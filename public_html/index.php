<?php

if (!file_exists('config.php') || !file_get_contents('config.php')) {
  require_once 'generateConfig.php';
  exit;
} 

require_once 'config.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/logic.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/view.php';
