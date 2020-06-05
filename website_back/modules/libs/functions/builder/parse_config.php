<?php
function cn_parse_config($file) {
  if(!file_exists($file)) {
  	throw new Exception("Config file not found");
  }

  $cfg = json_decode(file_get_contents($file));

  if(!$cfg) {
  	throw new Exception("Config file contains wrong data");
  }

  return $cfg;
}