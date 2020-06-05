<?php
class P_Urlfield extends Plugin {
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
  
    $this->parseConfig($this->rawCfg);
    
    if(!$this->cfg->readOnly) {
      $this->subscribes['beforeAction'] = 'getData';
    }
  }

  public function getData($data, $modeConfig) {
    $cfg = $this->cfg;
    $value = isset($data[$cfg->field_name]) ? $data[$cfg->field_name] : '';

    if($cfg->required and !$value) {
      throw new Exception($cfg->label." is empty");
    }

    $value = $this->escape($value);

    return $value;
  }
  
  public function setValue($val) {
    $this->cfg->value = $val;
  }

  public function renderView() {
    $cfg = $this->cfg;
    if($cfg->value) {
      $v = SITE_URL.$cfg->prefix;
      foreach($cfg->tpl as $tpl) {
        if(isset($tpl->static)) {
          $v .= $tpl->static.'/';
        } else if(isset($cfg->value->{$tpl->field})) {
          $part = $cfg->value->{$tpl->field};
          
          if($tpl->type == 'number') {
            $part = (int) $part;
          } else if($tpl->type == 'string') {
            $part = URL::escapeURL($part);
          }
          
          $v .= $part.'/';
        }
      }
      $cfg->value = $v;
    }
    $this->view = ''
      .'<div class="form-group">'
        .'<label class="form-label">'.$cfg->label.':'.($cfg->required ? '  <sup class="field-required-sup">*</sup>' : '').'</label>'
        .'<input type="text" '.($cfg->required ? 'data-error-text="'.$cfg->label.'"' : '').'  class="form-control'.($cfg->required ? ' field-required textfield-field' : '').'" '.($cfg->readOnly ? ' readonly="readonly"' : '').' value="'.$cfg->value.'" />'
      .'</div>';
  }
  
  protected function parseConfig($cfg) {
    $defaults = array(
      'label' => 'URL',
      'value' => '',
      'readOnly' => true,
      'required' => false,
      'prefix' => '',
      'tpl' => array()
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
