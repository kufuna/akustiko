<div class="form-group">
	<label class="form-label"><?= $cfg->label ?>:<?= $cfg->required ? '  <sup class="field-required-sup">*</sup>' : '' ?></label>
	<input type="text" 
		   name="<?= $cfg->field_name ?>" 
		   class="form-control<?= $cfg->required ? ' field-required textfield-field' : '' ?>" 
		   <?= $cfg->required ? 'data-error-text="'.$cfg->label.'"' : '' ?>
		   placeholder="<?= $cfg->placeholder ?>"<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?> 
		   value="<?= $cfg->value ?>">
</div>