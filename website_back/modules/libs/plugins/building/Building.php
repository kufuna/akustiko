<?php
class P_Building extends Plugin {
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
    $cfg = $this->cfg;
    
    if($this->mode == 'edit') {
      $this->loadCurrentData();
    }
    
    $floorCoords = isset($_POST['floors_input_'.$cfg->id]) ? stripslashes($_POST['floors_input_'.$cfg->id]) : '';
    $floorCoords = json_decode($floorCoords);
    
    if($floorCoords && $this->currentValue) {
      // cn_db_remove($cfg->floors_table, array( 'project_id' => $this->currentValue->id ));
      // cn_db_truncate($cfg->floors_table);
      cn_db_update_building(array( $cfg->floors_key => 0, 'status' => 1 ), $cfg->floors_table, $cfg->floors_key, $this->currentValue->id, false);
      
      foreach($floorCoords as $k => $v) {
        $data = array( 'coords' => $v, $cfg->floors_key => $this->currentValue->id,'ord' => $k );
        $data[$cfg->floor_number_col] = $k;
        cn_db_insert($data, $cfg->floors_table);
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
    
    if($cfg->thumbs && count($cfg->thumbs)) {
      cn_create_thumbs($fullSrc, $cfg->thumbs);
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
    $buildingImage = isset($row->{$this->cfg->columnName}) ? json_decode($row->{$this->cfg->columnName}) : false;
    
    if($buildingImage) {
      $buildingImage->src = ROOT_URL.'uploads/'.$this->cfg->folder.'/'.$buildingImage->src;
      $this->cfg->value = $buildingImage;
      
      $floors = DB_BASE::get(array(
        'table' => $this->cfg->floors_table,
        'query' => array( $this->cfg->floors_key => $row->id ),
        'multiLang' => false
      ));
      
      $this->cfg->value->floors = $floors;
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
      'field_name' => 'plugin_building_'.$currentInc,
      'id' => 'plugin_building_id_'.$currentInc,
      'preview_name' => 'plugin_building_'.$currentInc,
      'required' => false,
      'types' => array( 'jpg', 'png', 'gif' ),
      'columnName' => 'buildingimage',
      'floors_table' => 'building_floors',
      'floors_key' => 'project_id',
      'floor_number_col' => 'floor_number',
      'thumbs' => array()
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
