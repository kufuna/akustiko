<?php
class DB_BASE {
  public static $pdo = null;

  public static function connect() {
    try {
      self::$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    } catch(PDOException $e) {
      return false;
    }

    self::$pdo->query("SET NAMES 'utf8'");
    self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return true;
  }

  public static function disconnect() {
    self::$pdo = null;
  }

  public static function get($cfg) {
    $cfg = (object) $cfg;
    $table = isset($cfg->table) ? $cfg->table : 'test';
    $query = isset($cfg->query) ? $cfg->query : array();
    $not_query = isset($cfg->not_query) ? $cfg->not_query : array();
    $less_query = isset($cfg->less_query) ? $cfg->less_query : array();
    $more_query = isset($cfg->more_query) ? $cfg->more_query : array();
    $between_query = isset($cfg->between_query) ? $cfg->between_query : array();
    $or_query = isset($cfg->or_query) ? $cfg->or_query : array();
    $like_query = isset($cfg->like_query) ? $cfg->like_query : array();
    $in_query = isset($cfg->in_query) ? $cfg->in_query : array();
    $like_or_query = isset($cfg->like_or_query) ? $cfg->like_or_query : array();
    $like_or_query_array = isset($cfg->like_or_query_array) ? $cfg->like_or_query_array : array();
    $fields = isset($cfg->fields) ? $cfg->fields : 'a.*';
    $limit = isset($cfg->limit) ? (int) $cfg->limit : 1000000000000;
    $skip = isset($cfg->skip) ? (int) $cfg->skip : 0;
    $order = isset($cfg->order) ? $cfg->order : false;
    $group = isset($cfg->group) ? $cfg->group : false;
    $single = isset($cfg->single) ? $cfg->single : false;
    $random = isset($cfg->random) ? $cfg->random : false;
    $multiLang = isset($cfg->multiLang) ? $cfg->multiLang : true;
    $join = isset($cfg->join) ? (object) $cfg->join : false;

    $lang = Lang::$lang;
    $table = $multiLang ? $table.'_'.$lang : $table;

    $joinFields = '';
    if($join) {
      foreach($join->fields as $field) {
        $joinFields .= ', b.'.$field;
      }
    }

    $Q = "SELECT $fields".$joinFields." FROM $table a";

    if($join) {
      $join->table = !isset($join->multiLang) || $join->multiLang ? $join->table.'_'.$lang : $join->table;
      $Q .= " LEFT JOIN {$join->table} b ON a.{$join->key1} = b.{$join->key2}";
    }
    if(count($query) > 0 || count($less_query) > 0 || count($not_query) > 0 || count($more_query) > 0 || count($like_query) || count($or_query) > 0 || count($in_query) > 0) {
      $Q .= " WHERE 1";
    }

    $vals = array();
    foreach($query as $key => $val) {
      $sqlkey = strpos($key, '.') === false ? "a.$key" : $key;
      $Q .= " AND $sqlkey = ?";
      $vals[] = $val ? $val : '';
    }
    
    foreach($not_query as $key => $val) {
      $Q .= " AND a.$key != ?";
      $vals[] = $val;
    }


    if($or_query) {
      $Q .= " AND (";

      $first = true;
      foreach($or_query as $key => $val) {
        if(!$first) $Q .= ' OR ';
        $Q .= "a.$key = ?";
        $vals[] = $val;
        $first = false;
      }

      $Q .= ")";
    }

    foreach($less_query as $key => $val) {
      $Q .= " AND a.$key < ?";
      $vals[] = $val;
    }

    foreach($more_query as $key => $val) {
      $Q .= " AND a.$key > ?";
      $vals[] = $val;
    }

    foreach($between_query as $key => $val) {
      $Q .= " AND a.$key BETWEEN ? AND ?";
      $vals = array_merge($vals, $val);
    }    

    foreach($like_query as $key => $val) {
      $Q .= " AND a.$key LIKE ?";
      $vals[] = $val;
    }

    foreach($in_query as $key => $val) {
      $questionmarks = str_repeat("?,", count($val) - 1) . "?";
      $Q .= " AND a.$key IN ($questionmarks)";
      $vals = array_merge($vals, $val);
    }

    if($like_or_query) {
      if(count($query) == 0) $Q .= ' WHERE 1';
      $Q .= ' AND (';

      foreach($like_or_query as $key => $val) {
        $Q .= "a.$key LIKE ? OR ";
        $vals[] = $val;
      }

      $Q = rtrim($Q, ' OR ');
      $Q .= ')';
    }

    if($like_or_query_array) {
      if(count($query) == 0) $Q .= ' WHERE 1';
      $Q .= ' AND (';

      foreach ($like_or_query_array as $key => $value) {
        foreach($value as $vkey => $vval) {
          $Q .= "a.$key LIKE ? OR ";
          $vals[] = $vval;
        }
      }

      $Q = rtrim($Q, ' OR ');
      $Q .= ')';
    }

    if($group) {
      $Q .= " GROUP BY a.$group";
    }

    if($order) {
      $Q .= " ORDER BY a.$order";
    }

    if($random) {
      $Q .= " ORDER BY RAND()";
    }

    $Q .= " LIMIT $skip, $limit";
    // if($like_or_query_array) {echo $Q; exit;}
    // echo $Q."\n\n";

    try {
      $st = self::$pdo->prepare($Q);
      $st->execute($vals);
    } catch(Exception $e) {
      //echo $Q;exit;
      throw $e;
    }

    if($single || isset($query['id']) && $query['id']) return $st->fetchObject();

    $data = array();
    while($row = $st->fetchObject()) { $data[] = $row; }

    return $data;
  }

  public static function insert($data, $tableName, $allLang = false) {
    global $CFG;

    $pdo = self::$pdo;

    $id = 'NULL';

    $table = $allLang ? $tableName.'_'.Lang::$lang : $tableName;

    $sql_names = "INSERT INTO `$table` (";
    $sql_values = 'VALUES (';
    $real_values = array();

    foreach($data as $key => $val) {
      $sql_names .= '`'.$key.'`,';
      $sql_values .= '?,';
      $real_values[] = $val;
    }

    $sql_names .= 'id) ';
    $sql_values .= '?)';
    $real_values[] = 'NULL'; // id val should be last value in array
    $SQL = $sql_names.$sql_values;

    $st = $pdo->prepare($SQL);
    $st->execute($real_values);

    $insertId = $pdo->lastInsertId();

    if(!$insertId) {
      throw new Exception('Unable to insert in database (insertId is null)');
    }

    if(!$allLang) return $insertId;

    // replace last value by insertId
    $real_values[count($real_values) - 1] = $insertId;

    foreach($CFG['SITE_LANGS'] as $lang) {
      if($lang == Lang::$lang) continue;

      $SQL = preg_replace('#^INSERT INTO `(.*?)` #', 'INSERT INTO `'.$tableName.'_'.$lang.'` ', $SQL);
      $st = $pdo->prepare($SQL);
      $st->execute($real_values);
    }

    return $insertId;
  }

  public static function update($data, $tableName, $id, $allLang = false) {
    global $CFG;

    if(count($data) == 0) return true;

    $id = (int) $id;

    $pdo = self::$pdo;

    $table = $allLang ? $tableName.'_'.Lang::$lang : $tableName;

    if(!$id) throw new Exception("Item id is NULL");

    $SQL = "UPDATE `$table` SET ";
    $real_values = array();

    foreach($data as $key => $val) {
      $SQL .= '`'.$key.'` = ?,';
      $real_values[] = $val;
    }

    $SQL = substr($SQL, 0, strlen($SQL) - 1);

    $SQL .= ' WHERE id = ?';
    $real_values[] = $id;

    $st = $pdo->prepare($SQL);
    $st->execute($real_values);

    if($allLang) {
      foreach($CFG['SITE_LANGS'] as $lang) {
        if($lang == Lang::$lang) continue;

        $SQL = preg_replace('#^UPDATE `(.*?)` #', 'UPDATE `'.$tableName.'_'.$lang.'` ', $SQL);
        $st = $pdo->prepare($SQL);
        $st->execute($real_values);
      }
    }

    return true;
  }

  public static function remove($tableName, $id, $allLang = false) {
    global $CFG;

    $id = (int) $id;
    $pdo = self::$pdo;
    $table = $allLang ? $tableName.'_'.Lang::$lang : $tableName;
    if(!$id) throw new Exception("Item id is NULL");

    $SQL = "DELETE FROM `$table` WHERE id = $id";
    self::$pdo->query($SQL);

    if($allLang) {
      foreach($CFG['SITE_LANGS'] as $lang) {
        if($lang == Lang::$lang) continue;

        $SQL = preg_replace('#^DELETE FROM `(.*?)` #', 'DELETE FROM `'.$tableName.'_'.$lang.'` ', $SQL);
        self::$pdo->query($SQL);
      }
    }

    return true;
  }

  public static function removeChilds($tableName, $parent, $depth) {

    $childs = self::get(array(
      'table' => $tableName,
      'query' => array('parent' => $parent ),
      'multiLang' => false
    ));

    foreach($childs as $child) {
        cn_db_remove($tableName, array( 'id' => $child->id ));

        $depth--;
        if($depth > 0) {
            self::removeChilds($tableName, $child->id, $depth);
        }
    }
  }

  public static function getModules() {
    $data = array(  );

    $sql = "SELECT * FROM cn_admin_modules ORDER BY ord ASC";

    $st = self::$pdo->query($sql);

    while($row = $st->fetchObject()) { $data[] = $row; }

    return $data;
  }

  public static function getModulesById($id) {
    $id = (int) $id;

    if(!$id) throw new Exception("Modules not found");

    $sql = "SELECT * FROM cn_admin_modules  WHERE id = $id";
    return self::$pdo->query($sql)->fetchObject();
  }

  public static function reorderModule($data) {

    $l = count($data);
    for($x = 0; $x < $l; $x++) {
      $id = (int) $data[$x]->id;
      self::$pdo->query("UPDATE cn_admin_modules SET ord = $x WHERE id = $id");

    }
  }

  public static function removeModuleById($post) {
    $id = isset($post['id']) ? (int) $post['id'] : 0;

    if(!$id) throw new Exception("Modules not found");

    $sql = "DELETE FROM cn_admin_modules WHERE id = $id";
    self::$pdo->query($sql);
    return true;
  }


  public static function getDashboard() {
    $data = array(  );

    $sql = "SELECT * FROM cn_admin_dashboard ORDER BY ord ASC";

    $st = self::$pdo->query($sql);

    while($row = $st->fetchObject()) { $data[] = $row; }

    return $data;
  }

  public static function getDashboardById($id) {
    $id = (int) $id;

    if(!$id) throw new Exception("Modules not found");

    $sql = "SELECT * FROM cn_admin_dashboard  WHERE id = $id";
    return self::$pdo->query($sql)->fetchObject();
  }

  public static function reorderDashboard($data) {

    $l = count($data);
    for($x = 0; $x < $l; $x++) {
      $id = (int) $data[$x]->id;
      self::$pdo->query("UPDATE cn_admin_dashboard SET ord = $x WHERE id = $id");

    }
  }

  public static function removeDashboardById($post) {
    $id = isset($post['id']) ? (int) $post['id'] : 0;

    if(!$id) throw new Exception("Modules not found");

    $sql = "DELETE FROM cn_admin_dashboard WHERE id = $id";
    self::$pdo->query($sql);
    return true;
  }

  public static function updateInAllLang($table,$newData,$id) {
    global $CFG;

    if(!$id || !$table || !$newData) throw new Exception("Edit item not found");

    $data = self::get(array(
      'table' => $table,
      'query' => array( 'id' => $id ),
      'single' => true
    ));

    $changeData = array();

    foreach ($data as $key =>  $value) {
      if($key == "id" || $key == 'fields' || $key == "active" || (!isset($newData[$key]))) continue;
      if($value !== $newData[$key]){
        $changeData[$key] = $newData[$key];
      }
    }

    self::update($changeData, $table, $id,true);
  }


  public static function writeAuthLog($user) {
    $userInfo  = getBrowser();
    $sql = "INSERT INTO cn_auth_logs (`user`,browser,platform) VALUES (?, ?, ?)";

    $st = self::$pdo->prepare($sql);
    $st->execute(array(($user->username),($userInfo['browser']),($userInfo['platform'])));

  }


  public static function getAuthLogs() {
    $data = array(  );

    $sql = "SELECT * FROM cn_auth_logs ORDER BY id DESC";

    $st = self::$pdo->query($sql);

    while($row = $st->fetchObject()) { $data[] = $row; }

    return $data;
  }



  /* Stats */
  public static function getStats($tableName, $fromDate = false) {
    $data = array(  );
    $lang = Lang::$lang;
    $fromDate = $fromDate ?: '2014-11-02';

    $sql = "SELECT DATE(`date`) as `date`, COUNT( id ) AS `count` FROM {$tableName}_{$lang} WHERE `date` > '$fromDate' GROUP BY DAY(date)";

    $st = self::$pdo->query($sql);
    while($row = $st->fetchObject()) { $data[] = $row; }

    return $data;
  }

  public static function getLogs() {
    $data = array(  );

    $sql = "SELECT l.*, u.username FROM cn_action_logs l LEFT JOIN cn_admin_users u on l.user = u.id";

    $st = self::$pdo->query($sql);
    while($row = $st->fetchObject()) { $data[] = $row; }

    return $data;
}

public static function getTrash() {

    $data = array(  );

    $sql = "SELECT l.*, u.username FROM cn_trash l LEFT JOIN cn_admin_users u on l.user = u.id";

    $st = self::$pdo->query($sql);
    while($row = $st->fetchObject()) { $data[] = $row; }

    return $data;
}

  public static function getTrashItem($id) {
    $id = (int) $id;

    if(!$id) throw new Exception("Log not found");

    $sql = "SELECT l.*, u.username FROM cn_trash l LEFT JOIN cn_admin_users u on l.user = u.id WHERE l.id = $id";
    return self::$pdo->query($sql)->fetchObject();

  }

public static function DeleteTrashItem($id) {

    $sql = "DELETE FROM cn_trash Where id = $id";

    self::$pdo->query($sql);
  }

  public static function getLogById($id) {
    $id = (int) $id;

    if(!$id) throw new Exception("Log not found");

    $sql = "SELECT l.*, u.username FROM cn_action_logs l LEFT JOIN cn_admin_users u on l.user = u.id WHERE l.id = $id";
    return self::$pdo->query($sql)->fetchObject();

  }

  public static function insertVisitStat($date, $count) {
    try {
      $sql = "INSERT INTO cn_visit_stats (`date`, `count`) VALUES (?, ?)";
      $st = self::$pdo->prepare($sql);
      $st->execute(array($date, $count));
      return true;
    } catch(Exception $e) {
      return false;
    }
  }

  public static function clearAllStats() {
    $sql = "DELETE FROM cn_visit_stats";
    self::$pdo->query($sql);
  }
}
