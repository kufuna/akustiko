<?php
class P_MultiselectBox extends Plugin {
  protected static $inc = 0;

  public $subscribes = array(
    'beforeAction' => 'getData',
    'beforeRender' => 'renderView',
    'setValue' => 'setValue'
  );
  
  private $modules = array();

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

    $value = $this->escape($value);

    return json_encode($value);
  }
  
  public function setValue($data) {
    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : '';
  }
  
  private function renderOptions() {
    $cfg = $this->cfg;
    
    $tableName = $cfg->tableName;
    $multiLang = $cfg->multiLang;

    if($cfg->dataLoaderMethod) {
      $methodName = $cfg->dataLoaderMethod;
      $this->modules = DB::$methodName();
    } else {
      $this->modules = DB_BASE::get(array(
        'table' => $tableName,
        'multiLang' => $multiLang
      ));
    }
    
    if($cfg->defaultValue) {
      array_unshift($this->modules, $cfg->defaultValue);
    }
    
    $result = '';
    
    foreach($this->modules as $mod) {
      if(!isset($mod->{$cfg->optionValue})) continue;

      $selected = in_array($mod->{$cfg->optionValue}, (array)(json_decode($this->cfg->value))) ? ' selected="selected"' : '';
      $result .= '<option'.$selected.' value="'.$mod->{$cfg->optionValue}.'">'.$mod->{$cfg->optionName}.'</option>';
    }
    
    return $result;
  }

  public function renderView() {
    $cfg = $this->cfg;
    $options = $this->renderOptions();
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }
  
  protected function parseConfig($cfg) {
    $uniqInc = ++self::$inc;
  
    $defaults = array(
      'label' => 'Select box',
      'placeholder' => '',
      'value' => '',
      'field_name' => 'plugin_multiselectbox_'.$uniqInc,
      'id' => 'plugin_multiselectbox_id_'.$uniqInc,
      'required' => false,
      'escape' => array(),
      'errorText' => 'Selectbox is empty',
      'columnName' => 'category',
      'width' => '350',
      'optionName' => 'name',
      'optionValue' => 'value',
      'search' => false,
      'dataLoaderMethod' => false,
      'tableName' => 'test',
      'multiLang' => true,
      'defaultValue' => false
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
