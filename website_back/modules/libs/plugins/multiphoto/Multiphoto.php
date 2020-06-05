<?php
class P_Multiphoto extends Plugin {
  protected static $inc = 0;
  private $allowed_img_types = array( 'png','jpg', 'jpeg', 'png', 'gif' );
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
    
    if($this->mode == 'remove') return array();

    if($this->mode == 'edit') {
      $this->loadCurrentData();
    }
    
    $cropInfo = $this->getCropInfo($data);
    
    $image_order = $this->getImageOrder($data);
    if(!is_array($image_order)) {
      throw new Exception("Unable to read image order");
    }
    
    $files = cn_get_files($cfg->field_name);
    
    $photoSources = array();
    foreach($files as $file) {
      $src = cn_upload_file($file, $cfg->folder, $this->allowed_img_types, $cfg->info);
      if($cfg->info) {
        $fullInfo = $src;
        $src = $src->src;
      }
      
      $extension = cn_get_extension($src);
      if($extension != 'jpg' and !in_array($extension, $cfg->types)) {
        $src = cn_convert_to_jpg(UPLOAD_FOLDER.$cfg->folder.'/'.$src);
        if($cfg->info) {
          $fullInfo->type = 'jpg';
          $fullInfo->src = preg_replace('#\.(.*?)$#', '.jpg', $fullInfo->src);
        } else {
          $src = preg_replace('#\.(.*?)$#', '.jpg', $src);
        }
      }
      
      cn_create_thumbs(UPLOAD_FOLDER.$cfg->folder.'/'.$src, $cfg->thumbs);
      $photoSources[] = $cfg->info ? $fullInfo : $src;
    }
    
    $photoSourcesFinal = array();
    foreach($image_order as $ord) {
      if(isset($ord->new)) {
        if(isset($photoSources[$ord->new])) $photoSourcesFinal[] = $photoSources[$ord->new];
      } else if(isset($ord->old)) {
        if(isset($this->currentValue[$ord->old])) {
          $photoSourcesFinal[] = $this->currentValue[$ord->old];
          $this->currentValue[$ord->old] = 'ADDED';
        }
      }
    }
    
    $this->removeOldPhotos();
    
    $i = 0;
    foreach($photoSourcesFinal as $photoItem) {
      if(isset($cropInfo[$i]) && $cropInfo[$i]) {
        $src = $cfg->info ? $photoItem->src : $photoItem;
        $tmpSrc = UPLOAD_FOLDER.$cfg->folder.'/'.$src;
        cn_crop_image($tmpSrc, $cropInfo[$i]);
	      @chmod($tmpSrc, 0666);
		    cn_create_thumbs($tmpSrc, $cfg->thumbs);
      }
      $i++;
    }
    
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
        if($this->cfg->info) {
          $src = $images[$i]->src;
        } else {
          $src = $images[$i];
        }
        
        $images[$i] = ROOT_URL.'uploads/'.$this->cfg->folder.'/'.$src;
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
        $oldPhoto = $this->cfg->info ? $oldPhoto->src : $oldPhoto;
        if(!$this->multiLang || !cn_image_is_used($oldPhoto, $cfg->columnName, $tableName)) {
          if($cfg->thumbs) {
            foreach($cfg->thumbs as $thumbCfg) {
              $prefix = isset($thumbCfg->prefix) && $thumbCfg->prefix ? $thumbCfg->prefix.'_' : '';
              @unlink(UPLOAD_FOLDER.$cfg->folder.'/'.$prefix.$oldPhoto);
            }
          } else {
            @unlink(UPLOAD_FOLDER.$cfg->folder.'/'.$oldPhoto);
          }
        }
      }
    }
  }
  
  private function getCropInfo($data) {
    $cfg = $this->cfg;
    
    $cropInfo = isset($data[$cfg->crop_name]) && is_array($data[$cfg->crop_name]) ? $data[$cfg->crop_name] : array();
    for($i = 0; $i < count($cropInfo); $i++) {
      try { $cropInfo[$i] = json_decode($cropInfo[$i]); } catch(Exception $e) { $cropInfo[$i] = false; }
    }
    
    return $cropInfo;
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
      'label' => 'Photos',
      'value' => array(),
      'folder' => '',
      'field_name' => 'plugin_multiphoto_'.$currentInc,
      'crop_name' => 'plugin_multiphotocrop_'.$currentInc,
      'preview_name' => 'plugin_multiphotopreview_'.$currentInc,
      'image_order' => 'plugin_multiphoto_image_order_'.$currentInc,
      'id' => 'plugin_multiphoto_id_'.$currentInc,
      'thumbs' => array(),
      'required' => false,
      'columnName' => 'images',
      'types' => array( 'jpg','png','jpeg','png','svg' ),
      'maxCount' => 999,
      'info' => false
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
  
  public function itemRemove($data) {
    $this->currentValue = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : null;
    $this->currentValue = $this->parseValue($this->currentValue, false);
    $tableName = $this->rootCfg->tableName;
    
    if(is_array($this->currentValue)) {
      foreach($this->currentValue as $src) {
        $src = $this->cfg->info ? $src->src : $src;
        $fullSrc = UPLOAD_FOLDER.$this->cfg->folder.'/'.$src;
        if(file_exists($fullSrc)) {
          if(!$this->multiLang || !cn_image_is_used($src, $this->cfg->columnName, $tableName)) {
            @unlink($fullSrc);
          }
        }
      }
    }
  }
}
