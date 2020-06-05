<?php
  $fields = json_decode($cfg->value);
  $fields = $fields ?: array();
?>

<div class="form-group<?= $cfg->required ? ' field-required multi-fields-field' : '' ?>" <?= $cfg->required ? 'data-error-text="'.$cfg->label.'"' : '' ?>>
    <label class="form-label"><?= $cfg->label ?>:  <?= $cfg->required ? '<sup class="field-required-sup">*</sup>' : '' ?></label>

    <?php if(count($fields) == 0) { ?>
        <div class="input-group multi-group">
            <input<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> type="text" name="<?= $cfg->key_name ?>[]" placeholder="<?= $cfg->key_placeholder ?>" value="" class="form-control field-key" />
            <div class="input-group-append input-group-prepend">
                <span class="input-group-text"><i class="fal fa-angle-double-right"></i></span>
            </div>
            <input<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> type="text" name="<?= $cfg->val_name ?>[]" placeholder="<?= $cfg->val_placeholder ?>" value="" class="form-control field-value" />
            <?php if(!$cfg->readOnly) { ?>
                <div class="input-group-append multi-input-remove remove_btn_<?= $cfg->id ?>">
                    <span class="input-group-text"><i class="fal fa-times"></i></span>
                </div>
            <?php } ?>
        </div>
    <?php } else {
        foreach($fields as $field) {
          foreach($field as $key => $val) {
      ?>
      <div class="input-group multi-group">
        <input<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> type="text" name="<?= $cfg->key_name ?>[]" placeholder="<?= $cfg->key_placeholder ?>" value="<?= $key ?>" class="form-control field-key" />
         <div class="input-group-append input-group-prepend">
            <span class="input-group-text"><i class="fal fa-angle-double-right"></i></span>
        </div>
        <input<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> type="text" name="<?= $cfg->val_name ?>[]" placeholder="<?= $cfg->val_placeholder ?>" value="<?= $val ?>" class="form-control field-value" />
        <?php if(!$cfg->readOnly) { ?>
            <div class="input-group-append multi-input-remove remove_btn_<?= $cfg->id ?>">
                <span class="input-group-text"><i class="fal fa-times"></i></span>
            </div>
        <?php } ?>
      </div>
      <?php } } } ?>
      <div class="clear" id="wrapper_<?= $cfg->id ?>"></div>
      <?php if(!$cfg->readOnly) { ?>
      <button type="button" class="btn btn-default waves-effect waves-themed multi-input-remove-btn" id="<?= $cfg->id ?>">Add more</button>
    <?php } ?>

</div>

<script>
$(function() {
  $('#<?= $cfg->id ?>').click(function() {
    var div = $('<div class="input-group multi-group"></div>').html('<input type="text" name="<?= $cfg->key_name ?>[]" placeholder="<?= $cfg->key_placeholder ?>" value="" class="form-control field-key"> <div class="input-group-append input-group-prepend"><span class="input-group-text"><i class="fal fa-angle-double-right"></i></span></div> <input type="text" name="<?= $cfg->val_name ?>[]" placeholder="<?= $cfg->val_placeholder ?>" value="" class="form-control"> <div class="input-group-append multi-input-remove remove_btn_<?= $cfg->id ?> field-value"><span class="input-group-text"><i class="fal fa-times"></i></span></div>')

    div.insertBefore($('#wrapper_<?= $cfg->id ?>'));
    initRemoveEvents();
  })
  initRemoveEvents();

  function initRemoveEvents() {
    $('.remove_btn_<?= $cfg->id ?>').click(function(){
      $(this).parent().remove()
    })
  }
})
</script>
