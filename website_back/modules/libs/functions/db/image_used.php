<?php
function cn_image_is_used($img, $col, $table) {
  global $CFG;
  
  $pdo = Admin_DB::getConnection();
  $used = false;
  
  $img = '%'.$img.'%';
  
  foreach($CFG['SITE_LANGS'] as $lang) {
    if($lang == Lang::$lang) continue;
    
    $sql = "SELECT id FROM {$table}_{$lang} WHERE $col LIKE '$img'";
    $st = $pdo->query($sql);
    if($st->rowCount() > 0) $used = true;
  }
  
  return $used;
}
