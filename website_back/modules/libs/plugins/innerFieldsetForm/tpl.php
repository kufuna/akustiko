<a href="<?= $cfg->back_url ?>" class="buttonS bBlue">Back</a><br><br>

<form method="<?= $cfg->method ?>" action="<?= MODULE_URL.$cfg->action.'/'.$cfg->urlData->id ?>/" class="main" enctype="<?= $cfg->enctype ?>">
  <fieldset>
    <div class="widget grid9" style=" margin: 0 auto;">
      <div class="whead"><h6><?= $cfg->title ?></h6><div class="clear"></div></div>
      {InnerPlugin}
      
      <?php if(!$cfg->readOnly) { ?>
      <div class="formRow">
        <button style="float: left; margin-right: 20px;" type="submit" name="post" class="buttonS bBlue "><?= $cfg->submitbtnText ?></button>
        <?php } ?>
        <div class="clear"></div>
      </div>
    </div>
  </fieldset>
</form>
