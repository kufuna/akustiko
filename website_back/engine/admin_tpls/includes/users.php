<div class="sidePad container-fluid">
    <a href="<?= ADMIN_URL.'users/add' ?>" title="Add" class="btn btn-primary waves-effect waves-themed btn-link">
        <span class="fal fa-plus mr-1"></span>
        <?= L('add user') ?>
    </a>
</div>

<div id="panel-4" class="panel">
    <div class="panel-hdr">
        <h2>
            Users
        </h2>
        <div class="panel-toolbar">
            <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
        </div>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <table class="table table-bordered m-0">
                <thead>
                    <tr>
                        <th><?= L('users') ?></th>
                        <th><?= L('user email') ?></th>
                        <th style="width: 120px;"><?= L('action') ?></th>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach($data->users as $user) { ?>
	                    <tr data-id="<?= $user->id ?>">
	                        <th scope="row"><?= $user->username ?></th>
	                        <td><?= $user->email ?></td>
	                        <td>
	                        	<a href="<?= ADMIN_URL.'users/edit/'.$user->id ?>" class="btn btn-primary btn-sm btn-icon waves-effect waves-themed view-trash-btn">
                                    <i class="fal fa-pen"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm btn-icon waves-effect waves-themed view-trash-btn btn-remove-user">
                                    <i class="fal fa-trash"></i>
                                </a>
	                        </td>
	                    </tr>
	                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>