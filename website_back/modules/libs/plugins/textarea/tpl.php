<div class="form-group">
	<label class="form-label"><?= $cfg->label ?>:  <?= $cfg->required ? '<sup class="field-required-sup">*</sup>' : '' ?></label>
	<textarea class="form-control<?= $cfg->required ? ' field-required textfield-field' : '' ?>" 
			  rows="<?= $cfg->rows ?>" 
			  cols="<?= $cfg->cols ?>" 
			  name="<?= $cfg->field_name ?>" 
			  <?= $cfg->required ? 'data-error-text="'.$cfg->label.'"' : '' ?>
			  placeholder="<?= $cfg->placeholder ?>"<?= $cfg->readOnly ? ' readonly="readonly"' : '' ?>><?= $cfg->value ?></textarea>
</div>