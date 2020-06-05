<?php
class P_Multifile extends Plugin {
  protected static $inc = 0;
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
    $this->editModeOnly = isset($this->rootCfg->editModeOnly) ? $this->rootCfg->editModeOnly : false;
    
    $this->parseConfig($this->rawCfg);
  }
  
  private function loadCurrentData() {
    $editId = isset($this->urlData->id) ? $this->urlData->id : 0;
      
    if($this->editModeOnly) {
      $editItem = DB_BASE::get(array(
        'table' => $this->rootCfg->tableName,
        'single' => true,
        'multiLang' => $this->multiLang
      ));
    } else if($editId) {
      $editItem = DB_BASE::get(array(
        'table' => $this->rootCfg->tableName,
        'query' => array( 'id' => $editId ),
        'multiLang' => $this->multiLang
      ));
    }
      
    if($editItem && isset($editItem->{$this->cfg->columnName})) {
      $this->currentValue = $this->parseValue($editItem->{$this->cfg->columnName}, false);
    }
  }

  public function getData($data) {
    $cfg = $this->cfg;
    $this->currentValue = array();
    
    if($this->mode == 'edit') {
      $this->loadCurrentData();
    }
    
    $image_order = $this->getImageOrder($data);
    
    $files = cn_get_files($cfg->field_name);
    
    $photoSources = array();
    foreach($files as $file) {
      $uploadedFile = cn_upload_file($file, $cfg->folder, $cfg->types, true);
      $photoSources[] = $uploadedFile;
    }
    
    $photoSourcesFinal = array();
    if(!is_array($image_order)) $image_order = array();
    foreach($image_order as $ord) {
      if(isset($ord->new)) {
        if(isset($photoSources[$ord->new])) $photoSourcesFinal[] = $photoSources[$ord->new];
      } else if(isset($ord->old)) {
        if(isset($this->currentValue[$ord->old]) and $this->currentValue[$ord->old] != 'ADDED') {
          $photoSourcesFinal[] = $this->currentValue[$ord->old];
          $this->currentValue[$ord->old] = 'ADDED';
        }
      }
    }
    
    $this->removeOldPhotos();
    
    $photoSourcesFinal = json_encode($photoSourcesFinal);
  
    return $photoSourcesFinal;
  }
  
  public function setValue($data) {
    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : '[]';
    $this->cfg->value = $this->parseValue($this->cfg->value);
  }
  
  private function parseValue($value, $absolutePath = true) {
    $images = json_decode($value);
    if(!$images) $images = array();
    
    if($absolutePath) {
      for($i = 0; $i < count($images); $i++) {
        $images[$i]->src = ROOT_URL.'uploads/'.$this->cfg->folder.'/'.$images[$i]->src;
      }
    }
    
    return $images;
  }
  
  private function removeOldPhotos() {
    $cfg = $this->cfg;
    $tableName = $this->rootCfg->tableName;
    $this->currentValue = is_array($this->currentValue) ? $this->currentValue : array();
  
    foreach($this->currentValue as $oldPhoto) {
      if($oldPhoto != 'ADDED') {
        if(!$this->multiLang || !cn_image_is_used($oldPhoto->src, $cfg->columnName, $tableName)) {
          @unlink(UPLOAD_FOLDER.$cfg->folder.'/'.$oldPhoto->src);
        }
      }
    }
  }
  
  private function getImageOrder($data) {
    $cfg = $this->cfg;
    $image_order = isset($data[$cfg->image_order]) ? str_replace('\"', '"', (string) $data[$cfg->image_order]) : '';
    try { $image_order = json_decode($image_order); } catch(Exception $e) { $image_order = false; }
    
    return $image_order;
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
      'label' => 'Files',
      'value' => array(),
      'folder' => '',
      'field_name' => 'plugin_multifile_'.$currentInc,
      'crop_name' => 'plugin_multifilecrop_'.$currentInc,
      'preview_name' => 'plugin_multifilepreview_'.$currentInc,
      'image_order' => 'plugin_multifile_image_order_'.$currentInc,
      'id' => 'plugin_multifile_id_'.$currentInc,
      'required' => false,
      'columnName' => 'files',
      'types' => array( 'pdf', 'docx', 'odf', 'xls', 'xlsx', 'ppt', 'doc','pptx' ),
      'maxCount' => 1000
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
  
  public function itemRemove($data) {
    $this->currentValue = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : null;
    $this->currentValue = $this->parseValue($this->currentValue, false);
    $tableName = $this->rootCfg->tableName;
      
    if(is_array($this->currentValue)) {
      foreach($this->currentValue as $file) {
        $fullSrc = UPLOAD_FOLDER.$this->cfg->folder.'/'.$file->src;
        if(file_exists($fullSrc)) {
          if(!$this->multiLang || !cn_image_is_used($file->src, $this->cfg->columnName, $tableName)) {
            @unlink($fullSrc);
          }
        }
      }
    }
  }
}
