<?php
class P_CustomFloorPlan extends Plugin {
  protected static $inc = 0;
  private $allowed_img_types = array( 'jpg', 'jpeg', 'png', 'gif' );
  private $currentValue = null;

  public $subscribes = array(
    'itemRemove' => 'itemRemove',
    'beforeAction' => 'getData',
    'beforeRender' => 'renderView',
    'setValue' => 'setValue'
  );

  public function __construct($config) {
    $this->rawCfg = isset($config['config']) ? $config['config'] : array();
    $this->rootCfg = isset($config['rootConfig']) ? $config['rootConfig'] : array();
    $this->mode = isset($config['mode']) ? $config['mode'] : '';
    $this->multiLang = isset($this->rootCfg->multiLang) ? $this->rootCfg->multiLang : true;
    $this->urlData = isset($config['urlData']) ? $config['urlData'] : array();
  
    $this->parseConfig($this->rawCfg);
  }
  
  private function loadCurrentData() {
    $editId = isset($this->urlData->id) ? $this->urlData->id : 0;
    
    if($editId) {
      $editItem = DB_BASE::get(array(
        'table' => $this->rootCfg->tableName,
        'query' => array( 'id' => $editId ),
        'multiLang' => $this->multiLang
      ));
      
      $this->currentValue = $editItem;
    }
  }

  public function getData($data) {
    global $CFG;
    
    $cfg = $this->cfg;
    
    if($this->mode == 'edit') {
      $this->loadCurrentData();
    }
    
    $floorCoords = isset($_POST['floors_input_'.$cfg->id]) ? stripslashes($_POST['floors_input_'.$cfg->id]) : '';
    $floorCoords = json_decode($floorCoords);
    
    if($floorCoords) {
      $secondfloor = DB_BASE::get(array(
        'table' => $cfg->second_floors_table,
        'query' => array('id' => $this->currentValue->new_floors_id),
        'multiLang' => false,
        'single' => true
      ));
      if(!$secondfloor) throw new Exception("Please attach floor");
      foreach($CFG['SITE_LANGS'] as $lang) {
        $flatsTable = !isset($cfg->flats_multiLang) || $cfg->flats_multiLang ? $cfg->flats_table.'_'.$lang : $cfg->flats_table;
        cn_db_remove($flatsTable, array( 'floor_id' => $this->currentValue->id ));
        foreach($floorCoords as $k => $v) {
          $data = array( 'floor_id' => $this->currentValue->id );
          $data[$cfg->apNumberCol]  = $k;
          $data[$cfg->coordsColumn] = $v;
          cn_db_insert($data, $flatsTable);
        }
      }
    }
    
    $selfData = isset($this->currentValue->{$cfg->columnName}) ? $this->currentValue->{$cfg->columnName} : '';
    
    $files = cn_get_files($cfg->field_name);
    if(count($files) == 0) { // No file chosen
      if($this->mode == 'edit') {
        return $selfData;
      } else if($this->cfg->required) {
        throw new Exception("Please choose photo");
      } else {
        return '';
      }
    }
    
    $selfData = json_decode($selfData);
    if(!$selfData) $selfData = (object) array( 'src' => '' );
    
    $src = cn_upload_file($files[0], $cfg->folder, $this->allowed_img_types);
    $fullSrc = UPLOAD_FOLDER.$cfg->folder.'/'.$src;
    
    $extension = cn_get_extension($src);
    if($extension != 'jpg' and !in_array($extension, $cfg->types)) {
      $src = cn_convert_to_jpg($fullSrc);
      $fullSrc = UPLOAD_FOLDER.$cfg->folder.'/'.$src;
    }
    
    if($this->mode == 'edit') {
      $currentSrc = $selfData->src;
      $currentFullSrc = UPLOAD_FOLDER.$cfg->folder.'/'.$currentSrc;
      self::removeFromDisk($currentSrc, $currentFullSrc);
    }
    
    list($width, $height) = getimagesize($fullSrc);
    
    $result = array(
      'width' => $width,
      'height' => $height,
      'src' => $src
    );
    
    return json_encode($result);
  }
  
  private function removeFromDisk($src, $fullSrc) {
    $tableName = $this->rootCfg->tableName;
  
    if(file_exists($fullSrc)) {
      if(!$this->multiLang || !cn_image_is_used($src, $this->cfg->columnName, $tableName)) {
        @unlink($fullSrc);
      }
    }
  }
  
  public function setValue($data) {
    $row = $data;
    $buildingImage = isset($data->{$this->cfg->columnName}) ? json_decode($data->{$this->cfg->columnName}) : false;
    
    if($buildingImage) {
      $buildingImage->src = ROOT_URL.'uploads/'.$this->cfg->folder.'/'.$buildingImage->src;
      $this->cfg->value = $buildingImage;
      if(!isset($this->cfg->flats_multiLang) || $this->cfg->flats_multiLang){
        $flats = DB::get(array(
          'table' => $this->cfg->flats_table,
          'query' => array( 'floor_id' => $row->id)
        ));
      }else{
        $flats = DB::get(array(
          'table' => $this->cfg->flats_table,
          'multiLang' => false,
          'query' => array( 'floor_id' => $row->id)
        ));
      }

      $this->cfg->value->flats = $flats;
    } else {
      $this->cfg->value = '';
    }
  }
  
  private function getCropInfo($data) {
    $cfg = $this->cfg;
    
    if(!isset($data[$cfg->crop_name])) return false;
    else $str = $data[$cfg->crop_name];
    
    try {
      return json_decode($str);
    } catch(Exception $e) {
      return false;
    }
  }

  public function renderView() {
    $cfg = $this->cfg;
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }
  
  protected function parseConfig($cfg) {
    $currentInc = (++self::$inc);
  
    $defaults = array(
      'label' => 'Photo',
      'value' => '',
      'folder' => '',
      'second_floors_table' => 'test',
      'flats_multiLang' => true,
      'field_name' => 'plugin_floorPlan_'.$currentInc,
      'id' => 'plugin_floorPlan_id_'.$currentInc,
      'preview_name' => 'plugin_floorPlan_'.$currentInc,
      'required' => false,
      'types' => array( 'jpg', 'png', 'gif' ),
      'columnName' => 'image',
      'flats_table' => 'building_flats',
      'apNumberCol' => 'ap_number',
      'floors_key' => 'project_id',
      'coordsColumn' => 'coords'
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
  
  public function itemRemove($data) {
    $this->currentValue = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : null;
    
    if($this->currentValue) {
      $src = $this->currentValue;
      $fullSrc = UPLOAD_FOLDER.$this->cfg->folder.'/'.$src;
      self::removeFromDisk($src, $fullSrc);
    }
  }
}

