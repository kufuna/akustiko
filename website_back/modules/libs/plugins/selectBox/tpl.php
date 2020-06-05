<div class="form-group">
    <label class="form-label"><?= $cfg->label ?>:  <?= $cfg->required ? '<sup class="field-required-sup">*</sup>' : '' ?></label>
    <div class="<?= $cfg->search ? 'searchDrop' : 'noSearch' ?>">
        <select id="<?= $cfg->id ?>" 
                name="<?= $cfg->field_name ?>" 
                <?= $cfg->required ? 'data-error-text="'.$cfg->label.'"' : '' ?>
                class="select-box<?= $cfg->required ? ' field-required field-select' : '' ?>" 
                style="width:<?= $cfg->width ?>px;"><?= $options ?></select>
    </div> 
</div>

<link rel="stylesheet" media="screen, print" href="v2/css/formplugins/select2/select2.bundle.css">
<script src="v2/js/formplugins/select2/select2.bundle.js"></script>
<script>
    $(document).ready(function(){
        $(function(){
             $(".select-box").select2(
            {
                placeholder: "<?= $cfg->placeholder ?>",
                // allowClear: true
            });
        });
    });
</script>