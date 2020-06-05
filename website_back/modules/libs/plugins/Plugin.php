<?php
abstract class Plugin {
  protected $view;
  protected $cfg;
  protected $rawCfg;
  protected $rootCfg;
  protected $mode;
  protected $children = array();
  protected $multiLang;
  
  public function __construct($cfg, $rootCfg) {}

  public function getView() {
    $output = preg_replace('#(\{InnerPlugin[_-a-zA-Z0-9]{0,200}\})#', '', $this->view);
    return $output;
  }
  
  public function append($cfg) {
    if(is_array($cfg)) {
      $pluginName = isset($cfg['pluginName']) ? $cfg['pluginName'] : 'InnerPlugin';
      $replacement = isset($cfg['data']) ? $cfg['data'] : '';
    } else {
      $pluginName = 'InnerPlugin';
      $replacement = $cfg;
    }
    $replacement .= '{'.$pluginName.'}';
    
    $this->view = str_replace('{'.$pluginName.'}', $replacement, $this->view);
  }

  public function getRawConfig() {
    return $this->rawCfg;
  }

  public function addChild($child) {
    $this->children[] = $child;
  }

  public function getChildren() {
    return $this->children;
  }

  protected function mergeConfig($cfg, $defaults) {
    $this->cfg = array();
    $cfg = (array) $cfg;


    foreach($defaults as $key => $val) {
      $this->cfg[$key] = isset($cfg[$key]) ? $cfg[$key] : $val;
    }
    $this->cfg = (object) $this->cfg;
  }

  protected function escape($value) {
    foreach($this->cfg->escape as $escape) {
      switch($escape) {
        case 'html':
          $value = htmlspecialchars($value); break;
        case 'slashes':
          $value = stripslashes($value); break;
        case 'tags':
          $value = strip_tags($value); break;
      }
    }

    return $value;
  }

  protected abstract function renderView();
}
