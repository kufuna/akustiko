<?php
class P_HiddenTextfield extends Plugin {
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
    
    $value = isset($data[$cfg->field_name]) ? stripslashes($data[$cfg->field_name]) : '';

    if($cfg->required and !$value) {
      throw new Exception($cfg->label." is empty");
    }

    $value = $this->escape($value);
    
    return $value;
  }
  
  
  public function setValue($data) {

    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : '';

  }
  
  public function setMode($mode, $data) {
    $this->mode->type = $mode;
    $this->mode->data = $data;
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
      'placeholder' => '',
      'value' => 'user',
      'field_name' => 'plugin_hiddenfield_'.(++self::$inc),
      'required' => false,
      'escape' => array(),
      'errorText' => 'Textfield is empty',
      'columnName' => 'title',
      'readOnly' => false
    );

    $this->mergeConfig($cfg, $defaults);
  }
}
