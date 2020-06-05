<div class="sidePad container-fluid">
    <a href="<?= MODULE_URL.$cfg->addBtn->url ?>" title="Add" class="btn btn-primary waves-effect waves-themed btn-link">
        <span class="fal fa-plus mr-1"></span>
        <?= $cfg->addBtn->text ?>
    </a>
</div>
<div class="clear"></div>

<div class="widget" style="width:99%; background: transparent; border: 0; margin-top: 0">
    <ol class="sortable">
        {InnerPlugin}
    </ol>
</div>

<div class="divider" style="margin-top: 15px; margin-bottom: 15px;"><span></span></div>

<script>
    $(function() {
        loadScript(["js/jquery.mjs.nestedSortable.js", "js/libs/sortableList.js"], onload);

        function onload() {
            initSortableListPlugin('<?= POSTMODULE_URL ?>', <?= $cfg->depth ?> );
        }
    })

</script>
