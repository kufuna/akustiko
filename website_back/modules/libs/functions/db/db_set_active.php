<?php
function cn_db_set_active($tableName, $id, $active) {
  $pdo = Admin_DB::getConnection();

  $id = (int) $id;
  $active = (int) $active ? 1 : 0;

  $SQL = "UPDATE `$tableName` SET active = $active WHERE id = $id";

  $pdo->query($SQL);
  
  Sitemap::generate();
  return true;
}
