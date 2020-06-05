<div class="formRow">
  <label><?= $cfg->label ?>:</label>
  <input type="text" name="<?= $cfg->field_name ?>" placeholder="<?= $cfg->placeholder ?>"<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> value="<?= $cfg->value ? 'https://www.youtube.com/watch?v='.$cfg->value : '' ?>" />
  <?php if($cfg->value) { ?>
    <br><br>
    <iframe width="560" height="315" src="https://www.youtube.com/embed/<?= $cfg->value ?>" frameborder="0" allowfullscreen></iframe>
  <?php } ?>
</div>
