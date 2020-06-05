<?php if (!isset($cfg->editModeOnly)): ?>
    <div class="sidePad container-fluid">
        <a onclick="history.back()" title="Add" class="btn btn-primary waves-effect waves-themed btn-link">
            <span class="fal fa-chevron-left mr-1"></span>
            Back    
        </a>
    </div>
<?php endif ?>

<script type="text/javascript" src="v2/js/validate.js"></script>


<div class="alert alert-primary fieldset-form-alert-box" style="display: none">
    <div class="d-flex flex-start w-100">
        <div class="mr-2 hidden-md-down">
            <span class="icon-stack icon-stack-lg">
                <i class="base base-2 icon-stack-3x opacity-100 color-primary-500"></i>
                <i class="base base-2 icon-stack-2x opacity-100 color-primary-300"></i>
                <i class="fal fa-info icon-stack-1x opacity-100 color-white"></i>
            </span>
        </div>
        <div class="d-flex flex-fill">
            <div class="flex-fill">
                <span class="h5">ERROR</span>
                <div>
                  Please fill in all fields that are required, and marked with <sup class="field-required-sup">*</sup> sign: 
                  <code class='errors-data-container'></code>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-xl-12">
      <div id="panel-3" class="panel">
          <div class="panel-hdr">
              <h2>
                  <?= $cfg->title ?>
              </h2>
              <div class="panel-toolbar">
                  <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
              </div>
          </div>
          <div class="panel-container show">
              <div class="panel-content">
                  <form method="<?= $cfg->method ?>" action="<?= $cfg->action ?>" enctype="<?= $cfg->enctype ?>" onsubmit="return validate(this);" >
                      {InnerPlugin}
                      <?php if(!$cfg->readOnly) { ?>
                        <div class="formRow add-edit-row">
                            <button type="submit" name="post" class="btn btn-primary waves-effect waves-themed buttonS bBlue fieldset-form-submit"><?= $cfg->submitbtnText ?></button>
                            <?php if($cfg->allLangChange) { ?>
                              <div class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input" id="plugin_formfield_id" unchecked="unchecked" value="checked" name="fieldsetform-update-all-lang" >
                                  <label class="custom-control-label" for="plugin_formfield_id">Update all language</label>
                              </div>
                          <?php } ?>
                        </div>
                      <?php } ?>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>
