<?php
/*
  Developer error
*/
class CN_Error {
  public static function err($text) {
    if(function_exists('http_response_code')) http_response_code(503);
    echo '<div style="width:1000px; padding:50px; border:1px solid #000; background:#ec6e6e; margin:20px auto;"><b>Error:</b><br /><br />'.$text.'</div>';
    exit();
  }
}
