<?php
class P_MultiField extends Plugin {
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
    if(!isset($data[$this->cfg->key_name]) || !isset($data[$this->cfg->val_name])) {
      return '[]';
    }
  
    $fields = array();
    
    $i = 0;
    foreach($data[$this->cfg->key_name] as $key) {
      $fields[] = array( $key => isset($data[$this->cfg->val_name][$i]) ? $data[$this->cfg->val_name][$i] : '' );
      
      $i++;
    }

  
    return json_encode($fields);
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
    $increment = ++self::$inc;
  
    $defaults = array(
      'label' => 'Multi fields',
      'key_placeholder' => 'Key',
      'val_placeholder' => 'Value',
      'value' => '',
      'key_name' => 'plugin_multifield_key_'.($increment),
      'val_name' => 'plugin_multifield_val_'.($increment),
      'id' => 'plugin_multifield_id_'.($increment),
      'required' => false,
      'escape' => array(),
      'errorText' => 'Multi field is empty',
      'columnName' => 'html',
      'readOnly' => false
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
