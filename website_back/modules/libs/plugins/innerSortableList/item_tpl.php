<li id="menu_item_<?= $item->id ?>" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded">
  <div style="height: auto"><span class="disclose"><span></span></span>
    <?= $cfg->displayImage && $item->{$cfg->displayImage} ? '<img height="60" style="vertical-align: middle" src="'.ROOT_URL.'uploads/'.$cfg->imageFolder.'/'.$item->{$cfg->displayImage}.'" alt="img">' : '' ?>
    <?php
      if($cfg->displayFields) {
        foreach($cfg->displayFields as $displayField) {
          echo $item->{$displayField}."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
        }
      } else {
        echo $item->{$cfg->displayField};
      }
    ?>
    <a href="javascript:void(0)" class="tablectrl_small bDefault tipS del-btn" title="Delete" data-id="<?= $item->id ?>">
      <span class="iconb" data-icon=""></span>
    </a>
    <a href="<?= MODULE_URL.$cfg->editMode.'/'.$item->id ?>" class="tablectrl_small bDefault tipS edit-btn" title="Edit">
      <span class="iconb" data-icon=""></span>
    </a>
  </div>
  
  <?= $childrenTplView ?>
</li>
