<?php
/* Import files */
include_once __DIR__.'/../libs/plugins/Plugin.php';
include_once __DIR__.'/../libs/plugins/Loader.php';
include_once __DIR__.'/../libs/functions/builder/parse_config.php';
include_once __DIR__.'/../libs/functions/db/db_insert.php';
include_once __DIR__.'/../libs/functions/db/db_update.php';

/* Parse configuration file */
$cfg = cn_parse_config(__DIR__."/config.json");

$mode = 'edit';

$Plugins = PluginLoader::init($cfg, $mode, $data); // Config, Mode, Data from URL