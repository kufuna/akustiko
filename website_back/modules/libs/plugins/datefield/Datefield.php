<?php
class P_Datefield extends Plugin {
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
    
    if($this->cfg->default == 'CURRENT_DATE') $this->cfg->value = date($this->cfg->format);
  }

  public function getData($data) {
    $cfg = $this->cfg;
    $value = isset($data[$cfg->field_name]) ? $data[$cfg->field_name] : '';
    
    $value = htmlspecialchars($value);

    if($cfg->required and !$value) {
      throw new Exception($cfg->label." is empty");
    }

    return $value;
  }
  
  public function setValue($data) {
    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : '';
    if($this->cfg->value) {
      $this->cfg->value = date($this->cfg->format, strtotime($this->cfg->value));
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
    $defaults = array(
      'label' => 'Text field',
      'value' => '',
      'field_name' => 'plugin_datefield_'.(++self::$inc),
      'required' => false,
      'errorText' => 'Date field is empty',
      'columnName' => 'date',
      'readOnly' => false,
      "format" => "Y-m-d",
      "default" => "CURRENT_DATE"
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
