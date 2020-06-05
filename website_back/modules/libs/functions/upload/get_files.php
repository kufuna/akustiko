<?php
function cn_get_files($name) {
  /*
   * This function PHP $_FILES fooooooolish array
   * as normal, readable array
  */
  $result = array();
  if(!isset($_FILES[$name])) return $result;

  $files = $_FILES[$name];
  
  if(isset($files['name']) and $files['name']) {
    if(is_array($files['name'])) {
      $l = count($files['name']);
      for($n = 0; $n < $l; $n ++) {
        if(isset($files['name'][$n]) and $files['name'][$n]) {
          $result[] = array(
            'name' => $files['name'][$n],
            'type' => $files['type'][$n],
            'tmp_name' => $files['tmp_name'][$n],
            'error' => $files['error'][$n],
            'size' => $files['size'][$n]
          );
        }
      }
    } else {
      $result[] = $files;
    }
  }

  return $result;
}
