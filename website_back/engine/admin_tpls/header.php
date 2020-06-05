<div class="page-wrapper">
    <div class="page-inner">
        <!-- BEGIN Left Aside -->
        <aside class="page-sidebar">
            <div class="page-logo">
                <a href="<?= ADMIN_URL ?>"
                   class="page-logo-link press-scale-down d-flex align-items-center position-relative">
                    <img src="<?= ROOT_URL.'img/akustiko-logo.png' ?>" alt="LOGO" aria-roledescription="logo">
                </a>
            </div>
            <!-- BEGIN PRIMARY NAVIGATION -->
            <nav id="js-primary-nav" class="primary-nav" role="navigation">
                <div class="nav-filter">
                    <div class="position-relative">
                        <input type="text" id="nav_filter_input" placeholder="Filter menu" class="form-control"
                               tabindex="0">
                        <a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off"
                           data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
                            <i class="fal fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <ul id="js-nav-menu" class="nav-menu">
                    <li>
                        <a href="<?= ADMIN_URL ?>" title="dashboard"
                           data-filter-tags="dashboard" <?= $data->activeModule == 'home' ? ' class="active"' : '' ?>>
                            <i class="fal fa-user"></i>
                            <span class="nav-link-text" data-i18n="nav.ui_components">Dashboard</span>
                        </a>
                    </li>
                    <?php if (in_array('modules', $data->rights)) { ?>
                        <li <?= $data->activeModule == 'module' ? ' class="active open"' : '' ?>>
                            <a href="#" title="Modules" data-filter-tags="modules">
                                <i class="fal fa-info-circle"></i>
                                <span class="nav-link-text" data-i18n="nav.modules">Modules</span>
                            </a>
                            <ul>
                                <?php
                                if (isset($data->modules)) {
                                    foreach ($data->modules as $module) {
                                        if (!$module || !isset($module->id)) continue;
                                        $childs = '';
                                        $childActive = false;
                                        $end = '</li>';

                                        if (isset($module->children) && count($module->children)) {
                                            $head = '<a href="javascript:void(0)" title="' . $module->name . '" data-filter-tags="modules ' . strtolower($module->name) . '">
                                                                <span class="nav-link-text" data-i18n="nav.modules_' . str_replace(' ', '_', strtolower($module->name)) . '">' . $module->name . '</span>
                                                            </a>';
                                            $childs .= '<ul>';
                                            foreach ($module->children as $child) {
                                                if (isset($data->activeMod) && $data->activeMod == $child->id) {
                                                    $childActive = true;
                                                }
                                                $childs .= '<li ' . (isset($data->activeMod) && $data->activeMod == $child->id ? 'class="active"' : '') . '>
                                                                    <a href="' . ADMIN_URL . 'module/' . $child->id . '" title="' . $module->name . '" data-filter-tags="modules ' . strtolower($module->name) . ' ' . strtolower($child->name) . '" >
                                                                        <span class="nav-link-text" data-i18n="nav.utilities_menu_child_sublevel_item">' . $child->name . '</span>
                                                                    </a>
                                                                </li>';
                                            }
                                            $childs .= '</ul>';
                                        } else {
                                            $head = '<a href="' . ADMIN_URL . 'module/' . $module->id . '" title="' . $module->name . '" data-filter-tags="modules ' . strtolower($module->name) . '">
                                                                <span class="nav-link-text" data-i18n="nav.modules_' . str_replace(' ', '_', strtolower($module->name)) . '">' . $module->name . '</span>
                                                        </a>';
                                        }

                                        $start = '<li ' . ($childActive == true ? 'class="active open"' : (isset($data->activeMod) && $data->activeMod == $module->id ? 'class="active"' : '')) . '>';
                                        echo $start . $head . $childs . $end;
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <li class="nav-title">Users & Configuration</li>
                    <li>
                        <?php if (in_array('users', $data->rights)) { ?>
                            <a href="<?= ADMIN_URL . 'users' ?>" title="users"
                               data-filter-tags="users" <?= $data->activeModule == 'users' ? ' class="active"' : '' ?>>
                                <i class="fal fa-user"></i>
                                <span class="nav-link-text" data-i18n="nav.ui_components">Users</span>
                            </a>
                        <?php } ?>
                    </li>
                    <li class="nav-title">Tools & Components</li>
                    <li>
                        <?php if (in_array('texts', $data->rights)) { ?>
                            <a href="<?= ADMIN_URL . 'texts' ?>" title="texts"
                               data-filter-tags="texts" <?= $data->activeModule == 'texts' ? ' class="active"' : '' ?>>
                                <i class="fal fa-font"></i>
                                <span class="nav-link-text" data-i18n="nav.ui_components">Texts</span>
                            </a>
                        <?php } ?>
                    </li>
                    <li>
                        <?php if (in_array('seo', $data->rights)) { ?>
                            <a href="<?= ADMIN_URL . 'seo' ?>" title="seo"
                               data-filter-tags="seo" <?= $data->activeModule == 'seo' ? ' class="active"' : '' ?>>
                                <i class="fal fa-search-plus"></i>
                                <span class="nav-link-text" data-i18n="nav.ui_components">Seo</span>
                            </a>
                        <?php } ?>
                    </li>
                    <li>
                        <?php if (in_array('logs', $data->rights)) { ?>
                            <a href="<?= ADMIN_URL . 'logs' ?>" title="logs"
                               data-filter-tags="logs" <?= $data->activeModule == 'logs' ? ' class="active"' : '' ?>>
                                <i class="fal fa-cogs"></i>
                                <span class="nav-link-text" data-i18n="nav.ui_components">Logs</span>
                            </a>
                        <?php } ?>
                    </li>
                    <li>
                        <?php if (in_array('trash', $data->rights)) { ?>
                            <a href="<?= ADMIN_URL . 'trash' ?>" title="trash"
                               data-filter-tags="trash" <?= $data->activeModule == 'trash' ? ' class="active"' : '' ?>>
                                <i class="fal fa-trash"></i>
                                <span class="nav-link-text" data-i18n="nav.ui_components">Trash</span>
                            </a>
                        <?php } ?>
                    </li>

                </ul>
                <div class="filter-message js-filter-message bg-success-600"></div>
            </nav>
            <!-- END PRIMARY NAVIGATION -->
        </aside>
        <!-- END Left Aside -->

        <!-- Page Content -->
        <div class="page-content-wrapper">
            <!-- BEGIN Page Header -->
            <header class="page-header" role="banner">
                <!-- we need this logo when user switches to nav-function-top -->
                <div class="page-logo">
                    <a href="#" class="page-logo-link press-scale-down d-flex align-items-center position-relative"
                       data-toggle="modal" data-target="#modal-shortcut">
                        <img src="v2/img/logo.png" aria-roledescription="logo">
                        <span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2"></span>
                        <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
                    </a>
                </div>
                <!-- DOC: nav menu layout change shortcut -->
                <div class="hidden-md-down dropdown-icon-menu position-relative">
                    <a class="header-btn btn js-waves-off" data-action="toggle" data-class="nav-function-hidden"
                       title="Hide Navigation">
                        <i class="ni ni-menu"></i>
                    </a>
                    <ul>
                        <li>
                            <a class="btn js-waves-off" data-action="toggle" data-class="nav-function-minify"
                               title="Minify Navigation">
                                <i class="ni ni-minify-nav"></i>
                            </a>
                        </li>
                        <li>
                            <a class="btn js-waves-off" data-action="toggle" data-class="nav-function-fixed"
                               title="Lock Navigation">
                                <i class="ni ni-lock-nav"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- DOC: mobile button appears during mobile width -->
                <div class="hidden-lg-up">
                    <a href="#" class="header-btn btn press-scale-down" data-action="toggle" data-class="mobile-nav-on">
                        <i class="ni ni-menu"></i>
                    </a>
                </div>
                <div class="ml-auto d-flex">

                    <!-- Langs Here -->
                    <?php foreach ($data->langs as $key => $val) { ?>
                        <div class="menu-lang-div <?= $key == Lang::$lang ? 'menu-lang-active-div' : '' ?>">
                            <a href="<?= $val ?>" class="header-icon language">
                                <div>
                                    <div class="lang-icon"
                                         style="background-image: url('v2/img/langs/lang_<?= $key ?>.png');"></div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>

                    <!-- app user menu -->
                    <div>
                        <a href="#" data-toggle="dropdown" title="drlantern@gotbootstrap.com"
                           class="header-icon d-flex align-items-center justify-content-center">
                            <i class="fal fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg">
                            <div class="dropdown-header bg-trans-gradient d-flex flex-row py-4 rounded-top">
                                <div class="d-flex flex-row align-items-center mt-1 mb-1 color-white">
                                    <span class="mr-2">
                                        <img src="v2/img/svg/user-white.svg" class="rounded-circle profile-image"
                                             alt="Dr. Codex Lantern">
                                    </span>
                                    <div class="info-card-text">
                                        <div class="fs-lg text-truncate text-truncate-lg"><?= AdminUser::getCurrentUser()->username ?></div>
                                        <span class="text-truncate text-truncate-md opacity-80"><?= AdminUser::getCurrentUser()->email ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider m-0"></div>
                            <a href="#" class="dropdown-item app-fullscreen" data-action="app-fullscreen">
                                <span data-i18n="drpdwn.fullscreen">Fullscreen</span>
                                <i class="float-right text-muted fw-n">F11</i>
                            </a>
                            <div class="dropdown-multilevel dropdown-multilevel-left user-languages-dropdown">
                                <div class="dropdown-item">
                                    Languages
                                </div>
                                <div class="dropdown-menu">
                                    <?php foreach ($data->langs as $key => $val) { ?>
                                        <div>
                                            <a href="<?= $val ?>"
                                               class="dropdown-item <?= $key == Lang::$lang ? 'active' : '' ?>">
                                                <?= $key ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="dropdown-divider m-0"></div>
                            <a class="dropdown-item fw-500 pt-3 pb-3" href="<?= ADMIN_URL . 'logout' ?>">
                                <span data-i18n="drpdwn.page-logout">Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            <!-- END Page Header -->

            <!-- BEGIN Page Content -->
            <main id="js-page-content" role="main" class="page-content">
