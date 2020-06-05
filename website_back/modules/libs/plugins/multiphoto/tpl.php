<div class="formRow form-group">
  <div style="margin-bottom: 15px">
    <label><?=$cfg->label ?>:  <?= $cfg->required ? '<sup class="field-required-sup">*</sup>' : '' ?></label><br />
    <input id="<?= $cfg->id ?>" <?= $cfg->required ? 'data-error-text="'.$cfg->label.'"' : '' ?> type="file" multiple="multiple" class="fileInput <?= $cfg->required ? ' field-required' : '' ?> <?= $cfg->field_name ?>" name="<?= $cfg->field_name ?>[]" />
  </div>
  <div class="<?= $cfg->preview_name ?> multiple-photo-previews">
    <?php
    $i = 0;
    $image_order = array();
    foreach($cfg->value as $image) { ?>
      <div class="photo-preview no-remove" data-ord="<?= $i ?>">
        <a href="javascript:void(0)" class="tablectrl_small bDefault tipS removeImgBtn"><span class="iconb" data-icon=""></span></a>
        <img src="<?= $image . '?nocache='.rand(0, 10000) ?>" draggable="false" />
        <input type="hidden" value="" name="<?= $cfg->crop_name ?>[]" />
      </div>
    <?php
        $image_order[] = array( 'old' => $i );
        $i++; } ?>
    
    <input type="hidden" id="<?= $cfg->image_order ?>" value='<?= json_encode($image_order) ?>' name="<?= $cfg->image_order ?>" />
    <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>

<script>
  $(function() {
    loadCss(["css/jquery.Jcrop.min.css" ]);
  
    loadScript(["js/plugins/forms/jquery.uniform.js", "js/imageupload.js",
                "js/sortable.js", "js/jquery.Jcrop.js" ], onload);
    
    function onload() {
      $("#<?= $cfg->id ?>").uniform();
    
      var sortCfg = initSortable('.<?= $cfg->preview_name ?>.multiple-photo-previews .photo-preview', initPhotoRemoveEvents, $('#<?= $cfg->image_order ?>'));
  
      var PU = initPhotoUpload($('.<?= $cfg->field_name ?>'), $('.<?= $cfg->preview_name ?>'), '<?= $cfg->crop_name ?>[]', onImageAdd, <?= $cfg->maxCount ?>)
      
      function onImageAdd() {
        sortCfg.calculateReorders();
        sortCfg.reInitEvents();
      }

      function initPhotoRemoveEvents() {
        $('.removeImgBtn').click(function() {
          p = $(this).parent();
          p.remove();
          sortCfg.calculateReorders();
        })
        
        PU.reInitEvents();
      }
      initPhotoRemoveEvents();
    }
  });
</script>
