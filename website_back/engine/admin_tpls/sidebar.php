<!-- Sidebar begins -->
<div id="sidebar">
    <div class="mainNav">
        <div class="user">
            <a title="" class="leftUserDrop"><img src="images/svg/user-white.svg" alt="" /></a><span class="logged-user"><?= AdminUser::getCurrentUser()->username ?></span>
            <ul class="leftUser">
                <li><a href="<?= ADMIN_URL.'logout' ?>" title="" class="sLogout"><?= L('Logout') ?></a></li>
            </ul>
        </div>

        <div class="altNav">
            <ul class="userNav" style="margin-top: 15px">
                <li><a href="#" title="" class="settings"></a></li>
                <li><a href="<?= ADMIN_URL.'logout' ?>" title="" class="logout"></a></li>
            </ul>
        </div>

        <ul class="nav">
            <?php if(in_array('modules', $data->rights)) { ?>
            <li><a href="<?= ADMIN_URL.'home' ?>" title="მოდულები"<?= $data->activeModule == 'home' ? ' class="active"' : '' ?>>
              <i class="icn-file-text"></i><span><?= L('modules') ?></span></a>

            </li>
            <?php } ?>

            <?php if(in_array('texts', $data->rights)) { ?>
            <li><a href="<?= ADMIN_URL.'texts' ?>" title="ტექსტები"<?= $data->activeModule == 'texts' ? ' class="active"' : '' ?>>
              <i class="icn-chat"></i><span><?= L('texts') ?></span></a>
            </li>
            <?php } ?>

            <?php if(in_array('users', $data->rights)) { ?>
            <li><a href="<?= ADMIN_URL.'users' ?>" title="მომხმარებლები"<?= $data->activeModule == 'users' ? ' class="active"' : '' ?>>
              <i class="icn-users"></i><span><?= L('users') ?></span></a>
            </li>
            <?php } ?>

            <?php if(in_array('logs', $data->rights)) { ?>
            <li><a href="<?= ADMIN_URL.'logs' ?>" title="logs"<?= $data->activeModule == 'logs' ? ' class="active"' : '' ?>>
              <i class="icn-edit"></i><span><?= L('logs') ?></span></a>
            </li>
            <?php } ?>

            <?php if(in_array('super_user', $data->rights)) { ?>
            <li><a href="<?= ADMIN_URL.'super_user' ?>" title="Super User"<?= $data->activeModule == 'super_user' ? ' class="active"' : '' ?>>
              <i class="icn-equalizer2"></i><span><?= L('Super User') ?></span></a>
            </li>
            <?php } ?>

            <?php if(in_array('stats', $data->rights)) { ?>
            <li><a href="<?= ADMIN_URL.'stats' ?>" title="stats"<?= $data->activeModule == 'stats' ? ' class="active"' : '' ?>>
              <i class="icn-line-chart"></i><span><?= L('Statistics') ?></span></a>
            </li>
            <?php } ?>

            <?php if(in_array('documentation', $data->rights)) { ?>
            <li><a href="<?= ADMIN_URL.'documentation' ?>" title="დოკუმენტაცია"<?= $data->activeModule == 'documentation' ? ' class="active"' : '' ?>>
              <i class="icn-paste"></i><span><?= L('documantation') ?></span></a>
            </li>
            <?php } ?>

            <?php if(in_array('trash', $data->rights)) { ?>
            <li><a href="<?= ADMIN_URL.'trash' ?>" title="ნაგვის ყუთი"<?= $data->activeModule == 'trash' ? ' class="active"' : '' ?>>
              <i class="icn-bin"></i><span><?= L('trash') ?></span></a>
            </li>
            <?php } ?>

            <?php if(in_array('seo', $data->rights)) { ?>
            <li><a href="<?= ADMIN_URL.'seo' ?>" title="SEO"<?= $data->activeModule == 'seo' ? ' class="active"' : '' ?>>
              <i class="icn-search"></i><span><?= L('SEO') ?></span></a>
            </li>
            <?php } ?>
        </ul>
    </div>

  <?php if($data->submenu) { ?>
    <div class="secNav">
      <div class="secWrapper">

        <div class="secTop">
          <div class="balance">
            <div class="balInfo"><div class="data-titile"><?= $data->title ?></div><span><?= $data->subtitle ?></span></div>
          </div>
        </div>

        <div class="divider" style="margin-top:0"></div>

        <ul class="subNav">
          <?php

          if(isset($data->modules)) {
            foreach($data->modules as $module) {
              if(!$module || !isset($module->id)) continue;
			        echo '<li'.($data->activeMod == $module->id ? ' class="activeli"' : '').'>
			                <a href="'.ADMIN_URL.'module/'.$module->id.'" title=""'.($data->activeMod == $module->id ? ' class="this"' : '').'>';
		                       if ($module->icon != ''){
		                          echo '<img src="'.ROOT_URL.'uploads/adminicons/'.$module->icon.'">';
		                       } else {
		                          echo '<i class="icn-gear">';
		                       }
			                 echo  '</i>'.$module->name.'
			                </a>
			              </li>';

			        if(isset($module->children) && count($module->children)) {
			          foreach($module->children as $child) {
			            echo '<li'.($data->activeMod == $child->id ? ' class="activeli"' : '').' style="border-left: 21px solid #777;">
			                <a href="'.ADMIN_URL.'module/'.$child->id.'" title=""'.($data->activeMod == $child->id ? ' class="this"' : '').'>';
		                       if ($child->icon != ''){
			                          echo '<img src="'.ROOT_URL.'uploads/adminicons/'.$child->icon.'">';
		                       } else {
			                        echo '<i class="icn-forward" style="margin-right: 6px;"></i>';
		                       }
			                 echo  '</span>'.$child->name.'
			                </a>
			              </li>';
			          }
			        }
            }
          } else if(isset($data->langs)) {
            foreach($data->langs as $key => $val) {
			        echo '<li'.($data->activeLang == $key ? ' class="activeli"' : '').'>
			                <a href="'.ADMIN_URL.'texts/'.$key.'" title=""'.($data->activeLang == $key ? ' class="this"' : '').'>
			                  <span class="icos-lng"><img src="images/icons/lang_'.$key.'.png"></span>'.$val.'
			                </a>
			              </li>';
            }
          }
          ?>
			  </ul>

      </div>
      <div class="clear"></div>
    </div>
  <?php } ?>
</div>
<!-- Sidebar ends -->
