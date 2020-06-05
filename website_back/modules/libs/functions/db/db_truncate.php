<?php
function cn_db_truncate($tableName) {
  $pdo = Admin_DB::getConnection();

  $SQL = "TRUNCATE `$tableName`";

  $pdo->query($SQL);
  
  return true;
}
