<?php
function cn_upload_file($file, $folder, $extensions = false, $withInfo = false) {
  $ext = cn_get_extension($file['name']);
  if($extensions and !in_array($ext, $extensions)) {
    throw new Exception("This file type is not allowed");
  }

  if(!is_dir(UPLOAD_FOLDER.$folder.'/')) {
    mkdir(UPLOAD_FOLDER.$folder.'/', 0777, true);
  }

  $uniqId = uniqid();
  $src = $uniqId.'.'.$ext;
  $target = UPLOAD_FOLDER.$folder.'/'.$src;

  cn_create_directory($folder);

  $fileName = explode('.', $file['name']);
  array_pop($fileName);
  $fileName = implode('.', $fileName);

  if(move_uploaded_file($file['tmp_name'], $target)) {
    if($withInfo) {
      list($width, $height) = getimagesize($target);
      return (object) array(
        'name' => $fileName,
        'size' => $file['size'],
        'src' => $src,
        'type' => $ext,
        'date' => strtotime('now'),
        'width' => $width,
        'height' => $height
      );
    } else {
      return $src;
    }
  } else {
    throw new Exception("Unable to move uploaded file to the server");
  }
}

function cn_get_extension($name) {
  $ext = explode('.', $name);
  $ext = end($ext);
  $ext = strtolower($ext);
  if($ext == 'jpeg') $ext = 'jpg';
  return $ext;
}

function cn_create_directory($folder) {
  $dir = UPLOAD_FOLDER;
  $folders = explode('/', $folder);

  foreach($folders as $folder) {
    if(!$folder) continue;

    if(!is_dir($dir.$folder)) {
      try {
        mkdir($dir.$folder, 0777);
      } catch(Exception $e) {
        throw $e;
      }

      $dir = $dir.$folder.'/';
    }
  }
}
