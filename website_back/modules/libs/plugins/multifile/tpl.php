<div class="formRow">
  <div style="margin-bottom: 15px">
    <label><?=$cfg->label ?>:</label><br />
    <input id="<?= $cfg->id ?>" type="file"<?= $cfg->maxCount > 1 ? ' multiple="multiple"' : '' ?> class="fileInput <?= $cfg->field_name ?>" name="<?= $cfg->field_name ?>[]" />
  </div>
  <div class="<?= $cfg->preview_name ?> multiple-photo-previews">
    <?php
    $i = 0;
    $image_order = array();
    foreach($cfg->value as $image) { ?>
      <div class="photo-preview files no-remove" data-ord="<?= $i ?>">
        <a href="javascript:void(0)" class="tablectrl_small bDefault tipS removeImgBtn"><span class="iconb" data-icon=""></span></a>
        <div class="file"><?= $image->name ?><br>(<?= $image->type ?>)</div>
        <input type="hidden" value="" name="<?= $cfg->crop_name ?>[]" />
        <a style="right: 35px;" href="<?= $image->src ?>" download="<?= $image->name.'.'.$image->type ?>" class="tablectrl_small bDefault tipS downloadFileBtn"><span class="icon-download" style="margin: auto;"><i class="fal fa-download"></i></span></a>
      </div>
    <?php
        $image_order[] = array( 'old' => $i );
        $i++;
      } ?>
    
    <input type="hidden" id="<?= $cfg->image_order ?>" value='<?= json_encode($image_order) ?>' name="<?= $cfg->image_order ?>" />
    <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>

<script>
  $(function() {
    loadCss(["css/jquery.Jcrop.min.css" ]);
  
    loadScript(["js/plugins/forms/jquery.uniform.js", "js/fileupload.js",
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
