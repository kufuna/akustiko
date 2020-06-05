<?php
class Settings {
  public static function getSettings($namespace = 'general') {
    $data = json_decode(file_get_contents(ROOT.DIR_BACK.'/config.json'));

    return isset($data->$namespace) && $data->$namespace ? $data->$namespace : array();
  }

  public static function getSetting($key, $namespace = 'general') {
    $settings = self::getSettings($namespace);
    return isset($settings->$key) ? $settings->$key : null;
  }

  public static function set($key, $val, $namespace = 'general') {
    $settings = self::getSettings($namespace);
    $settings->$key = $val;
    return self::save($settings, $namespace);
  }

  private static function save($updatedData, $namespace = 'general') {
    try {
      $data = json_decode(file_get_contents(ROOT.DIR_BACK.'/config.json'));
      $data->$namespace = $updatedData;
      file_put_contents(ROOT.DIR_BACK.'/config.json', json_encode($data));
    } catch(Exception $e) {
      throw new Exception("Unable to save settings");
    }

    return true;
  }

  public static function updateSettings($data, $namespace = 'general') {
    $settings = self::getSettings($namespace);

    foreach($settings as $key => $val){
      if(isset($data->$key)) {
        $settings->$key = $data->$key;
      }

      if($key == 'DEBUG') {
        $settings->$key = (string) $data->$key == 'on' ? 1 : 0;
      }
    }

    return self::save($settings, $namespace);
  }
}
