<?php
class P_DisplayField extends Plugin {
  protected static $inc = 0;

  public $subscribes = array(
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
  
  public function setValue($data) {
    $cfg = $this->cfg;
  
    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : '';
    
    if($cfg->link) {
      $cfg->link = ADMIN_URL.str_replace('{id}', $this->cfg->value, $cfg->link);
    }
    
    if($cfg->full_link) {
      $cfg->link = str_replace('{id}', $this->cfg->value, $cfg->full_link);
    }
    
    if($cfg->tableName) {
      $data = DB::get(array(
        'table' => $cfg->tableName,
        'query' => array( 'id' => $this->cfg->value ),
        'multiLang' => $cfg->multiLang,
        'single' => true
      ));
      
      if($data && isset($data->{$cfg->displayColumn})) {
        $this->cfg->value = $data->{$cfg->displayColumn};
      }
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
      'label' => 'Display field',
      'value' => '',
      'columnName' => 'id',
      'displayColumn' => '',
      'tableName' => '',
      'multiLang' => true,
      'link' => '',
      'full_link' => ''
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
