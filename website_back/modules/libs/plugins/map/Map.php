<?php
class P_Map extends Plugin {
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
  
    $this->parseConfig($this->rawCfg);
  }

  public function getData($data) {
    $cfg = $this->cfg;
    $value = isset($data[$cfg->field_name]) ? $data[$cfg->field_name] : '';

    return stripslashes($value);
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
      'value' => '',
      'field_name' => 'plugin_map_'.$uniqInc,
      'id' => 'plugin_map_id_'.$uniqInc,
      'columnName' => 'map'
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
