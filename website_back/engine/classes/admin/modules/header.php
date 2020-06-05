<?php
class Admin_PageHeader {
  private static $builtInModules = array(  'users'  );

  public static function getModel($request) {
    global $CFG;

    $user = AdminUser::getCurrentUser();
  
    $canonical = URL::getAdminCanonicalUrl($request->data);
    $rootUrl = substr(ADMIN_URL, 0, strlen(ADMIN_URL) - 1); // Remove last slash
    
    $ms = array(  );
    $pattern = '';
    foreach($CFG['SITE_LANGS'] as $lang) {
      $pattern .= '/'.$lang.'/|';
    }
    
    preg_match('#'.$rootUrl.'('.$pattern.'/)(.*?)$#', $canonical, $ms);
    
    $langs = array(  );
    
    foreach($CFG['SITE_LANGS'] as $lang) {
      $langs[$lang] = ADMIN_ROOT.$lang.'/'.(isset($ms[2]) ? $ms[2] : '');
    }
    
    $headerData = array( 
      'langs' => (object) $langs
    );

    $modules = Admin_DB::getModules($user->id, true);
    
    if(in_array($request->module, self::$builtInModules)) {

    } else if($request->module == 'home') {

      $headerData = array(
        'title' => L('Exist modules'),
        'subtitle' => L('In total:').' '.count($modules).' '.L('module'),
        'modules' => $modules
       );
      $headerData['activeMod'] = 0;

    } else if($request->module == 'texts') {

      $headerData = array(
        'title' => 'ტექსტები',
        'subtitle' => L('In total:').count($CFG['SITE_LANGS']).' ენა',
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG
      );
      $headerData['activeMod'] = 0;

    } else if($request->module == 'module') {

      $headerData = array(
        'title' => L('Exist modules'),
        'subtitle' => L('In total:').' '.count($modules).L('module')
       );
      $headerData['activeMod'] = (int) $request->action;

    } else if($request->module == 'super_user') {

      $headerData = array(
        'title' => NULL,
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );
      $headerData['activeMod'] = 0;

    } else if($request->module == 'documentation') {
      $headerData = array(
        'title' => 'დოკუმენტაცია',
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );
      $headerData['activeMod'] = 0;

    } else if($request->module == 'logs') {
      $headerData = array(
        'title' => NULL,
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );
      $headerData['activeMod'] = 0;

    } else if($request->module == 'trash') {
      $headerData = array(
        'title' => NULL,
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );
      $headerData['activeMod'] = 0;

    } else if($request->module == 'seo') {
      $headerData = array(
        'title' => NULL,
        'activeLang' => in_array($request->action, $CFG['SITE_LANGS']) ? $request->action : DEFAULT_LANG,
      );
      $headerData['activeMod'] = 0;

    }

    $headerData['langs'] = $langs;
    $headerData['modules'] = $modules;
    $headerData['activeModule'] = $request->module;
    $headerData['rights'] = explode(':', $user->rights);

    return (object) array( "tpl" => "header", "data" => (object) $headerData );
  }
}
