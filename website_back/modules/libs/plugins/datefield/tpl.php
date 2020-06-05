<div class="form-group">
    <label class="form-label"><?= $cfg->label ?>:  <?= $cfg->required ? '<sup class="field-required-sup">*</sup>' : '' ?></label>
    <input id="datefield-id-<?= $cfg->field_name ?>" 
           type="text" 
           class="form-control<?= $cfg->required ? ' field-required textfield-field' : '' ?>" 
           <?= $cfg->required ? 'data-error-text="'.$cfg->label.'"' : '' ?>
           autocomplete="off"
           name="<?= $cfg->field_name ?>"<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> 
           value="<?= $cfg->value ?>" />
</div>

<link rel="stylesheet" media="screen, print" href="v2/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
<script src="v2/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>


<?php if($cfg->format == 'Y-m-d') { ?>
    <script>
      var controls = {
          leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
          rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
      }
      $(function() {
        $('#datefield-id-<?= $cfg->field_name ?>').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            orientation: "bottom left",
            todayBtn: "linked",
            clearBtn: true,
            templates: controls
        });
      })
    </script>
<?php } else { ?>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />
    <script src="js/jquery.datetimepicker.js"></script>
    <script>
      $(function() {
        $( "#datefield-id-<?= $cfg->field_name ?>" ).datetimepicker({
          format: "<?= $cfg->format ?>"
        });
      })
    </script>
<?php } ?>
