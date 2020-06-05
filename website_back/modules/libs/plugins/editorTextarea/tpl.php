<div class="form-group">
    <label class="form-label"><?= $cfg->label ?>:  <?= $cfg->required ? '<sup class="field-required-sup">*</sup>' : '' ?></label>
    <textarea id="<?= $cfg->id ?>" 
              name="<?= $cfg->field_name ?>" 
              <?= $cfg->required ? 'data-error-text="'.$cfg->label.'"' : '' ?>
              class="editor-textarea<?= $cfg->required ? ' field-required' : '' ?>"><?= $cfg->value ?></textarea>
</div>

<link rel="stylesheet" type="text/css" href="css/skin.min.css">
<script type="text/javascript" src="js/tinymce/tinymce.js"></script>

<script type="text/javascript">
    window.staticInc = window.staticInc || 0;
    window.staticInc++;

    setTimeout(function() {
        tinymce.PluginManager.load('moxiecut', '<?= ROOT_URL.'admin_resources/' ?>js/tinymce/plugins/moxiecut/plugin.min.js');
        tinymce.init({
            selector: "textarea#<?= $cfg->id ?>",
            theme: "modern",
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: '',
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste moxiecut"
            ],
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link insertfile image media | forecolor | fontsizeselect",
            autosave_ask_before_unload: false,
            height: 400,
            relative_urls: false
        });
    }, window.staticInc * 1000);
</script>
