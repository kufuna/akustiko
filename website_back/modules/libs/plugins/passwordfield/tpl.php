<div class="formRow">
  <label><?= $cfg->label ?>:</label>
  <input type="text" name="<?= $cfg->field_name ?>" placeholder="<?= $cfg->placeholder ?>"<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> value="<?= $cfg->value ?>" />
</div>
