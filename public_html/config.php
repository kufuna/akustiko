<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'admin');
define('DB_PASS', 'admin123');
define('DB_NAME', 'akustiko');
define('DIR_FRONT', 'public_html');
define('DIR_BACK', 'website_back');
define('ROOT', '../');
define('ROOT_URL', 'http://akustiko.test/');
define('DOMAIN', 'akustiko.test');
define('DEFAULT_LANG', 'ka');
define('MAPS_API_KEY', 'AIzaSyBOYB-uk489gOpMKTxw-2j-uxrPanFOSwc');
define('ADMIN_SESSION_NAMESPACE', '_CN_ADMIN_USER');
define('USER_SESSION_NAMESPACE', '_CN_USER');

$dynamicConfig = json_decode(file_get_contents(ROOT . DIR_BACK . '/config.json'));
if ($dynamicConfig) {
    $dynamicConfig = $dynamicConfig->general;

    foreach ($dynamicConfig as $key => $val) {
        define($key, $val);
    }
}

ini_set('display_errors', DEBUG);
error_reporting((DEBUG ? E_ALL : 0));
define('ACCESS_LOG_PATH', '../logs/book.log');
date_default_timezone_set('Asia/Tbilisi');
mb_internal_encoding('utf8');

$CFG = (array(
    'SITE_LANGS' =>
        array(
            0 => 'ka',
            1 => 'en'
        ),
    'LANG_NAMES' =>
        (array(
            'ka' => 'Georgian',
            'en' => 'English',
        )),
    'LANG_SHORT_NAMES' =>
        (array(
            'ka' => 'Ka',
            'en' => 'En',
        )),
    'stats' =>
        (array()),
));

$SITE_LANGS = array(0 => 'ka', 1 => 'en');