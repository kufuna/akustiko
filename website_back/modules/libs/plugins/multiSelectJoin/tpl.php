<div class="formRow">
  <div class="grid3"><label style="float:none"><?= $cfg->label ?>:</label></div>
  <div class="grid9 <?= $cfg->search ? 'searchDrop' : 'noSearch' ?>">
    <select multiple id="<?= $cfg->id ?>" name="<?= $cfg->field_name ?>[]" data-placeholder="<?= $cfg->placeholder ?>" class="select" style="width:<?= $cfg->width ?>px;"><?= $options ?></select>
  </div>
  <div class="clear"></div>
</div>

<script>
  $(function() {
    loadScript(["js/plugins/forms/jquery.chosen.min.js" ], onload);
    function onload() {
      $("#<?= $cfg->id ?>").chosen();
    }
  })
</script>
