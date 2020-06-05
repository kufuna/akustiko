<?php
class P_ColorPicker extends Plugin {
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

    if($cfg->required and !$value) {
      throw new Exception($cfg->label." is empty");
    }

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
      'label' => 'Choose color',
      'value' => '900',
      'field_name' => 'plugin_colorpicker_'.$uniqInc,
      'id' => 'plugin_colorpicker_id_'.$uniqInc,
      'required' => false,
      'errorText' => 'Color is empty',
      'columnName' => 'color'
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
