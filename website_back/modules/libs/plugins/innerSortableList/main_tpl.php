<div class="divider"><span></span></div>
<h3 style="padding: 15px; float: left;"><?= $cfg->title ?></h3>

<div class="sidePad" style="width: 200px; margin-top: 15px; float: right;">
  <a href="<?= MODULE_URL.$cfg->addBtn->url.'/'.$cfg->urlData->id.'/' ?>" title="" class="sideB bLightBlue add-lang-text"><?= $cfg->addBtn->text ?></a>
</div>
<div class="clear"></div>

<div class="widget" style="width:99%; background: transparent; border: 0; margin-top: 0">
    <ol class="sortable" id="<?= $cfg->uniq_id ?>">
      {InnerPlugin}
		</ol>
</div>

<div class="divider" style="margin-top: 15px; margin-bottom: 15px;"><span></span></div>

<script>
$(function() {
  loadScript(["js/jquery.mjs.nestedSortable.js", "js/libs/sortableList.js" ], onload);
  function onload() { initSortableListPlugin('<?= POSTMODULE_URL ?>', <?= $cfg->depth ?>, '<?= $cfg->reorderMethod ?>', '<?= $cfg->removeMethod ?>', '#<?= $cfg->uniq_id ?>'); }
})
</script>
