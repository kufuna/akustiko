<div class="formRow form-group">
  <div style="margin-bottom: 15px">
    <label><?= $cfg->label ?>:  <?= $cfg->required ? '<sup class="field-required-sup">*</sup>' : '' ?></label><br />
    <input id="<?= $cfg->id ?>" type="file" <?= $cfg->required ? 'data-error-text="'.$cfg->label.'"' : '' ?> class="form-control fileInput <?= $cfg->required ? 'field-required' : '' ?> <?= $cfg->field_name ?>" name="<?= $cfg->field_name ?>" />
  </div>
  <div class="<?= $cfg->preview_name ?>">
    <?php if($cfg->value) { ?>
      <div class="photo-preview">
        <a href="javascript:void(0)" class="tablectrl_small bDefault tipS removeImgBtn <?= $cfg->delete_name ?>"><span class="iconb" data-icon=""></span></a>
        <img src="<?= $cfg->value ?>?nocache='<?= rand(0, 10000) ?>" />
        <input type="hidden" value="" name="<?= $cfg->crop_name ?>" class="<?= $cfg->required ? ' field-required' : '' ?>" />
      </div>
    <?php } ?>
      <input type="hidden" value="" name="<?= $cfg->delete_name ?>" id="<?= $cfg->delete_name ?>" />
  </div>
  <div class="clear"></div>
</div>

<script>
  $(function() {
    loadCss(["css/jquery.Jcrop.min.css" ]);
    
    loadScript(["js/plugins/forms/jquery.uniform.js", "js/imageupload.js",
                "js/jquery.Jcrop.js" ], onload);
                
    function onload() {
      $("#<?= $cfg->id ?>").uniform();
      var PU = initPhotoUpload($(".<?= $cfg->field_name ?>"), $(".<?= $cfg->preview_name ?>"), ".<?= $cfg->crop_name ?>", initPhotoRemoveEvents);
    }
    
    function initPhotoRemoveEvents() {
      $('.removeImgBtn.<?= $cfg->delete_name ?>').click(function() {
        p = $(this).parent();
        p.remove();
        $('#<?= $cfg->delete_name ?>').val('removed')
      })
    }
    initPhotoRemoveEvents();
  })
</script>


