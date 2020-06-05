<?php
class P_Tags extends Plugin {
  protected static $inc = 0;

  public $subscribes = array(
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

  public function getData($data) {
    $cfg = $this->cfg;
    
    $value = isset($data[$cfg->field_name]) ? $data[$cfg->field_name] : '';
    
    return $value;
  }
  
  public function setValue($data) {
    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : '';
  }

  public function renderView() {
    $cfg = $this->cfg;
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }
  
  protected function parseConfig($cfg) {
    $uniqInc = ++self::$inc;

    $defaults = array(
      'id' => 'plugin_tags_id_'.$uniqInc,
      'field_name' => 'plugin_tags_'.$uniqInc,
      'label' => 'Tags',
      'value' => '',
      'columnName' => 'tags',
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
