<?php
class URL {
  public static $vars = array();
  private static $params = array();

  public static function parse() {
    global $SITE_LANGS;

      $url = explode('/', @$_GET['url']);
      URL::$vars = (object) URL::$vars;

      if(isset($_GET['url']) && $_GET['url'][0] == '/') array_shift($url);

    if(@$url[0] == ADMIN_PATH) {
      URL::$vars->admin = true;
      array_shift($url);
    } else {
      URL::$vars->admin = false;
    }

    if(in_array(@$url[0], $SITE_LANGS)) {
      URL::$vars->lang = array_shift($url);
    } else {
      URL::$vars->lang = null;
    }

    self::$params = $url;
    URL::$vars->module = isset($url[0]) && $url[0] ? $url[0] : 'home';
    URL::$vars->action = urldecode(isset($url[1]) ? $url[1] : '');
    URL::$vars->id = isset($url[2]) ? $url[2] : '';
    URL::$vars->extra = isset($url[3]) ? $url[3] : '';
    URL::$vars->filter1 = isset($url[4]) ? $url[4] : '';
    URL::$vars->filter2 = isset($url[5]) ? $url[5] : '';

    URL::$vars->params = $url;

    $urlComponents = parse_url($_SERVER['REQUEST_URI']);
    if(isset($urlComponents['query'])) {
      $getParams = array();
      parse_str($urlComponents['query'], $getParams);
      foreach($getParams as $key => $val) {
        $_GET[$key] = $val;
        $_REQUEST[$key] = $val;
      }
    }
  }

  public static function isCanonical($req, $canonical) {
    $current = SITE_URL.self::$vars->module;
    if(self::$vars->action) $current .= '/'.urlencode(self::$vars->action);
    if(self::$vars->id) $current .= '/'.self::$vars->id;
    if(self::$vars->extra) $current .= '/'.self::$vars->extra;
    $current .= '/';

    if($canonical != $current) {
      return $canonical;
    }

    return true;
  }

  public static function equalsCurrentUrl($link, $checkLevel = 4) {
    $current = !URL::$vars->lang ? ROOT_URL : substr(SITE_URL, 0, strlen(SITE_URL) - 1);
    $checkLevel = max($checkLevel, 1);

    $explainedLink = str_replace(SITE_URL, '', $link);
    $explainedLink = explode('/', $explainedLink);
    $correctedLink = array();

    for($l = 0; $l < $checkLevel; $l++) {
      if(isset(self::$params[$l]) && self::$params[$l]) {
        $current .= '/'.urlencode(self::$params[$l]);
      }

      if(isset($explainedLink[$l]) && $explainedLink[$l]) {
        $correctedLink[] = $explainedLink[$l];
      }
    }

    $lastSlash = count($correctedLink) ? '/' : '';
    $correctedLink = SITE_URL.implode('/', $correctedLink).$lastSlash;

    $current .= (count(self::$params) > 0 ? '/' : '');

    //echo $correctedLink."\n".$current."\n\n\n";

    if($correctedLink != $current) return false;
    return true;
  }

  public static function parseLink($link) {
    return str_replace('{SITE_URL}', SITE_URL, $link);
  }

  public static function getAdminCanonicalUrl($data) {
    switch(self::$vars->module) {
      case 'home':
        return ADMIN_URL.'home/';
        break;
      case 'login':
        return ADMIN_URL.'login/';
        break;
      case 'texts':
        return ADMIN_URL.'texts/';
        break;
      case 'users':
        return ADMIN_URL.'users/';
        break;
      case 'module':
        $url = ADMIN_URL.'module/';
        if(URL::$vars->action) $url .= URL::$vars->action.'/';
        if(URL::$vars->id) $url .= URL::$vars->id.'/';
        if(URL::$vars->extra) $url .= URL::$vars->extra.'/';
        return $url;
        break;
      default:
        return SITE_URL.'404/';
    }
  }

  public static function escapeUrl($s, $onlySymbos = false) {
    $s = mb_strtolower($s,'utf-8');

    if($onlySymbos) {
      $notAllowedChars = array(  '&', '?', '/' );
	    return str_replace($notAllowedChars, '', $s);
    }

	  $geo = array('ა','ბ','გ','დ','ე','ვ','ზ','თ','ი','კ','ლ','მ','ნ','ო','პ','ჟ','რ','ს','ტ','უ',
      'ფ','ქ','ღ','ყ','შ','ჩ','ც','ძ','წ','ჭ','ხ','ჯ','ჰ');
	  $geo2eng = array('a','b','g','d','e','v','z','t','i','k','l','m','n','o','p','j','r','s','t',
      'u','f','q','g','y','sh','ch','c','dz','w','ch','x','j','h');

	  $rus = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о',
      'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');

	  $rus2 = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О',
      'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я');
	  $rus2eng = array('a','b','v','g','d','e','yo','j','z','i','i','k','l','m','n','o','p','r','s',
      't','u','f','x','c','ch','sh','shch','','i','','e','yu', 'ya');

    $s = str_replace($geo, $geo2eng, $s);
    $s = str_replace($rus, $rus2eng, $s);
    $s = str_replace($rus2, $rus2eng, $s);

	  $notAllowedChars = array(  '&', '?', '/' );
	  $s = str_replace($notAllowedChars, '', $s);

	  $s = str_replace(' ', '-', $s);

    $s = preg_replace('#[^a-zA-Z0-9\-]#', '', $s);

    return urlencode($s);
  }
}
