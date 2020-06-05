<?php
class P_Codegeneratorform extends Plugin {

  public static $pdo2 = null;

  public static function connect2() {
    try {
      self::$pdo2 = new PDO('mysql:host='.DB_HOST2.';dbname='.DB_NAME2, DB_USER2, DB_PASS2);
    } catch(PDOException $e) {
      return false;
    }

    self::$pdo2->query("SET NAMES 'utf8'");
    self::$pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return true;
  }

  public $subscribes = array(
    'action' => 'processAction',
    'beforeRender' => 'processRender'
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
  
  public function processAction($data) {



    $count = isset($data['count']) ? (int) $data['count'] : 0;
    $doctorId = isset($data['doctor']) ? (int) $data['doctor'] : 0;
    
    function codeExists($code) {
      $st = DB::$pdo->query("SELECT nmb FROM cabinet_numbers WHERE nmb = $code");
      return $st->fetchObject();
    }
    
    function getDoctor($id) {

      P_Codegeneratorform::connect2();
      $st = P_Codegeneratorform::$pdo2->query("SELECT * FROM our_doctors_ka WHERE id = $id");
      return $st->fetchObject();
    }
    
    $Doctor = getDoctor($doctorId);
    
    $generatedCodes =  array();
    
    if($Doctor) {
      for($x = 0; $x < $count; $x++) {
        do {
          $code = rand(10000000, 99999999);
        } while(codeExists($code));
        
        $generatedCodes[] = array( "Code" => $code );
        DB::$pdo->query("INSERT INTO cabinet_numbers (`nmb`, `used`, `doctor_id`) VALUES ($code, 0, $doctorId)");
      }
    }
    
    Excel::downloadExcel($generatedCodes, URL::escapeURL($Doctor->doctor_name).".xls");
    exit;
  }

  public function processRender() {
    
  
    $this->renderView();
  }

  protected function renderView() {
    $cfg = $this->cfg;
    $this->view = ''
      .'<form method="'.$cfg->method.'" action="'.$cfg->action.'" class="main" enctype="'.$cfg->enctype.'">'
        .'<fieldset>'
          .'<div class="widget grid9" style=" margin: 0 auto;">'
            .'<div class="whead"><h6>'.$cfg->title.'</h6><div class="clear"></div></div>'
            .'{InnerPlugin}'
            
            .'<div class="formRow">'
              .'<button type="submit" name="post" class="buttonS bBlue ">'.$cfg->submitbtnText.'</button>'
              .'<div class="clear"></div>'
            .'</div>'
            
          .'</div>'
        .'</fieldset>'
      .'</form>';
  }
  
  protected function parseConfig($cfg) {
    $defaults = array(
      'title' => '',
      'action' => POSTMODULE_URL.'generate/',
      'enctype' => 'multipart/form-data',
      'method' => 'post',
      'submitbtnText' => 'add'
    );
    
    $this->mergeConfig($cfg, $defaults);
  }
}
