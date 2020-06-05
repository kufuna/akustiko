<?php
class Admin_PageLeft {
  private static $builtInModules = array(  'users'  );

  public static function getModel($request) {
    global $CFG;

    $user = AdminUser::getCurrentUser();

    if(in_array($request->module, self::$builtInModules)) {
      $data = array(
        'submenu' => false
       );
    } else if($request->module == 'home') {
      $modules = Admin_DB::getModules($user->id, true);

      $data = array(
        'submenu' => true,
        'title' => L('Exist modules'),
        'subtitle' => L('In total:').' '.count($modules).' '.L('module'),
        'modules' => $modules
       );

      $data['activeMod'] = 0;
    } else if($request->module == 'texts') {
      $data = array(
        'submenu' => false,
        'title' => 'ტექსტები',
        'subtitle' => L('In total:').count($CFG['SITE_LANGS']).' ენა',
        'langs' => $CFG['LANG_NAMES'],
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG
      );

      $data['activeMod'] = 0;
    } else if($request->module == 'module') {
      $modules = Admin_DB::getModules($user->id, true);

      $data = array(
        'submenu' => true,
        'title' => L('Exist modules'),
        'subtitle' => L('In total:').' '.count($modules).L('module'),
        'modules' => $modules
       );

      $data['activeMod'] = (int) $request->action;
    } else if($request->module == 'super_user') {
      $modules = Admin_DB::getModules($user->id, true);
      $data = array(
        'submenu' => false,
        'title' => NULL,
        'modules' => $modules,
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );

      $data['activeMod'] = 0;
    } else if($request->module == 'documentation') {
      $modules = Admin_DB::getModules($user->id, true);
      $data = array(
        'submenu' => false,
        'title' => 'დოკუმენტაცია',
        'modules' => $modules,
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );

      $data['activeMod'] = 0;
    } else if($request->module == 'logs') {
      $modules = Admin_DB::getModules($user->id, true);
      $data = array(
        'submenu' => false,
        'title' => NULL,
        'modules' => $modules,
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );

      $data['activeMod'] = 0;
    } else if($request->module == 'trash') {
      $modules = Admin_DB::getModules($user->id, true);
      $data = array(
        'submenu' => false,
        'title' => NULL,
        'modules' => $modules,
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );

      $data['activeMod'] = 0;
    } else if($request->module == 'seo') {
      $modules = Admin_DB::getModules($user->id, true);
      $data = array(
        'submenu' => false,
        'title' => NULL,
        'modules' => $modules,
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );

      $data['activeMod'] = 0;
    }

    $data['activeModule'] = $request->module;
    $data['rights'] = explode(':', $user->rights);

    return (object) array( "tpl" => "sidebar", "data" => (object) $data );
  }
}
