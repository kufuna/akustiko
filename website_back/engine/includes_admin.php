<?php
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/admin/main.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/admin/user.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/admin/db.php';

require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/admin/modules/head.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/admin/modules/header.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/admin/modules/pageleft.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/admin/modules/pagemain.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/admin/modules/footer.php';

require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/Settings.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/Sitemap.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/export/excel.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/logs/logger.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/trash/Trash.php';

require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/Stats.php';

require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/minifier/src/Converter/Converter.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/minifier/src/Minify.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/minifier/src/CSS.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/minifier/src/JS.php';
require_once ROOT.'/'.DIR_BACK.'/'.'engine/classes/Minifier.php';