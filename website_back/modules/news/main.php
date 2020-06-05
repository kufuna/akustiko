<?php
/* Import files */
include_once __DIR__.'/../libs/plugins/Loader.php';

/* Parse configuration file */
$cfg = cn_parse_config(__DIR__."/config.json");
$ExceptionMode = 'HTML';
if(isset($_POSTMODE)) {
  $mode = $_POSTMODE;
  $ExceptionMode = 'JSON';
  $data->id = isset($_POST['id']) ? $_POST['id'] : 0;
} else if($data->action == 'add') {
  $mode = 'add';
} else if($data->action == 'edit') {
  $mode = 'edit';
} else {
  $mode = 'overview';
}

$Plugins = PluginLoader::init($cfg, $mode, $data, $ExceptionMode); // Config, Mode, Data from URL