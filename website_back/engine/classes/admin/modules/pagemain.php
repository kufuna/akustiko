<?php
class Admin_PageMain {
  public static function getModel($request, $result = true) {
    global $CFG;
    $data = array(  );

    switch($request->module) {
      case 'home':
        $data['title'] = 'Dashboard';
        $data['dashboard'] = DB::getDashboard();
        $data['authlogs'] = DB::getAuthLogs();
        $data['icon'] = 'screen';
        $data['fullscreen'] = false;

        if(count($CFG['stats']) == 0) {
          $CFG['stats'] = false;
        }

        if(isset($CFG['stats']) && $CFG['stats']) {
          foreach($CFG['stats'] as $label => $tableName) {
            $key = 'stats_'.$tableName;
            $data[$key] = DB::getStats($tableName);
          }
        }
        break;

      case 'users':
        $data['title'] = 'Users';
        $data['icon'] = 'link';
        $data['fullscreen'] = true;
        if($request->action == 'edit') {
          $data['modFile'] = 'includes/edituser.php';
          $data['user'] = Admin_DB::getUserById($request->id);
          $data['modules'] = Admin_DB::getModulesAndRights($request->id);
          $data['rights'] = explode(':', $data['user']->rights);
          if($result !== true) $data['error'] = $result;
        } else if($request->action == 'add') {
          $data['modFile'] = 'includes/edituser.php';
          $data['user'] = (object) AdminUser::getModel();
          $data['user']->id = 0;
          $data['modules'] = Admin_DB::getModules();;
          $data['rights'] = array(  );
          if($result !== true) $data['error'] = $result;
        } else {
          $data['modFile'] = 'includes/users.php';
          $data['users'] = Admin_DB::getAllUsers();
        }
        break;

      case 'texts':
        if(!in_array($request->action, $CFG['SITE_LANGS'])) $request->action = DEFAULT_LANG;
        $data['title'] = 'Texts';
        $data['icon'] = 'link';
        $data['fullscreen'] = true;
        $data['modFile'] = 'includes/texts.php';
        $data['allLangs'] = Admin_Lang::getAllLangs();
        $data['currentLangs'] = Admin_Lang::getLangByKey(Lang::$lang);
        $data['allowAddDelete'] = AdminUser::hasRight('texts_add_remove');
        $data['activeLang'] = in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG;
        break;

      case 'super_user':
        if(!in_array($request->action, $CFG['SITE_LANGS'])) $request->action = DEFAULT_LANG;

        if(isset($_POST['truncate-trash'])) {
          DB::$pdo->query("truncate cn_trash");
        }

        if(isset($_POST['truncate-logs'])) {
          DB::$pdo->query("truncate cn_action_logs");
        }

        if (isset($_POST['minify-js'])) {
          Minifier::minifyJs();
        }

        if (isset($_POST['minify-css'])) {
          Minifier::minifyCss();
        }

        if (isset($_POST['set-minify-mode'])) {
          Settings::set('MINIFY_FILES', $_POST['set-minify-mode']);
        }
        
        $data['settings'] = Settings::getSettings('general');
        $data['title'] = 'Super User';
        $data['icon'] = 'link';
        $data['fullscreen'] = true;
        $data['modFile'] = 'includes/super_user.php';
        $data['allowAddDelete'] = AdminUser::hasRight('texts_add_remove');
        break;

      case 'stats':
        if(!in_array($request->action, $CFG['SITE_LANGS'])) $request->action = DEFAULT_LANG;
        $data['stats'] = Stats::getStats();
        $data['settings'] = Settings::getSettings('general');
        $data['title'] = 'Statistics';
        $data['icon'] = 'link';
        $data['fullscreen'] = true;
        $data['modFile'] = 'includes/statistics.php';
        $data['allowAddDelete'] = AdminUser::hasRight('texts_add_remove');
        $data['error'] = isset($request->error) ? $request->error : '';
        break;

      case 'documentation':
        if(!in_array($request->action, $CFG['SITE_LANGS'])) $request->action = DEFAULT_LANG;
        $data['title'] = 'Documentation';
        $data['icon'] = 'link';
        $data['fullscreen'] = true;
        $data['modFile'] = 'includes/documentation.php';
        $data['allowAddDelete'] = AdminUser::hasRight('texts_add_remove');
        $data['error'] = isset($request->error) ? $request->error : '';
        break;

       case 'logs':
        if(!in_array($request->action, $CFG['SITE_LANGS'])) $request->action = DEFAULT_LANG;
        if(isset($request->params[1]) && $request->params[1] == 'more'){

          $data['log'] = DB::getLogById($request->params[2]);

          $data['modFile'] = 'includes/log.php';
          $data['fullscreen'] = true;
          $data['title'] = 'logs';
        }else{
          $data['title'] = 'logs';
          $data['request'] = $request;
          $data['logs'] = DB::getLogs();
          $data['icon'] = 'screen';
          $data['fullscreen'] = true;
          $data['modFile'] = 'includes/logs.php';
          $data['error'] = isset($request->error) ? $request->error : '';
        }
        break;

      case 'seo':
        if(!in_array($request->action, $CFG['SITE_LANGS'])) $request->action = DEFAULT_LANG;
        $sitemapFile = ROOT.DIR_FRONT.'/sitemap.xml';

        if(isset($_POST['action']) && $_POST['action'] == 'generate-sitemap') {
          try {
            Sitemap::generate();
          } catch(Exception $e) {
            $request->error = $e->getMessage();
          }
        } else if(isset($_POST['action']) && $_POST['action'] == 'enable-seo') {
          try {
            Sitemap::enableSEO();
            Settings::set('seo_enabled', true, 'seo');
          } catch(Exception $e) {
            $request->error = $e->getMessage();
          }
        } else if(isset($_POST['action']) && $_POST['action'] == 'disable-seo') {
          try {
            Sitemap::disableSEO();
            Settings::set('seo_enabled', false, 'seo');
          } catch(Exception $e) {
            $request->error = $e->getMessage();
          }
        } else if(isset($_POST['action']) && $_POST['action'] == 'update-settings') {
          if(isset($_POST['google-verification-code'])) {
            Settings::set('google-verification-code', $_POST['google-verification-code'], 'seo');
          }

          if(isset($_POST['google-analytics-id'])) {
            Settings::set('google-analytics-id', $_POST['google-analytics-id'], 'seo');
          }
        }

        $data['title'] = 'SEO';
        $data['request'] = $request;
        $data['seo_enabled'] = Settings::getSetting('seo_enabled', 'seo');
        $data['sitemap_updated'] = Settings::getSetting('sitemap_updated', 'seo');
        $data['sitemap_exists'] = file_exists($sitemapFile);
        $data['google_verification_code'] = Settings::getSetting('google-verification-code', 'seo');
        $data['google_analytics_id'] = Settings::getSetting('google-analytics-id', 'seo');
        $data['icon'] = 'search';
        $data['fullscreen'] = true;
        $data['modFile'] = 'includes/seo.php';
        $data['error'] = isset($request->error) ? $request->error : '';
        break;

        case 'trash':
          if(!in_array($request->action, $CFG['SITE_LANGS'])) $request->action = DEFAULT_LANG;

          if(isset($_POST['delete'])) { // Delete forever
            $id = (int) $_POST['delete'];
            DB::DeleteTrashItem($id);
          } else if(isset($_POST['restore'])){
            $id = (int) $_POST['restore'];
            Trash::restoreTrashItem($id);
          }

          if(isset($request->params[1]) && $request->params[1] == 'more'){
            $data['item'] = DB::getTrashItem($request->params[2]);
            $data['modFile'] = 'includes/deletedItem.php';
            $data['title'] = 'Deleted item';
            $data['error'] = '';

            if(!$data['item']) {
              $data['error'] = 'Item not found';
            }
          } else {
            $data['title'] = 'Trash';
            $data['modFile'] = 'includes/trash.php';
            $data['trash'] = DB::getTrash();
            $data['error'] = isset($request->error) ? $request->error : '';
          }

          $data['fullscreen'] = true;
          $data['icon'] = 'remove';
          $data['request'] = $request;

          break;

      case 'module':
        if(!AdminUser::hasModule($request->action)) {
          return (object) array(  'error' => 'You don\'t have permission to use this module'  );
        }

        $module = Admin_DB::getModuleById($request->action);
        if(!$module) {
          return (object) array(  'error' => 'Module not found'  );
        }

        $modFile = ROOT.'/'.DIR_BACK.'/modules/'.$module->namespace.'/main.php';
        if(!file_exists($modFile)) {
          return (object) array(  'error' => 'Module is not configured correctly'  );
        }

        $data['title'] = $module->name;
        $data['icon'] = 'link';
        $data['fullscreen'] = false;
        $data['modFile'] = $modFile;

        $data['action'] = $request->id;
        $data['id'] = $request->extra;

        define('MODULE_URL', ADMIN_URL.'module/'.$module->id.'/');
        define('POSTMODULE_URL', ADMIN_URL.'postmodule/'.$module->id.'/');
        define('UPLOAD_FOLDER', ROOT.'/'.DIR_FRONT.'/uploads/');
        break;

      case 'postmodule':
        if(!AdminUser::hasModule($request->action)) {
          return (object) array(  'error' => 'You don\'t have permission to use this module'  );
        }

        $module = Admin_DB::getModuleById($request->action);
        if(!$module) {
          return (object) array(  'error' => 'Module not found'  );
        }

        $modFile = ROOT.'/'.DIR_BACK.'/modules/'.$module->namespace.'/postmain.php';
        if(!file_exists($modFile)) {
          return (object) array(  'error' => 'Module is not configured correctly'  );
        }

        $data['modFile'] = $modFile;

        $data['action'] = $request->id;
        $data['id'] = $request->extra;

        define('MODULE_URL', ADMIN_URL.'module/'.$module->id.'/');
        define('POSTMODULE_URL', ADMIN_URL.'postmodule/'.$module->id.'/');
        define('UPLOAD_FOLDER', ROOT.'/'.DIR_FRONT.'/uploads/');

        return (object) array( "tpl" => "postmodule", "data" => (object) $data );
        break;
    }

    return (object) array( "tpl" => "main", "data" => (object) $data );
  }
}
