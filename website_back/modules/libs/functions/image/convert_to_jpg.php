<?php
function cn_convert_to_jpg($src) {
  $currentExt = cn_get_extension($src);
  if($currentExt == 'jpg') return cn_get_filename($src);

  $target_jpg = cn_set_extension($src, 'jpg');

  if($currentExt == 'png') {
    list($w, $h) = getimagesize($src);
    $targetResource = imagecreatetruecolor($w, $h);
    $currentResource = imagecreatefrompng($src);
    imagefill($targetResource, 0, 0,imagecolorallocate($targetResource, 255, 255, 255));
    imagecopyresampled($targetResource, $currentResource, 0, 0, 0, 0, $w, $h, $w, $h);
    imagejpeg($targetResource, $target_jpg, 90);
    imagedestroy($targetResource);
    imagedestroy($currentResource);
  } else {
    $imgResource = imagecreatefromstring(file_get_contents($src));
    imagejpeg($imgResource, $target_jpg, 90);
    imagedestroy($imgResource);
  }

  unlink($src);
  return cn_get_filename($target_jpg);
}

function cn_get_filename($src) {
  $path = explode('/', $src);
  return $path[count($path) - 1];
}

function cn_set_extension($name, $new_ext) {
  $ext = explode('.', $name);
  $ext[count($ext) - 1] = $new_ext;
  $name = implode('.', $ext);
  return $name;
}
