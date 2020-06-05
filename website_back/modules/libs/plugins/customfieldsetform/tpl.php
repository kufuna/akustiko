<form method="<?= $cfg->method ?>" action="<?= $cfg->action ?>" class="main" enctype="<?= $cfg->enctype ?>">
  <fieldset>
    <div class="widget grid9" style=" margin: 0 auto;">
      <div class="whead"><h6><?= $cfg->title ?></h6><div class="clear"></div></div>
      {InnerPlugin}
      
      <?php if(!$cfg->readOnly) { ?>
      <div class="formRow">
        <button style="float: left; margin-right: 20px;" type="submit" name="post" class="buttonS bBlue "><?= $cfg->submitbtnText ?></button>
        <?php if($cfg->allLangChange) { ?>
          <div style="margin-top: 5px;" class="grid9 check">
              <input type="checkbox" id="plugin_formfield_id" unchecked="unchecked" value="checked" name="fieldsetform-update-all-lang" />
              <label for="plugin_formfield_id" style="user-select: none; -webkit-user-select: none;" class="mr20">Update all language</label>
          </div>
          <script>
            $(function() {
              loadScript(["js/plugins/forms/jquery.uniform.js" ], onload);
              function onload() {
                $("#plugin_formfield_id").uniform();
              }
            })
          </script>
      <?php } ?>
        <?php } ?>
        <div class="clear"></div>
        <?php if($cfg->update_excel){ ?>
          <a href="<?= SITE_URL.'excel' ?>" target="_blank" style="float: left; margin-top: 20px;" class="buttonS bBlue "><?= L('Update Excel') ?></a>
        <?php } ?>
        <div class="clear"></div>
      </div>
    </div>
  </fieldset>
</form>
