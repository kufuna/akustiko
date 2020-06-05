<div class="formRow">
  <div class="grid3"><label><?= $cfg->label ?>:</label></div>
  <div class="grid9">
    <textarea style="resize: vertical;" id="textarea_<?= $cfg->id ?>" rows="<?= $cfg->rows ?>" cols="<?= $cfg->cols ?>" name="<?= $cfg->field_name ?>" placeholder="<?= $cfg->placeholder ?>"><?= $cfg->value ?></textarea>
  </div>
  <div class="clear"></div>
</div>

<iframe id="iframe_<?= $cfg->id ?>" style="display: block; width: 100%; height: 500px; border:0"></iframe>

<script>
$(function() {
  $('#textarea_<?= $cfg->id ?>').change(setPreview).keypress(setPreview).keydown(setPreview)
  
  function setPreview() {
    var self = this;
    setTimeout(function() {
      $('#iframe_plugin_htmlwithpreview_id_1').contents().find("body").html($(self).val())
    }, 50);
  }
  
  setPreview.call($('#textarea_<?= $cfg->id ?>'))
})
</script>
