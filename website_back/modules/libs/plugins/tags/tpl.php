<div class="formRow">
  <div class="grid3"><label><?= $cfg->label ?>:</label></div>
  <div class="grid9"><input type="text" name="<?= $cfg->field_name ?>" id="<?= $cfg->id ?>" value="<?= $cfg->value ?>" /></div>
  <div class="clear"></div>
</div>

<script type="text/javascript" src="js/plugins/forms/jquery.tagsinput.min.js"></script>
<script>
  $("#<?= $cfg->id ?>").tagsInput({width: '100%'});
</script>
