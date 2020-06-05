<?php if(isset($cfg->saleBtn) && $cfg->saleBtn) { ?>
  <div class="sidePad" style="width: 160px; margin-top: 0px; float: right; margin-right:15px;">
    <a href="javscript:;" title="" class="saleButton sideB bLightBlue add-lang-text">Sale</a>
  </div>
<?php } ?>
<?php if(isset($cfg->addBtn) && $cfg->addBtn) { ?>
  <div class="sidePad" style="width: 200px; margin-top: 15px; float: right;">
    <a href="<?= MODULE_URL.$cfg->addBtn->url ?>" title="" class="sideB bLightBlue add-lang-text"><?= $cfg->addBtn->text ?></a>
  </div>
<?php } ?>
<?php if(isset($cfg->sendBtn) && $cfg->sendBtn) { ?>
  <div class="sidePad" style="width: 200px; margin-top: 15px; float: left;">
    <a href="javscript:;" title="" data-href="<?= SITE_URL.'send' ?>" class="sideB sendButton bLightBlue add-lang-text"><?= $cfg->sendBtn->text ?></a>
  </div>
<div class="clear"></div>
<div class="sidePad sendMessage" style="text-align: center;margin-top: 15px;float: none;width: 100%;padding: 0">
 
</div>
<?php } ?>



<?php if($cfg->export) { ?>
  <form action="<?= POSTMODULE_URL.'export/' ?>" method="post" class="sidePad" style="width: 200px; margin-top: 15px; float: left;">
    <button type="submit" style="padding: 11px" class="sideB bLightBlue add-lang-text"><?= $cfg->export->title ?></button>
  </form>
<?php } ?>
<div class="clear"></div>

<div class="widget" style="margin-top:20px">
      <div class="whead"><h6><?= $cfg->title ?></h6><div class="clear"></div></div>
      <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options"><img src="images/icons/options.png" alt="options" /></a>
        <form data-url="<?= SITE_URL.'ajax-sale' ?>" data-redirect="<?= ADMIN_URL.'module/13' ?>" id="frm-example" action="" method="POST">
          <table cellpadding="0" cellspacing="0" border="0" class="dTable">
            <thead>
              <tr><?= $renderedCols ?></tr>
            </thead>
          <tbody>{InnerPlugin}</tbody>
          <?php if($cfg->columnFilter) { ?>
          <tfoot>
            <tr><?= $renderedCols ?></tr>
          </tfoot>
          <?php } ?>
        </table>
      </form>
    </div>
    <div class="clear"></div>
</div>

    

<script>
  $(function() {
    loadScript(["js/plugins/forms/jquery.uniform.js", "js/plugins/tables/jquery.dataTables.10.js",
        "js/plugins/tables/jquery.sortable.js", "js/plugins/tables/jquery.resizable.js",
        "js/plugins/ui/jquery.fancybox.js", "js/plugins/forms/jquery.ibutton.js",
        <?= $cfg->checkboxes ? '"js/libs/dynamicTableCustom.10.js"' : '"js/libs/dynamicTable.10.js"'  ?>
          ], onload);
    function onload() {
      initDynamicTablePlugin(<?= $cfg->columnFilter ? 'true' : 'false' ?>);
    }
  })
</script>
