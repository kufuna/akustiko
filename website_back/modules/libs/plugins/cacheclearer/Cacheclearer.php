<?php
class P_Cacheclearer extends Plugin {
  public $subscribes = array(
    'itemRemove' => 'clearCache',
    'action' => 'clearCache'
  );

  public function __construct($config) {
    $this->rawCfg = isset($config['config']) ? $config['config'] : array();
    $this->rootCfg = isset($config['rootConfig']) ? $config['rootConfig'] : array();
    $this->mode = isset($config['mode']) ? $config['mode'] : '';

    $this->parseConfig($this->rawCfg);
  }

  protected function parseConfig($cfg) {
    $defaults = array(
      'patterns' => []
    );

    $this->mergeConfig($cfg, $defaults);
  }

  public function clearCache() {
    foreach($this->cfg->patterns as $pattern) {
      if($pattern == '/') Cache::clearAll();
      else if($pattern == 'home') Cache::clearHome();
      else Cache::clearByPattern($pattern);
    }
  }

  protected function renderView() {}
}
