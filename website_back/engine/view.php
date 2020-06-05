<?php
/* Render each tpl file from page model */
foreach($Model->templates as $item) {
  $data = $item->data; // Export data as variable $data

  if(DEBUG || $Model->isAdmin) {
    include ROOT.'/'.DIR_BACK.'/'.DIR_TPLS.'/'.$item->tpl.'.php'; // Render template file
  } else {
    include ROOT.'/'.DIR_BACK.'/'.DIR_TPLS.'/min/'.$item->tpl.'.php'; // Render template file
  }
}

if($Model->isAdmin) {
  DB_BASE::disconnect();
}
