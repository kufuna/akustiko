<?php if(isset($cfg->addBtn) && $cfg->addBtn) { ?>
  <div class="sidePad" style="width: 160px; margin-top: 0px; float: right;">
  </div>
<?php } ?>
<div class="clear"></div>

<div class="widget" style="margin-top:20px">
      <div class="whead"><h6><?= $cfg->title ?></h6><div class="clear"></div></div>
      <div id="dyn2" class="shownpars">
        <a class="tOptions act" title="Options"><img src="images/icons/options" alt="" /></a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="<?= $cfg->id ?>">
          <thead>
            <tr><?= $renderedCols ?></tr>
          </thead>
        <tbody>{InnerPlugin}</tbody>
      </table>
    </div>
    <div class="clear"></div>
</div>

<script>
  $(function() {
    loadScript(["js/plugins/forms/jquery.uniform.js", "js/plugins/tables/jquery.dataTables.10.js",
        "js/plugins/tables/jquery.sortable.js", "js/plugins/tables/jquery.resizable.js",
        "js/plugins/ui/jquery.fancybox.js", "js/plugins/forms/jquery.ibutton.js",
         "js/libs/ajaxTable.js" ], onload);
    function onload() { initAjaxTablePlugin('<?= $cfg->id ?>', '<?= POSTMODULE_URL ?>ajaxPaging/'); }
  })
</script>
