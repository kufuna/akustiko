<?php
function cn_db_reorderItems($data, $tableName, $parent = 0, $depth = 1) {
  $pdo = Admin_DB::getConnection();

  $l = count($data);
  for($x = 0; $x < $l; $x++) {
    $id = (int) $data[$x]->id;
    $sql = "UPDATE `$tableName` SET ord = $x".($depth > 1 ? ", parent = $parent" : '')." WHERE id = $id";
    $pdo->query($sql);

    if(isset($data[$x]->children) && count($data[$x]->children)) {
      cn_db_reorderItems($data[$x]->children, $tableName, $id, $depth);
    }
  }
  
  return true;
}
