<li id="menu_item_<?= $item->id ?>" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded">
    <div><span class="disclose"><span></span></span>
        <?php
            if($cfg->displayFields) {
                foreach($cfg->displayFields as $displayField) {
                    echo $item->{$displayField}."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
                }
            } else {
                echo $item->{$cfg->displayField};
            }
        ?>
        <a href="javascript:void(0);" class="btn btn-outline-secondary btn-sm btn-icon waves-effect waves-themed btn-action-def del-btn" title="Delete" data-id="<?= $item->id ?>">
            <i class="fal fa-times"></i>
        </a>
        <a href="<?= MODULE_URL.'edit/'.$item->id ?>" class="btn btn-outline-secondary btn-sm btn-icon waves-effect waves-themed btn-action-def list-edit-btn" title="Edit">
            <i class="fal fa-pen"></i>
        </a>
    </div>

    <?= $childrenTplView ?>
</li>
