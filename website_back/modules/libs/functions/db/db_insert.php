<?php
function cn_db_insert($data, $tableName, $allLang = false, $returnInsertId = false) {
  global $CFG;
  // var_dump($data);
  // exit;

  $pdo = Admin_DB::getConnection();

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
  $real_values[] = null; // id val should be last value in array
  $SQL = $sql_names.$sql_values;

  $st = $pdo->prepare($SQL);
  $st->execute($real_values);

  $insertId = $pdo->lastInsertId();

  $logConfig = array('item_id' => $insertId, 'type' => 'content_add','table' => $table);
  $Logger = new Logger($logConfig);
  $Logger->save();

  if(!$insertId) {
    throw new Exception('Unable to insert in database (insertId is null)');
  }

  if(!$allLang || defined('MULTIPLE_DOMAINS')) { // Inserting in multi lang, while using multidomains is disabled
    Sitemap::generate();
    return $returnInsertId ? $insertId : true;
  }

  // replace last value by insertId
  $real_values[count($real_values) - 1] = $insertId;

  foreach($CFG['SITE_LANGS'] as $lang) {
    if($lang == Lang::$lang) continue;

    $SQL = preg_replace('#^INSERT INTO `(.*?)` #', 'INSERT INTO `'.$tableName.'_'.$lang.'` ', $SQL);
    $st = $pdo->prepare($SQL);
    $st->execute($real_values);

    $logConfig['table'] = $tableName.'_'.$lang;
    $Logger = new Logger($logConfig);
    $Logger->save();
  }

  Sitemap::generate();
  return $returnInsertId ? $insertId : true;
}
