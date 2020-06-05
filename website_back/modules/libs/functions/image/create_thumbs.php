<?php
function cn_create_thumbs($target, $thumbConfig) {
  foreach($thumbConfig as $thumbCfg) {
    $prefix = isset($thumbCfg->prefix) && $thumbCfg->prefix ? $thumbCfg->prefix.'_' : '';
    $thumbCfg->src = $target;
    $thumbCfg->target = cn_add_prefix($target, $prefix);
    new EasyThumbnail((array) $thumbCfg);
    @chmod($target, 0666);
  }
}

function cn_add_prefix($target, $prefix) {
  $path = explode('/', $target);
  $fileName = $path[count($path) - 1];
  $path[count($path) - 1] = $prefix.$fileName;
  $result = implode('/', $path);
  return $result;
}
