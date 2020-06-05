<?php
class P_MailTemplateSender extends Plugin {
  protected $view;
  protected $cfg;
  protected static $inc = 0;

  public function __construct($cfg) {
    $this->parseConfig($cfg);
    $this->renderView();
  }

  public function getData($data, $modeConfig) {
    return isset($data[$this->cfg->field_name]) ? $data[$this->cfg->field_name] : '';
  }
  
  public function setValue($val) {
    $this->cfg->value = $val;
    $this->renderView();
  }

  protected function renderView() {
    $cfg = $this->cfg;
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }
  
  protected function parseConfig($cfg) {
    $increment = ++self::$inc;
  
    $defaults = array(
      'label' => 'HTML Template',
      'placeholder' => '',
      'value' => '',
      'field_name' => 'template_generated_html_plugin_mailtemplatesender_id_'.($increment),
      'id' => 'plugin_mailtemplatesender_id_'.($increment),
      'required' => false,
      'escape' => array(),
      'errorText' => 'HTML template is empty',
      'columnName' => 'html',
      'rows' => 20,
      'cols' => ''
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
