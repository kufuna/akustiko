<?php
class Admin_Lang {
  public static $lang;
  private static $defaultLang = DEFAULT_LANG;
  private static $langs = null;
  private static $texts = array(  );

  public static function init($url) {
    global $CFG;
    self::$langs = $CFG['SITE_LANGS'];

    if($url->lang !== null) {
      Lang::$lang = $url->lang;
    } else if(!@$_COOKIE['lang'] || !in_array($_COOKIE['lang'], self::$langs)) {
      setcookie('lang', self::$defaultLang, time() + 31536000, '/');
      Lang::$lang = self::$defaultLang;
    } else {
      Lang::$lang = $_COOKIE['lang'];
    }

    define('ADMIN_URL', ROOT_URL.ADMIN_PATH.'/'.Lang::$lang.'/');
    define('ADMIN_ROOT', ROOT_URL.ADMIN_PATH.'/');
  }

  public static function setLang($lang) {
    if(!in_array($lang, self::$langs)) {
      $lang = self::$defaultLang;
    }

    setcookie('lang', $lang, time() + 31536000, '/');
    Lang::$lang = $lang;
  }

  public static function getAllLangs() {
    return json_decode(file_get_contents(ROOT.'/'.DIR_BACK.'/engine/lang/langs.json'));
  }

  public static function getLangByKey($key) {
    if(in_array($key, self::$langs)) {
      $langFile = ROOT.'/'.DIR_BACK.'/engine/lang/'.$key.'.json';

      if(!file_exists($langFile)) {
        file_put_contents($langFile, '{}');
      }

      return json_decode(file_get_contents($langFile));
    } else {
      return (object) array(  );
    }
  }

  public static function get($key, $lang = false) {
    $lang = $lang ? $lang : self::$lang;

    self::loadLang($lang);

    if(isset(self::$texts[$lang]->$key)) return self::$texts[$lang]->$key;
    else return $key;
  }

  public static function updateLang($data, $lang = false) {
    $lang = $lang ? $lang : self::$lang;
    if(!in_array($lang, self::$langs)) {
      return false;
    }

    self::loadLang($lang);

    if(isset($data['key']) && isset($data['value']) && $data['key']) {
      if(empty(self::$texts[$lang]->{$data['key']})) {
        if($data['value'] && !AdminUser::hasRight('texts_add_remove')) {
          return false;
        }

        self::addToAllLangs($data['key']);
      }

      self::$texts[$lang]->{$data['key']} = $data['value'];
      self::saveChanges($lang);
    }

    return true;
  }

  public static function removeLang($key) {
    $langsFile = ROOT.'/'.DIR_BACK.'/engine/lang/langs.json';
    $allLangs = json_decode(file_get_contents($langsFile));

    if(isset($allLangs->$key)) {
      unset($allLangs->$key);
      file_put_contents($langsFile, json_encode($allLangs));

      foreach(self::$langs as $lng) {
        self::loadLang($lng);
        if(isset(self::$texts[$lng]->$key)) {
          unset(self::$texts[$lng]->$key);
          self::saveChanges($lng);
        }
      }
    }
  }

  private static function saveChanges($lang) {
    $langFile = ROOT.'/'.DIR_BACK.'/engine/lang/'.$lang.'.json';

    file_put_contents($langFile, json_encode(self::$texts[$lang]));
  }

  private static function addToAllLangs($key) {
    if(!$key) return;

    $langFile = ROOT.'/'.DIR_BACK.'/engine/lang/langs.json';

    $allLangs = self::getAllLangs();
    $allLangs->$key = 1;
    file_put_contents($langFile, json_encode($allLangs));
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
