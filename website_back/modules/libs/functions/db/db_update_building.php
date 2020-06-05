<?php
function cn_db_update_building($data, $tableName, $tableKey, $id, $allLang = false) {
  global $CFG;

  $id = (int) $id;

  $pdo = Admin_DB::getConnection();

  $table = $allLang ? $tableName.'_'.Lang::$lang : $tableName;

  $logConfig = array(
    'item_id' => $id,
    'type' => 'content_edit',
    'table' => $table
  );
  $Logger = new Logger($logConfig);

  if(!$id) throw new Exception("Item id is NULL");

  $SQL = "UPDATE `$table` SET ";
  $real_values = array();

  foreach($data as $key => $val) {
    $SQL .= '`'.$key.'` = ?,';
    $real_values[] = $val;
  }

  $SQL = substr($SQL, 0, strlen($SQL) - 1);

  $SQL .= ' WHERE '.$tableKey.' = ?';
  $real_values[] = $id;

  $st = $pdo->prepare($SQL);
  $st->execute($real_values);

  $Logger->save();

  if($allLang) {
    foreach($CFG['SITE_LANGS'] as $lang) {
      if($lang == Lang::$lang) continue;

      $logConfig['table'] = $tableName.'_'.$lang;
      $Logger = new Logger($logConfig);

      $SQL = preg_replace('#^UPDATE `(.*?)` #', 'UPDATE `'.$tableName.'_'.$lang.'` ', $SQL);
      $st = $pdo->prepare($SQL);
      $st->execute($real_values);  
      
      $Logger->save();
    }
  }
  
  Sitemap::generate();
  return true;
}
