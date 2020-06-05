<?php
function cn_db_remove($table, $query) {
  $pdo = Admin_DB::getConnection();

  $data = DB_BASE::get(array(
    'table' => $table,
    'query' => $query,
    'multiLang' => false
  ));

  $deletedItem = array(
    'item_id' => isset($query['id']) ? $query['id'] : 0,
    'table' => $table
  );
  
  $logConfig = array(
    'item_id' => isset($query['id']) ? $query['id'] : 0,
    'type' => 'content_remove',
    'table' => $table
  );

  $Logger = new Logger($logConfig);

  $Trash = new Trash($deletedItem);

  $SQL = "DELETE FROM `$table` WHERE 1";
  $vals = array();

  foreach($query as $key => $val) {
    $SQL .= ' AND `'.$key.'` = ?';
    $vals[] = $val;
  }

  $st = $pdo->prepare($SQL);
  $st->execute($vals);
  
  $Logger->save();
  $Trash->save();
  
  Sitemap::generate();
  return true;
}
