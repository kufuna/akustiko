<?php
class P_Singlephoto extends Plugin {
  protected static $inc = 0;
  private $allowed_img_types = array( 'jpg', 'jpeg', 'png', 'gif','svg' );
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

    if($config['parentConfig'] && isset($config['parentConfig']->tableName)) {
      $this->rootCfg->tableName = $config['parentConfig']->tableName;
      $this->multiLang = $config['parentConfig']->multiLang;
      if($config['parentConfig']->edit_action == $this->mode) {
        $this->mode = 'edit';
      }
    }

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
      $this->currentValue = $editItem->{$this->cfg->columnName};
    }
  }

  public function getData($data) {
    $cfg = $this->cfg;

    if($this->mode == 'edit') {
      $this->loadCurrentData();
    }

    $files = cn_get_files($cfg->field_name);
    if(count($files) == 0) { // No file chosen
      if($this->mode == 'edit') {
        $src = $this->currentValue;
        $fullSrc = UPLOAD_FOLDER.$cfg->folder.'/'.$src;

        if(isset($_POST[$cfg->delete_name]) && $_POST[$cfg->delete_name] == 'removed') { // Photo removed
          self::removeFromDisk($src, $fullSrc);
          return '';
        } else {
          $cropInfo = $this->getCropInfo($data);
          if($cropInfo) {
            cn_crop_image($fullSrc, $cropInfo);

            if($cfg->thumbs && count($cfg->thumbs)) {
              cn_create_thumbs($fullSrc, $cfg->thumbs);
            }
          }

          return $this->currentValue;
        }
      } else if($this->cfg->required) {
        throw new Exception("Please choose photo");
      } else {
        return '';
      }
    }

    $src = cn_upload_file($files[0], $cfg->folder, $this->allowed_img_types);
    $fullSrc = UPLOAD_FOLDER.$cfg->folder.'/'.$src;

    $extension = cn_get_extension($src);
    if($extension != 'jpg' && !in_array($extension, $cfg->types)) {
      $src = cn_convert_to_jpg($fullSrc);
      $fullSrc = UPLOAD_FOLDER.$cfg->folder.'/'.$src;
    }

    $cropInfo = $this->getCropInfo($data);
    if($cropInfo) {
      cn_crop_image($fullSrc, $cropInfo);
    }

    if($cfg->thumbs && count($cfg->thumbs)) {
      cn_create_thumbs($fullSrc, $cfg->thumbs);
    }

    if($this->mode == 'edit' && $this->currentValue) {
      $currentSrc = $this->currentValue;
      $currentFullSrc = UPLOAD_FOLDER.$cfg->folder.'/'.$currentSrc;
      self::removeFromDisk($currentSrc, $currentFullSrc);
    }

    return $src;
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
    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : null;
    if($this->cfg->value) {
      $this->cfg->value = ROOT_URL.'uploads/'.$this->cfg->folder.'/'.$this->cfg->value;
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
      'field_name' => 'plugin_singlephoto_'.$currentInc,
      'id' => 'plugin_singlephoto_id_'.$currentInc,
      'crop_name' => 'plugin_singlephotocrop_'.$currentInc,
      'delete_name' => 'plugin_singlephoto_delete_'.$currentInc,
      'preview_name' => 'plugin_singlephotopreview_'.$currentInc,
      'thumbs' => array(),
      'required' => false,
      'types' => array( 'jpg','png','jpeg','png','svg' ),
      'columnName' => 'image'
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
