<?php
  $data->title = isset($data->title) ? $data->title : L('default_title');
  $data->description = isset($data->description) ? $data->description : L('default_description');
  $data->keywords = isset($data->keywords) ? $data->keywords : L('default_keywords');
  $data->site_name = isset($data->site_name) ? $data->site_name : L('default_site_name');
  $data->canonical = isset($data->canonical) ? $data->canonical : '';
  $data->image = isset($data->image) ? $data->image : '';
  $data->alternates = isset($data->alternates) ? $data->alternates : array();
  $data->css = isset($data->css) ? $data->css : array();
?>
<!DOCTYPE html>
  <html lang="<?= Lang::$lang ?>" class="no-js">
  <head>
    <title><?= $data->title ?></title>
    <base href="<?= ROOT_URL ?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <?php
      foreach($data->alternates as $key => $link) { if($key == Lang::$lang) continue; ?>
      <link rel="alternate" hreflang="<?= $key ?>" href="<?= $link ?>" />
    <?php } ?>

    <!-- DON'T FORGET TO UPDATE -->
    <meta name="description" content="<?= $data->description ?>"/>
    <meta name="keywords"  content="<?= $data->keywords ?>"/>
    <meta name="resource-type" content="document"/>

    <meta property="og:title" content="<?= $data->title ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= $data->canonical ?>" />
    <meta property="og:image" content="<?= $data->image ?>" />
    <meta property="og:site_name" content="<?= $data->site_name ?>" />


    <?php
      if(defined('MINIFY_FILES') && MINIFY_FILES) {
        echo '<link href="css/min/all.css" rel="stylesheet" type="text/css" />';
      } else {
        echo '<link href="css/main.css" rel="stylesheet" type="text/css" />';
      }
    ?>
    <link rel="canonical" href="<?= $data->canonical ?>" />
    <link rel="icon" type="image/png" href="img/fav.ico"/>


  </head>
  <body class="preload body-<?= $data->pageName; ?>">
  <script src="//maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>" async="" defer="defer" type="text/javascript"></script>
<div class="page-wrap page-<?= $data->pageName; ?>">
