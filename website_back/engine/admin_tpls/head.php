<!DOCTYPE html>
<html lang="<?= Lang::$lang ?>">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title><?= $data->title ?></title>
    <base href="<?= ROOT_URL.'admin_resources/' ?>" />

    <!-- <link href="css/styles.css" rel="stylesheet" type="text/css" /> -->
    <!-- base css -->
    <link rel="stylesheet" media="screen, print" href="v2/css/vendors.bundle.css">
    <link rel="stylesheet" media="screen, print" href="v2/css/app.bundle.css">
    <link rel="mask-icon" href="v2/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <!--[if IE]> <link href="css/ie.css" rel="stylesheet" type="text/css"><![endif]-->

    <link rel="icon" type="image/png" href="<?= ROOT_URL ?>img/favicon.ico"/>

    <!-- <script type="text/javascript" src="js/jquery.min.js"></script> -->
    <script src="v2/js/vendors.bundle.js"></script>
    <script type="text/javascript" src="js/plugins/forms/ui.spinner.js"></script>
    <script type="text/javascript" src="js/plugins/forms/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="js/jquery-ui.min.js"></script>
    
    <script type="text/javascript" src="js/dashboard.js"></script>
    <script type="text/javascript" src="js/scriptsLoader.js"></script>
  </head>

  <body>

    <!-- DOC: script to save and load page settings -->
        <script>
            /**
             *  This script should be placed right after the body tag for fast execution 
             *  Note: the script is written in pure javascript and does not depend on thirdparty library
             **/
            'use strict';

            var classHolder = document.getElementsByTagName("BODY")[0],
                /** 
                 * Load from localstorage
                 **/
                themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
                {},
                themeURL = themeSettings.themeURL || '',
                themeOptions = themeSettings.themeOptions || '';
            /** 
             * Load theme options
             **/
            if (themeSettings.themeOptions)
            {
                classHolder.className = themeSettings.themeOptions;
                console.log("%c✔ Theme settings loaded", "color: #148f32");
            }
            else
            {
                console.log("Heads up! Theme settings is empty or does not exist, loading default settings...");
            }
            if (themeSettings.themeURL && !document.getElementById('mytheme'))
            {
                var cssfile = document.createElement('link');
                cssfile.id = 'mytheme';
                cssfile.rel = 'stylesheet';
                cssfile.href = themeURL;
                document.getElementsByTagName('head')[0].appendChild(cssfile);
            }
            /** 
             * Save to localstorage 
             **/
            var saveSettings = function()
            {
                themeSettings.themeOptions = String(classHolder.className).split(/[^\w-]+/).filter(function(item)
                {
                    return /^(nav|header|mod|display)-/i.test(item);
                }).join(' ');
                if (document.getElementById('mytheme'))
                {
                    themeSettings.themeURL = document.getElementById('mytheme').getAttribute("href");
                };
                localStorage.setItem('themeSettings', JSON.stringify(themeSettings));
            }
            /** 
             * Reset settings
             **/
            var resetSettings = function()
            {
                localStorage.setItem("themeSettings", "");
            }

        </script>
