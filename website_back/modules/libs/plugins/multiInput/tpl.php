<?php
  $multi_input_fields = json_decode($cfg->value);
  $multi_input_fields = $multi_input_fields ?: array();
?>

<div class="form-group<?= $cfg->required ? ' field-required multi-input-field' : '' ?>" <?= $cfg->required ? 'data-error-text="'.$cfg->label.'"' : '' ?>>
    <label class="form-label"><?= $cfg->label ?>:   <?= $cfg->required ? '<sup class="field-required-sup">*</sup>' : '' ?></label>
    <?php if(count($multi_input_fields) == 0) { ?>
        <div class="input-group multi-inuput-div">
            <input<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> type="text" name="<?= $cfg->key_name ?>[]" class="form-control" placeholder="<?= $cfg->key_placeholder ?>">
            <?php if(!$cfg->readOnly) { ?>
                <div class="input-group-append multi-input-remove multi_input_remove_btn_<?= $cfg->id ?>">
                    <span class="input-group-text"><i class="fal fa-times"></i></span>
                </div>
            <?php } ?>
        </div>

    <?php } else {
        foreach($multi_input_fields as $field) {
        ?>
            <div class="input-group multi-inuput-div">
                <input<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> type="text" name="<?= $cfg->key_name ?>[]" class="form-control" placeholder="<?= $cfg->key_placeholder ?>" value="<?= $field ?>" />
                <?php if(!$cfg->readOnly) { ?>
                    <div class="input-group-append multi-input-remove multi_input_remove_btn_<?= $cfg->id ?>">
                        <span class="input-group-text"><i class="fal fa-times"></i></span>
                    </div>
                <?php } ?>
            </div>
    <?php } } ?>
    <div id="multi_input_wrapper_<?= $cfg->id ?>"></div>
    <?php if(!$cfg->readOnly) { ?>
        <button type="button" class="btn btn-default waves-effect waves-themed multi-input-remove-btn" id="input_<?= $cfg->id ?>">Add more</button>
    <?php } ?>
</div>


<script>
$(function() {
  $('#input_<?= $cfg->id ?>').click(function() {
    var div = $('<div class="input-group multi-inuput-div"></div>').html('<input type="text" class="form-control" name="<?= $cfg->key_name ?>[]" placeholder="<?= $cfg->key_placeholder ?>" value=""> <div class="input-group-append multi-input-remove multi_input_remove_btn_<?= $cfg->id ?>"><span class="input-group-text"><i class="fal fa-times"></i></span></div>')

    div.insertBefore($('#multi_input_wrapper_<?= $cfg->id ?>'));
    initRemoveEvents();
  });
  initRemoveEvents();

  function initRemoveEvents() {
    $('.multi_input_remove_btn_<?= $cfg->id ?>').click(function(){
      $(this).parent().remove()
    })
  }
})
</script>
