<?php
/* Abstract plugin */
class AP_Overview extends Plugin {
  private $tableName;
  private $multiLang;

  protected function renderView() {}

  public function __construct($cfg, $rootCfg) {
    parent::__construct($cfg, $rootCfg);
    $this->parseSelfConfig($rootCfg);
    $this->loadData();
  }
  
  private function parseSelfConfig($rootCfg) {
    $this->tableName = isset($rootCfg->tableName) ? $rootCfg->tableName : null;
    $this->multiLang = isset($rootCfg->multiLang) ? $rootCfg->multiLang : true;
  }
  
  private function loadData() {
    $data = DB_BASE::get(array(
      'table' => $this->tableName,
      'multiLang' => $this->multiLang
    ));
    
    $this->setLocalData($data);
  }
}
