<?php
class P_Textarea extends Plugin {
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
    if($this->cfg->readOnly) {
      return $this->getCurrentValue();
    }
    
    return isset($data[$this->cfg->field_name]) ? $data[$this->cfg->field_name] : '';
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
    $defaults = array(
      'label' => 'Text area',
      'placeholder' => '',
      'value' => '',
      'field_name' => 'plugin_textarea_'.(++self::$inc),
      'required' => false,
      'escape' => array(),
      'errorText' => 'Textarea is empty',
      'columnName' => 'text',
      'rows' => 8,
      'cols' => '',
      'readOnly' => false
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
