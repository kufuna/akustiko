<?php
class Admin {
  public $pageModel;
  private static $unauthorisedAccess = array(  'login', 'logout', 'addip');

  public function __construct($request) {
    //Admin_Lang::init($request);

    if(method_exists($this, "get_".$request->module)) {
      /* If user logged in */
      $user = AdminUser::getCurrentUser();
      if(!$user && !in_array($request->module, self::$unauthorisedAccess)) {
        $this->pageModel = $this->redirect(ADMIN_URL.'login');
      } else {
        $method = "get_".$request->module;
        $this->pageModel = $this->$method($request);
      }
    } else {
      $this->pageModel = $this->get_404("Page not found");
    }
  }

  private function get_home($vars) {
    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      Admin_PageMain::getModel($vars),
      Admin_PageFooter::getModel($vars)
     );
  }

  private function get_postmodule($vars) {
    if(!AdminUser::hasRight('modules')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }

    $mainModel = Admin_PageMain::getModel($vars);
    if(isset($mainModel->error)) {
      return $this->get_404($vars, $mainModel->error);
    }

    return array(
      $mainModel
     );
  }

  private function get_module($vars) {
    if(!AdminUser::hasRight('modules')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }

    $mainModel = Admin_PageMain::getModel($vars);
    if(isset($mainModel->error)) {
      return $this->get_404($vars, $mainModel->error);
    }

    if ($vars->action == 12 && $vars->id == 'synchronize') {
      $app = new Apricot;
      $app->synchronizeProductsList();
      $app->synchronizeProductRems();
      $app->synchronizeNotDescribedToDescribed();
    }

    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      $mainModel,
      Admin_PageFooter::getModel($vars)
     );
  }

  private function get_documentation($vars) {
    if(!AdminUser::hasRight('modules')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }

    $mainModel = Admin_PageMain::getModel($vars);
    if(isset($mainModel->error)) {
      return $this->get_404($vars, $mainModel->error);
    }

    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      $mainModel,
      Admin_PageFooter::getModel($vars)
     );
  }

  private function get_users($vars) {
    if(!AdminUser::hasRight('users')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }
    $result = true;

    if(isset($_POST['delete'])) {
      Admin_DB::removeUser($_POST['delete']);
      return array(
        (object) array(  "tpl" => "data", "data" => (object) array(  'success' => true  )  )
       );
    } else if(isset($_POST['upsertUser'])) {
      if(isset($_POST['userid']) and $_POST['userid']) {
        $result = AdminUser::updateProfile($_POST);
      } else {
        $result = AdminUser::register($_POST);
        if($result === true) {
          return $this->redirect(ADMIN_URL.'users/');
        }
      }
    }

    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      Admin_PageMain::getModel($vars, $result),
      Admin_PageFooter::getModel($vars)
     );
  }

  private function get_super_user($vars) {
    if(!AdminUser::hasRight('super_user')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }

    if(isset($_POST['update_settings'])) {
      $settings = Settings::getSettings();

      foreach($settings as $key => $val){
        if(isset($_POST[$key])){
          $settings->$key = $_POST[$key];
        }
      }

      if(!isset($_POST['DEBUG'])) $settings->{'DEBUG'} = 0;

      Settings::updateSettings($settings);
    }

    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      Admin_PageMain::getModel($vars),
      Admin_PageFooter::getModel($vars)
     );
  }


  private function get_logs($vars) {
    if(!AdminUser::hasRight('logs')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }

    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      Admin_PageMain::getModel($vars),
      Admin_PageFooter::getModel($vars)
     );
  }

  private function get_seo($vars) {
    if(!AdminUser::hasRight('seo')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }

    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      Admin_PageMain::getModel($vars),
      Admin_PageFooter::getModel($vars)
     );
  }

  private function get_trash($vars) {
    if(!AdminUser::hasRight('logs')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }

    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      Admin_PageMain::getModel($vars),
      Admin_PageFooter::getModel($vars)
     );
  }



  private function get_stats($vars) {
    if(!AdminUser::hasRight('stats')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }

    if(isset($_POST['clear_stats'])) {
      DB_Base::clearAllStats();
    }

    try {
      Stats::parse(Settings::getSetting('ACCESS_LOG_PATH'));
    } catch(Exception $e) {
      $vars->error = $e->getMessage();
    }

    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      Admin_PageMain::getModel($vars),
      Admin_PageFooter::getModel($vars)
     );
  }

  private function get_texts($vars) {
    if(!AdminUser::hasRight('texts')) {
      return $this->get_404($vars, 'You don\'t have permission to see this page');
    }

    if(isset($_POST['key'])) {
      Admin_Lang::updateLang($_POST, Lang::$lang);
      return array(
        (object) array(  "tpl" => "data", "data" => (object) array(  'success' => true  )  )
       );
    } else if(isset($_POST['delete'])) {
      if(!AdminUser::hasRight('texts_add_remove')) {
        return $this->get_404($vars, 'You don\'t have permission to see this page');
      }

      Admin_Lang::removeLang($_POST['delete']);
      return array(
        (object) array(  "tpl" => "data", "data" => (object) array(  'success' => true  )  )
       );
    }

    return array(
      Admin_PageHead::getModel($vars),
      Admin_PageHeader::getModel($vars),
      // Admin_PageLeft::getModel($vars),
      Admin_PageMain::getModel($vars),
      Admin_PageFooter::getModel($vars)
     );
  }

  private function get_login($vars) {

    $data = array();

    if(isset($_POST['submit'])) {

      try {

        $recaptcha = AdminUser::recaptcha(array(
          'g-recaptcha-response' => @$_POST['g-recaptcha-response']
        ));

        $status = AdminUser::login($_POST);

        if($status){
          return $this->redirect(ADMIN_URL);
        }

      } catch (Exception $e) {

        $data = array(  'success' => false, 'error' => $e->getMessage());

      }
  }

    return array(
      (object) array(  "tpl" => "login", "data" => (object) $data  )
     );
  }


  private function get_addip($request) {

    $clientIp = $_SERVER['REMOTE_ADDR'];
    $random = $request->action;

    Admin_DB::updateUserIps($random, $clientIp);

    return $this->redirect(ADMIN_URL.'login');
  }

  private function get_logout($vars) {
    AdminUser::destroy();

    return $this->redirect(ADMIN_URL.'login');
  }

  private function get_404($vars, $err = '404 Page not found') {
    return array(
      (object) array( "tpl" => "404", "data" => (object) array(  'error' => $err  ) )
    );
  }

  private function redirect($url) {
    return array(
      (object) array( "tpl" => "redirect", "data" => (object) array(  'url' => $url  ) )
    );
  }
}
