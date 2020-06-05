<?php
class Lang {
  public static $lang;
  private static $defaultLang = DEFAULT_LANG;
  private static $langs = null;
  private static $texts = array(  );

  public static function init($url) {
    global $SITE_LANGS;
    global $CFG;
    self::$langs = $SITE_LANGS;
  
    if($url->lang !== null) {
      Lang::$lang = $url->lang;
      setcookie('lang', $url->lang, time() + 31536000, '/');
    } else if(!@$_COOKIE['lang'] || !in_array($_COOKIE['lang'], self::$langs)) {
    
      $IP = $_SERVER['REMOTE_ADDR'];
      if($IP != '127.0.0.1' && function_exists('geoip_country_code_by_name')) {
        $countryCode = strtolower(geoip_country_code_by_name($IP));
          if($countryCode == 'ge') $countryCode = 'ka';
          else if($countryCode == 'ru') $countryCode = 'ru';
          else $countryCode = 'en';

        if(in_array($countryCode, self::$langs)) self::$defaultLang = $countryCode;
        else self::$defaultLang = isset(self::$langs[0]) ? self::$langs[0] : DEFAULT_LANG;
      }
    
      setcookie('lang', self::$defaultLang, time() + 31536000, '/');
      Lang::$lang = self::$defaultLang;
    } else {
      Lang::$lang = $_COOKIE['lang'];
    }
    
    if(defined('MULTIPLE_DOMAINS')) {
      define('SITE_URL', $CFG['MULTI_DOMAINS'][Lang::$lang].Lang::$lang.'/');
      
      if(!$url->admin && 'http://'.$_SERVER['HTTP_HOST'].'/' !=  $CFG['MULTI_DOMAINS'][Lang::$lang]) {
        header('Location: '.SITE_URL);
        exit;
      }
    } else {
      define('SITE_URL', ROOT_URL.Lang::$lang.'/');
    }
    
    Admin_Lang::init($url);
  }

  public static function setLang($lang) {
    if(!in_array($lang, self::$langs)) {
      $lang = self::$defaultLang;
    }
    
    setcookie('lang', $lang, time() + 31536000, '/');
    Lang::$lang = $lang;
  }
  
  private function isLangVar($var) {
    if(!$var) return false;
    
    $chk = explode('_', $var);
    if(($chk[0] == 'geo' || $chk[0] == 'eng') && ($chk[1] > 0 && $chk[1] < 1000001)) return true;
    return false;
  }
  
  public static function get($key, $lang = false) {
    $lang = $lang ? $lang : self::$lang;
    
    self::loadLang($lang);
    
    if(!isset(self::$texts[$lang]->$key)) {
      Admin_Lang::updateLang(array( 'key' => $key, 'value' => '' ), Lang::$lang);
      return $key;
    }
    
    return self::$texts[$lang]->$key ?: $key;
  }
  
  private static function loadLang($lang) {
    if(!isset(self::$texts[$lang])) {
      $langFile = ROOT.'/'.DIR_BACK.'/engine/lang/'.$lang.'.json';
      
      if(file_exists($langFile) and $lang != 'langs') {
        try {
          self::$texts[$lang] = json_decode(file_get_contents($langFile));
        } catch(Exception $e) {
          self::$texts[$lang] = (object) array(  );
        }
      } else {
        self::$texts[$lang] = (object) array(  );
      }
    }
  }
}

function L($key) {
  return Lang::get($key);
}
