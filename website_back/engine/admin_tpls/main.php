<!-- <div class="settings" id="content"<?= $data->fullscreen ? ' style="margin-left: 100px;margin-left: 100px;padding-bottom: 60px;position: absolute;padding-top: 49px;margin: auto;right: 0;top: 0;bottom: 0;left: 100px;overflow: auto;"' : ''?>>
  <div class="contentTop">
      <span class="pageTitle"><span class="icon-<?= isset($data->icon) ? $data->icon : '' ?>"></span><?= $data->title ?></span>
      <div class="clear"></div>
  </div>

  <div class="wrapper">
   <div style="margin-top: 35px"></div> -->




   
    <?php
      if(isset($data->modFile)) include_once $data->modFile;
      else include_once 'includes/dashboard.php';
    ?>





<!--   </div>

</div> -->
