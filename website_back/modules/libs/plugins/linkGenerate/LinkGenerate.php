<?php
class P_LinkGenerate extends Plugin {
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
  }
  
  public function setValue($data) {

  	$table_data = DB_BASE::get(array(
      'table' => $this->cfg->tableFor,
      'query' => array( 'id' => $data->{$this->cfg->column} ),
      'multiLang' => $this->cfg->multiLang
    ));

    $this->cfg->value = $table_data->id;
  }

  public function renderView() {
    $cfg = $this->cfg;

    if ($this->cfg->method == 'import') {
    	$this->view = '<div class="formRow"><a href="'.SITE_URL.$this->cfg->link.'/'.$this->cfg->method.'/'.$this->cfg->value.'" target="_blank" class="buttonS bBlue ">'.$this->cfg->label.'</a></div>';
    } elseif ($this->cfg->method == 'export') {
    	$this->view = '<div class="formRow"><a href="'.SITE_URL.$this->cfg->link.'/'.$this->cfg->method.'/'.$this->cfg->value.'" target="_blank" class="buttonS bBlue ">'.$this->cfg->label.'</a></div>';
    }
  }
  
  protected function parseConfig($cfg) {
    $defaults = array(
    	'label' => 'Update',
      'value' => '',
      'tableFor' => 'projects',
      'column' => 'project_id',
      'multiLang' => true,
      'link' => 'excels',
      'method' => 'import'
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
