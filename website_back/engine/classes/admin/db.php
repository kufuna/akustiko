<?php
class Admin_DB {
  private static $pdo = null;

  public static function connect() {
    try {
      self::$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    } catch(PDOException $e) {
      return false;
    }

    self::$pdo->query("SET NAMES 'utf8'");
    self::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    return true;
  }

  public static function getConnection() {
    return self::$pdo;
  }

  public static function getUserById($id) {
    $id = (int) $id;

    $sql = "SELECT * FROM cn_admin_users WHERE id = $id";

    return self::$pdo->query($sql)->fetchObject();
  }

  public static function getUserByUsername($username) {
    $sql = "SELECT * FROM cn_admin_users WHERE username = '$username'";
    
    return self::$pdo->query($sql)->fetchObject();
  }

  public static function userExists($email, $excludeId = 0) {
    $excludeId = (int) $excludeId;

    $sql = "SELECT count(*) FROM cn_admin_users WHERE email = '$email' AND id != $excludeId";

    return self::$pdo->query($sql)->fetchColumn() > 0; // if exists returns true
  }

  public static function registerUser($data) {
    $keys = '';
    $vals = '';
    $valsArr = array(  );

    foreach($data as $key => $val) {
      $keys .= $key.',';
      $vals .= "?,";
      $valsArr[] = $val;
    }

    $keys = substr($keys, 0, strlen($keys) - 1); //remove last comma
    $vals = substr($vals, 0, strlen($vals) - 1);

    $sql = "INSERT INTO cn_admin_users ($keys) VALUES ($vals)";

    try {
      $st = self::$pdo->prepare($sql);
      $st->execute($valsArr);
      $insertId = self::$pdo->lastInsertId();
    } catch(PDOException $e) {
      return array(  'error' => (string) $e->getMessage()  );
    }

    return array(  'success' => true, 'insertId' => $insertId  );
  }

  public static function updateUserProfile($data, $userId) {
    $sql = "UPDATE cn_admin_users SET ";
    $vals = array(  );
    $userId = (int) $userId;

    foreach($data as $key => $val) {
      $sql .= $key.' = ?,';
      $vals[] = $val;
    }
    $sql = substr($sql, 0, strlen($sql) - 1); // remove last comma

    $sql .= " WHERE id = ?";
    $vals[] = $userId;

    try {
      $st = self::$pdo->prepare($sql);
      $st->execute($vals);
    } catch(PDOException $e) {
      return (string) $e->getMessage();
    }

    return true;
  }

  public static function updateModulesByUser($userid, $modules) {
    $userid = (int) $userid;
    $modules = (array) $modules;

    $sql = "DELETE FROM cn_admin_rights WHERE userid = $userid";
    self::$pdo->query($sql);

    $sql = "INSERT INTO cn_admin_rights (userid, moduleid) VALUES ";
    foreach($modules as $mod) {
      $sql .= "($userid, $mod),";
    }
    $sql = substr($sql, 0, strlen($sql) - 1); // remove last comma

    self::$pdo->query($sql);
  }

  public static function updateUserRights($userid, $rights) {
    $userid = (int) $userid;
    $rights = (array) $rights;
    $rights = implode(':', $rights);

    $sql = "UPDATE cn_admin_users SET rights = '$rights' WHERE id = $userid";
    self::$pdo->query($sql);
  }

  public static function getModuleById($id) {
    $id = (int) $id;
    $sql = "SELECT * FROM cn_admin_modules WHERE id = $id";
    return self::$pdo->query($sql)->fetchObject();
  }

  public static function getModules($userId = false, $ordered = false) {
    $data = array(  );

    if($userId) {
      $sql = 'SELECT r.moduleid, m.* FROM cn_admin_rights r
            LEFT JOIN cn_admin_modules m ON m.id = r.moduleid WHERE r.userid = '.$userId
            .'  ORDER BY m.parent, m.ord ASC';
    } else {
      $sql = 'SELECT * FROM cn_admin_modules ORDER BY parent, ord';
    }

    $st = self::$pdo->query($sql);
    while($row = $st->fetchObject()) {
      $data[] = $row;
    }
    
    $orderedData = array();
    if($ordered) {
      foreach($data as $item) {
        if($item->parent == 0) {
          $orderedData[$item->id] = $item;
        } else {
          //var_dump($orderedData[$item->parent]);
          $orderedData[$item->parent]->children[] = $item;
        }
      }
      //print_r($orderedData);exit;
      return $orderedData;
    }

    return $data;
  }

  public static function hasModule($userId, $moduleId) {
    $sql = "SELECT id FROM cn_admin_rights WHERE userid = $userId AND moduleid = $moduleId";
    return self::$pdo->query($sql)->fetchObject();
  }

  public static function getModulesAndRights($userId = false) {
    $userId = (int) $userId;
    $data = array(  );

    $sql = 'SELECT r.moduleid, m.* FROM cn_admin_modules m
            LEFT JOIN cn_admin_rights r ON m.id = r.moduleid AND r.userid = '.$userId
            .' ORDER BY m.ord ASC';

    $st = self::$pdo->query($sql);
    while($row = $st->fetchObject()) {
      $data[] = $row;
    }

    return $data;
  }

  public static function getAllUsers() {
    $data = array(  );

    $sql = 'SELECT * FROM cn_admin_users';
    $st = self::$pdo->query($sql);
    while($row = $st->fetchObject()) $data[] = $row;

    return $data;
  }

  public static function removeUser($userId) {
    $userId = (int) $userId;

    $sql = "DELETE FROM cn_admin_users WHERE id = $userId";
    self::$pdo->query($sql);
    return true;
  }
  
  public static function updateUserRandom($email, $random) {

    $sql = "UPDATE cn_admin_users SET random = :random WHERE email = :email";
    $st = self::$pdo->prepare($sql);

    $st->execute(array(
      'random' => $random,
      'email' => $email
    ));    
  } 

  public static function updateUserIps($random, $ip) {

    $sql = "UPDATE cn_admin_users SET ips = CASE WHEN ips IS NULL OR ips = '' THEN :ip ELSE CONCAT(ips, ',', :ip) END WHERE md5(random) = :random";
    $st = self::$pdo->prepare($sql);

    $st->execute(array(
      'ip' => $ip,
      'random' => $random
    ));    
  } 

}
