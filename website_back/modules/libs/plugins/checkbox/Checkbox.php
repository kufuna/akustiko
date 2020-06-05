<?php
class P_Checkbox extends Plugin {
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
  
  private function getCurrentValue() {
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
      return $editItem->{$this->cfg->columnName};
    }
    
    return '';
  }

  public function getData($data) {
    $cfg = $this->cfg;
    if($cfg->readOnly) {
      return $this->getCurrentValue();
    }
    
    $value = isset($data[$cfg->field_name]) && $data[$cfg->field_name] == 'on' ? 1 : 0;

    return $value;
  }
  
  public function setValue($data) {
    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? (int) $data->{$this->cfg->columnName} : '';
  }

  public function renderView() {
    $cfg = $this->cfg;
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }
  
  protected function parseConfig($cfg) {
    $inc = ++self::$inc;
  
    $defaults = array(
      'label' => 'Text field',
      'value' => '',
      'field_name' => 'plugin_checkbox_'.($inc),
      'id' => 'plugin_checkbox_id_'.($inc),
      'columnName' => 'state',
      'readOnly' => false
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
