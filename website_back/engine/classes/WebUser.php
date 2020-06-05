<?php

/**
 * User
 * @author     Aleqsandre Shubitidze <a.shubitidze@gmail.com>
 */
//class WebUser
//{
//  private static $userData = null;
//
//  //class configs
//  protected static $conf = array(
//    "secretKey" => "===connect===",
//    "tableName" => "users",
//    "verificationCodeName" => "verification_code",
//    "statusName" => "status",
//    "verificationLink" => 'messages/verification/',
//    "resetPasswordLink" => 'reset/',
//    "resetPasswordMessageLink" => 'messages/action/3',
//    "emailName" => 'email',
//    "passwordName" => 'password',
//    "mailSenderName" => 'Connect CMS'
//  );
//
//  //error codes
//  private static $errorCodes = array(
//    1 => "მითითებული ელ-ფოსტა ან პირადი ნომერი უკვე რეგისტრირებულია ჩვენს ბაზაში.",
//    2 => "მომხმარებლის რეგისტრაცია წარუმატებლად დასრულდა.",
//    3 => "მომხმარებლის დადასტურების ბმულის გენერაცია წარუმატებლად დასრულდა.",
//    4 => "მომხმარებლის ელ-ფოსტაზე დადასტურების კოდის გაგზავნა წარუმატებლად დასრულდა.",
//    5 => "ვერიფიკაციის კოდი არასწორია.",
//    6 => "მომხმარებლის ვერიფიკაციის დადასტურება წარუმატებლად დასრულდა.",
//    7 => "მომხმარებლის ელ-ფოსტა ან პაროლი არასწორია.",
//    8 => "მომხმარებელი არ არის ვერიფიცირებული.",
//    9 => "მომხმარებლის ელ ფოსტა არასწორია.",
//    10 => "პაროლის აღდგენა წარუმატებლად დასრულდა.",
//    11 => "შესაბამისი მომხმარებელი ვერ მოიძებნა.",
//    12 => "მომხმარებლის პირადი მონაცემების რედაქტირება წარუმატებლად დასრულდა.",
//  );
//
//  /**
//   * user init
//   * @param conf => config array to be merged
//   * @return user => registered user info or false
//   */
//  public static function logout()
//  {
//    unset($_SESSION[USER_SESSION_NAMESPACE]);
//  }
//
//  public static function init($conf = array())
//  {
//    self::$conf = array_merge(self::$conf, $conf);
//
//    if (isset($_SESSION[USER_SESSION_NAMESPACE])) {
//
//      $fields = array(
//        "id" => @$_SESSION[USER_SESSION_NAMESPACE]["id"],
//        self::$conf["passwordName"] => @$_SESSION[USER_SESSION_NAMESPACE][self::$conf["passwordName"]],
//      );
//
//      self::$userData = DB::get(array(
//        'table' => self::$conf["tableName"],
//        'query' => $fields,
//        'multiLang' => false,
//        'single' => true
//      ));
//    }
//  }
//
//  /**
//   * Checks user
//   * @return user data object => user is authorized, null => user isn't authorized
//   */
//  public static function getUser()
//  {
//    return self::$userData;
//  }
//
//  /**
//   * Reload user after profile update
//   * @return user data object => user is authorized, null => user isn't authorized
//   */
//  public static function reloadUser()
//  {
//    if(self::$userData) {
//      self::$userData = DB::get(array(
//        'table' => self::$conf["tableName"],
//        'query' => array('id' => self::$userData->id),
//        'multiLang' => false,
//        'single' => true
//      ));
//
//      $passwordField = self::$conf['passwordName'];
//      $_SESSION[USER_SESSION_NAMESPACE][$passwordField] = self::$userData->$passwordField;
//    }
//
//    return self::$userData;
//  }
//
//  /**
//   * user registration
//   * EXAMPLE:
//   * <code>
//   * [Method Call Sample]
//   * $regFields=array( 'fistName'=>'fistName', 'surName'=>'surName', 'email'=>'mail@example.com');
//   * $uniqueFields=array('email'=>'mail@example.com');
//   * $user=WebUser::register($regFields,$uniqueFields);
//   * </code>
//   *
//   * @param regFields => assoc array sample[array( 'fistName'=>'fistName', 'surName'=>'surName', 'email'=>'mail@example.com');]
//   * @param uniqueFields => not required parameter, unique fields in base for example email, idNumber...
//   * @return user => user data
//   */
//  public static function register($regFields, $uniqueFields = false, $verificationMail = false)
//  {
//    //checkUser
//    if ($uniqueFields && self::alreadyExists($uniqueFields)) {
//      throw new Exception(self::$errorCodes[1], 1);
//    }
//
//    //register user
//    try {
//      $user = DB::insert($regFields, self::$conf["tableName"]);
//
//      //send verification link
//      $verificationCode = md5(md5($user));
//      $link = SITE_URL . self::$conf["verificationLink"] . $verificationCode;
//
//      try {
//        //update verification code
//        DB::update(array(self::$conf["verificationCodeName"] => $verificationCode), self::$conf["tableName"], $user);
//      } catch (Exception $e) {
//        throw new Exception(self::$errorCodes[3], 3);
//      }
//
//      if ($verificationMail) {
//        Mail::send(array(
//          'from' => 'noreply@' . DOMAIN,
//          'fromName' => self::$conf['mailSenderName'],
//          'to' => $verificationMail,
//          'subject' => L('_register_mail_subject_'),
//          'message' => str_replace('{link}', $link, L('_register_mail_text_ {link}'))
//        ));
//      }
//    } catch (Exception $e) {
//      $code = $e->getCode() == 3 ? 3 : 2;
//      throw new Exception(self::$errorCodes[$code], $code);
//    }
//
//    return $user;
//  }
//
//  /**
//   * user update
//   * EXAMPLE:
//   * <code>
//   * [Method Call Sample]
//   * $regFields=array( 'fistName'=>'fistName', 'surName'=>'surName');
//   * $user=WebUser::updateUser($regFields);
//   * </code>
//   *
//   * @param regFields => assoc array sample[array( 'fistName'=>'fistName', 'surName'=>'surName');]
//   * @return user => user data
//   */
//  public static function updateUser($regFields, $userId)
//  {
//    //update user
//    try {
//      DB::update($regFields, self::$conf["tableName"], $userId);
//
//      if (@$regFields[self::$conf['passwordName']]) {
//        $_SESSION[USER_SESSION_NAMESPACE][self::$conf["passwordName"]] = $regFields[self::$conf['passwordName']];
//      }
//
//      $user = self::getUser();
//      self::$userData = DB::get(array(
//        'table' => self::$conf["tableName"],
//        'query' => array('id' => $user->id),
//        'multiLang' => false,
//        'single' => true
//      ));
//    } catch (Exception $e) {
//      throw new Exception(self::$errorCodes[12], 12);
//    }
//
//    return self::$userData;
//  }
//
//  /**
//   * authorization
//   *
//   * @param fields => login fields, for example email and password
//   * @return user data
//   */
//  public static function authorization($fields)
//  {
//    $data = array();
//    $user = DB::get(array(
//      'table' => self::$conf["tableName"],
//      'query' => $fields,
//      'multiLang' => false,
//      'single' => true
//    ));
//
//    if (!$user) throw new Exception(self::$errorCodes[7], 7);
//
//    $sessionData = array(
//      'id' => $user->id,
//      self::$conf["passwordName"] => $user->{self::$conf["passwordName"]}
//    );
//
//    $statusName = self::$conf["statusName"];
//    if (!$user->$statusName) throw new Exception(self::$errorCodes[8], 8);
//
//    // set user session
//    $_SESSION[USER_SESSION_NAMESPACE] = $sessionData;
//
//    return $user;
//  }
//
//  /**
//   * reset password
//   *
//   * @param email => user email
//   * @return user data
//   */
//  public static function sendPasswordResetLink($email)
//  {
//    $user = DB::get(array(
//      'table' => self::$conf["tableName"],
//      'query' => array('email' => $email),
//      'multiLang' => false,
//      'single' => true
//    ));
//
//    if (!$user) throw new Exception(self::$errorCodes[9], 9);
//
//    $statusName = self::$conf["statusName"];
//    if (!$user->$statusName) throw new Exception(self::$errorCodes[8], 8);
//
//    //send reset code
//    $verificationCode = md5(uniqid());
//    DB::update(array('verification_code' => $verificationCode), 'users', $user->id);
//
//    $link = SITE_URL . self::$conf["resetPasswordLink"] . $verificationCode;
//
//    Mail::send(array(
//      'from' => 'noreply@' . DOMAIN,
//      'fromName' => self::$conf['mailSenderName'],
//      'to' => $email,
//      'subject' => L('_reset_password_subject_'),
//      'message' => L('_reset_password_text_') . $link
//    ));
//
//    return $user;
//  }
//
//  /**
//   * change password
//   *
//   * @param email => user email
//   * @return user data
//   */
//  public static function changePassword($fields, $verificationCode)
//  {
//    $user = DB::get(array(
//      'table' => self::$conf["tableName"],
//      'query' => array(self::$conf["verificationCodeName"] => $verificationCode),
//      'multiLang' => false,
//      'single' => true
//    ));
//
//    if (!$user) throw new Exception(self::$errorCodes[11], 11);
//
//    try {
//      DB::update($fields, self::$conf["tableName"], $user->id);
//    } catch (Exception $e) {
//      throw new Exception(self::$errorCodes[10], 10);
//    }
//
//    return $user;
//  }
//
//  /**
//   * verifyUser
//   *
//   * @param verificationCode => user verification code
//   * @return user data
//   */
//  public static function verifyUser($verificationCode)
//  {
//    $user = DB::get(array(
//      'table' => self::$conf["tableName"],
//      'query' => array(self::$conf["verificationCodeName"] => $verificationCode),
//      'multiLang' => false,
//      'single' => true
//    ));
//
//    if (!$user) throw new Exception(self::$errorCodes[5], 5);
//
//    try {
//      DB::update(array(self::$conf["statusName"] => 1), self::$conf["tableName"], $user->id);
//    } catch (Exception $e) {
//      throw new Exception(self::$errorCodes[6], 6);
//    }
//
//    return $user;
//  }
//
//  /**
//   * alreadyExists
//   *
//   * @param uniqueFields => unique fields in base for example email, idNumber...
//   * @return true => if user exists, false = > if user doesn't exists
//   */
//  public static function alreadyExists($uniqueFields)
//  {
//    if (DB::get(array(
//      'table' => self::$conf["tableName"],
//      'query' => $uniqueFields,
//      'multiLang' => false,
//      'order' => 'id DESC'
//    ))
//    ) return true;
//
//    return false;
//  }
//
//  /**
//   * generatePassword
//   *
//   * @param password => user original password
//   * @return hashed password
//   */
//  public static function generatePassword($password)
//  {
//    return md5(md5($password));
//  }
//}
