<?php
class AdminUser {
  private static $rights = null;

  public static function getModel() {
    return array(
      'username' => '',
      'email' => '',
      'pass' => ''
     );
  }

  public static function validate($data, $optionals = false) {
    if(strlen(@$data['username']) < 2) {
      return L('Username is not valid');
    }

    if($optionals && @$data['email'] || !$optionals) {
      if(!preg_match("#[a-zA-Z0-9_.-]+[a-zA-Z0-9-]+[a-zA-Z0-9-.]+$#", $data['email'])) {
        return L('Please enter valid email');
      }
    }

    if($optionals && @$data['pass'] || !$optionals) {
      if(strlen(@$data['pass']) < 6) {
        return L('Password must contain at least 6 characters');
      }
    }

    if(Admin_DB::userExists(@$data['email'], @$data['userid'])) {
      return L('Email address is already used');
    }

    return true;
  }

  public static function register($data) {
    $username = preg_replace('/[^a-zA-Z_]/', "", @$data['username']);
    $email = preg_replace('/[^a-zA-Z\.\@\_0-9]/', "", @$data['email']);

    $user = self::getModel();
    $user['username'] = $username;
    $user['email'] = $email;
    $user['pass'] = @$data['pass'];
    $valid = self::validate($user);

    if($valid === true){
      $user['pass'] = md5(md5(@$data['pass']));
      $result = Admin_DB::registerUser($user);
      if($result['success'] === true) {
        if(!in_array('modules', @$data['roles'])) $data['modules'] = array(  ); // Clear all modules
        Admin_DB::updateModulesByUser($result['insertId'], @$data['modules']);
        Admin_DB::updateUserRights($result['insertId'], @$data['roles']);
        return true;
      } else {
        return $result['error'];
      }
    } else {
      return $valid;
    }
  }

  public static function updateProfile($data) {
    $user = self::getCurrentUser();
    $newInfo = array(  );
    $newinfo['username'] = preg_replace('/[^a-zA-Z_]/', "", @$data['username']);
    $newinfo['email'] = preg_replace('/[^a-zA-Z\.\@\_0-9]/', "", @$data['email']);
    $newinfo['pass'] = @$data['pass'];
    $newinfo['userid'] = @$data['userid'];

    $valid = self::validate($newinfo, true);

    if($valid !== true) return $valid;

    $currentInfo = Admin_DB::getUserById(@$data['userid']);

    if(!$newinfo['email']) unset($newinfo['email']);
    if(!$newinfo['pass']) {
      unset($newinfo['pass']);
    } else {
      $newinfo['pass'] = md5(md5($newinfo['pass']));
    }
    unset($newinfo['userid']);

    $result = Admin_DB::updateUserProfile($newinfo, @$data['userid']);

    if($result == true) {
      if(!in_array('modules', @$data['roles'])) $data['modules'] = array(  ); // Clear all modules
      Admin_DB::updateModulesByUser(@$data['userid'], @$data['modules']);
      Admin_DB::updateUserRights(@$data['userid'], @$data['roles']);
      self::updateSession();
    }

    return $result;
  }

  public static function login($data) {
    $username = preg_replace('/[^a-zA-Z\.\@\_0-9]/', "", @$data['username']);
    $pass = md5(md5(@$data['pass']));

    $user = Admin_DB::getUserByUsername($username);

    if($user && $user->pass === $pass) {
      $clientIp = $_SERVER['REMOTE_ADDR'];
      $accessIps = explode(',', $user->ips);

        $pregMatchIp = false;

        foreach ($accessIps as $key => $value) {
          if (preg_match('/^('. $value .')/', $_SERVER['REMOTE_ADDR'])) {
            $pregMatchIp = true;
          }
        }

//       if (!defined('ADMIN_AUTH_WITHOUT_IP_CHECK') && !$pregMatchIp) {
//          $email = $user->email;
//          $random = AdminUser::generateRandomString(10, $email);
//
//          $mailData = array(
//              'from' => MAIL_USER,
//              'to' => $email,
//              'subject' => L('Activation of IP address'),
//              'message' => L('Hi, please Click on the link to authorize your IP address').' <a href="'.ROOT_URL.ADMIN_PATH.'/addip/'.md5($random).'">'.ROOT_URL.ADMIN_PATH.'/addip/'.md5($random).'</a>',
//              'fromName' => 'CONNECT CMS',
//            );
//
//          Mail::send($mailData);
//
//          throw new Exception(L('In CMS due to the first time authorizing through the actual IP address, you should pass an Email authorization and verify it with your CMS registered Email address. Your IP address activation link will be sent to your email. Please visit your Email to activate IP Address'));
//      } else {
          AdminUser::onLogin($user);
          unset($user->pass);
          return $user;
//      }
    } else {
      throw new Exception("Incorrect username or password");
    }
    return false;
  }

  public static function hasRight($right) {
    $user = self::getCurrentUser();

    if(!$user) return false;

    if(self::$rights === null) {
      self::$rights = explode(':', $user->rights);
    }

    return in_array($right, self::$rights);
  }

  public static function hasModule($moduleId) {
    $moduleId = (int) $moduleId;
    $user = self::getCurrentUser();

    if(!$user) return false;

    return Admin_DB::hasModule($user->id, $moduleId);;
  }

  public static function onLogin($user) {
    self::destroy();
    $_SESSION[ADMIN_SESSION_NAMESPACE] = $user;
    DB_Base::writeAuthLog($user);

  }

  public static function updateSession() {
    $_SESSION[ADMIN_SESSION_NAMESPACE] = Admin_DB::getUserById($_SESSION[ADMIN_SESSION_NAMESPACE]->id);
  }

  public static function getCurrentUser() {
    return isset($_SESSION[ADMIN_SESSION_NAMESPACE]) ? $_SESSION[ADMIN_SESSION_NAMESPACE] : false;
  }

  public static function destroy() {
    unset($_SESSION[ADMIN_SESSION_NAMESPACE]);
  }

  public static function generateRandomString($length = 10, $email) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    Admin_DB::updateUserRandom($email, $randomString);

    return $randomString;
  }

  public static function recaptcha($data){
    if(!isset($data['g-recaptcha-response']) || !$data['g-recaptcha-response']) {
      // return false;
      throw new Exception("Incorrect security code");
    } else {
        if(ini_get('allow_url_fopen')){

          $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?'
                        .'secret='.CAPTCHA_PRIVATE_KEY
                        .'&response='.$data['g-recaptcha-response']
                        .'&remoteip='.$_SERVER['REMOTE_ADDR']);
        } else {
          $captchaUrl = 'https://www.google.com/recaptcha/api/siteverify?' .'secret='.CAPTCHA_PRIVATE_KEY .'&response='.$data['g-recaptcha-response'] .'&remoteip='.$_SERVER['REMOTE_ADDR']; 
          $curl = curl_init(); 
          curl_setopt($curl, CURLOPT_URL, $captchaUrl); 
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
          curl_setopt($curl, CURLOPT_TIMEOUT, 120); 
          $response = curl_exec($curl); 
          curl_close($curl); 
        }

        $response = json_decode($response);

        if(!$response || !isset($response->success) || !$response->success) {
          // return false;
          throw new Exception("Incorrect security code");
        } /*else {
          return true;
        }  */
    }
  }
}
