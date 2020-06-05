<?php
class P_LinkField extends Plugin {
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
    $value = isset($data[$cfg->field_name]) ? $data[$cfg->field_name] : '';

    if($cfg->required and !$value) {
      throw new Exception($cfg->label." is empty");
    }

    $value = $this->escape($value);
    
    $value = $this->escapeLink($value);

    return $value;
  }
  
  private function renderer($v) {
    return str_replace('{SITE_URL}', SITE_URL, $v);
  }
  
  private function escapeLink($v) {
    global $CFG;
  
    $rootUrl = substr(ROOT_URL, 0, strlen(ROOT_URL) - 1); // Remove last slash
    
    $ms = array(  );
    $pattern = '';
    foreach($CFG['SITE_LANGS'] as $lang) {
      $pattern .= '/'.$lang.'/|';
    }
    
    if(preg_match('~('.$rootUrl.'('.$pattern.'/))~', $v, $ms)) {
      return preg_replace('~('.$rootUrl.'('.$pattern.'/))~', '{SITE_URL}', $v);
    } else {
      return $v;
    }
  }
  
  public function setValue($data) {
    $this->cfg->value = isset($data->{$this->cfg->columnName}) ? $data->{$this->cfg->columnName} : '';
    $this->cfg->value = $this->renderer($this->cfg->value);
  }

  public function renderView() {
    $cfg = $this->cfg;
    $this->view = ''
      .'<div class="form-group">'
        .'<label>'.$cfg->label.':  '.($cfg->required ? '<sup class="field-required-sup">*</sup>' : '').'</label>'
        .'<input type="text" name="'.$cfg->field_name.'" placeholder="'.$cfg->placeholder.'"'.($cfg->readOnly ? ' readonly="readonly"' : '').' value="'.$cfg->value.'" class="form-control '.($cfg->required ? 'field-required textfield-field' : '').'" '.($cfg->required ? 'data-error-text="'.$cfg->label.'"' : '').'/>'
      .'</div>';
  }

  
  protected function parseConfig($cfg) {
    $defaults = array(
      'label' => 'Link field',
      'placeholder' => '',
      'value' => '',
      'field_name' => 'plugin_linkfield_'.(++self::$inc),
      'required' => false,
      'escape' => array(),
      'errorText' => 'Linkfield is empty',
      'columnName' => 'link',
      'readOnly' => false
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
