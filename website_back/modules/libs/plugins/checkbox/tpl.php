<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input<?= $cfg->readOnly ? ' disabled="disabled"' : '' ?> type="checkbox" class="custom-control-input" id="<?= $cfg->id ?>" <?= $cfg->value ? ' checked="checked"' : '' ?> name="<?= $cfg->field_name ?>">
        <label class="custom-control-label" for="<?= $cfg->id ?>"><?= $cfg->label ?></label>
    </div>
</div>
