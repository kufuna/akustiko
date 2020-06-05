<div class="form-group">
    <div id="<?= $cfg->id ?>" class="input-group">
        <label class="form-label color-picker-label"><?= $cfg->label ?>:<?= $cfg->required ? '<sup class="field-required-sup">*</sup>' : '' ?></label>
        <input type="text" name="<?= $cfg->field_name ?>" id="input_<?= $cfg->id ?>" class="form-control input-lg <?= $cfg->required ? 'field-required' : '' ?>" value="<?= $cfg->value ?>" />
    </div>
</div>

<link rel="stylesheet" media="screen, print" href="v2/css/spectrum.css">
<script src="v2/js/spectrum.js"></script>

<script type="text/javascript">
  window.addEventListener('load', function() {
    $('#input_<?= $cfg->id ?>').spectrum({
        preferredFormat: "hex",
        showInput: true
    });
  })
</script>