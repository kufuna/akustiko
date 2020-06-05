<?php
function cn_crop_image($target, $cropInfo) {
  $ext = cn_get_extension($target);
  if($ext != 'jpg') throw new Exception("Only jpg photos are acceptable to crop");

  $img_r = imagecreatefromjpeg($target);
  $dst_r = ImageCreateTrueColor( $cropInfo->w, $cropInfo->h );
  imagecopyresampled($dst_r, $img_r, 0, 0, $cropInfo->x, $cropInfo->y, $cropInfo->w, $cropInfo->h, $cropInfo->w, $cropInfo->h);
  imagejpeg($dst_r, $target, 90);
}
