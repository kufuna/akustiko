<?php
class P_EditorTextarea extends Plugin {
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
    return isset($data[$this->cfg->field_name]) ? stripslashes($data[$this->cfg->field_name]) : '';
  }
  
  public function setValue($data) {
    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : '';
  }

  public function renderView() {
    $cfg = $this->cfg;
    $inc = self::$inc;
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }
  
  protected function parseConfig($cfg) {
    $defaults = array(
      'label' => 'WYSIWYG editor',
      'placeholder' => 'Textarea',
      'value' => '',
      'field_name' => 'plugin_editortextarea_'.(++self::$inc),
      'id' => 'plugin_editortextarea_'.(++self::$inc),
      'required' => false,
      'escape' => array(),
      'errorText' => 'Textarea is empty',
      'columnName' => 'text',
      'rows' => 20,
      'cols' => ''
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
