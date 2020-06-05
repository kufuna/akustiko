<?php
class Model {
  public $templates;
  public $isAdmin = false;

  public function __construct($request) {
    global $CFG;

    $this->init();

    if($request->admin) { // If requested admin page
      require_once __DIR__.'/../includes_admin.php';

      if(!DB_BASE::connect() || !Admin_DB::connect()) {
        CN_Error::err("Can't connect to database"); // Stops execution
      }

      define('DIR_TPLS', 'engine/admin_tpls');

      $adminPage = new Admin($request); // Create admin page model
      $this->templates = $adminPage->pageModel;
      $this->isAdmin = true;
    } else {
      define('DIR_TPLS', 'tpls');

//      if(defined('INIT_WEBUSER') && INIT_WEBUSER) {
//        WebUser::init(@$CFG['webuser'] ? $CFG['webuser'] : array());
//      }

      $Page = Route::getInstance($request);
      $this->templates = $Page->getTpls();

      DB_BASE::disconnect();
    }
  }

  private function init() {
    /* Include default pages */
    include __DIR__.'/../../pages/404/404.php';
  }
}
