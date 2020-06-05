<style type="text/css">
	.modules-list{
		padding-left: 25px;
	}
	.modules-list .form-group{
		margin-bottom: 5px;
	}
</style>

<div class="row">
	<div class="col-xl-12">
		<div id="panel-3" class="panel">
			<div class="panel-hdr">
				<h2>
					Edit User
				</h2>
			  	<div class="panel-toolbar">
					<button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
			  	</div>
			</div>
			<div class="panel-container show">
			  	<div class="panel-content">
					<?php if(isset($data->error)) echo '<div style="margin-top: 0;" class="nNote nFailure"><p>'.$data->error.'</p></div>'; ?>

					<form method="post" action="<?= ADMIN_URL.'users/'.($data->user->id ? 'edit/'.$data->user->id : 'add') ?>">
						<div class="form-group">
							<label class="form-label"><?= L('user name') ?></label>
							<input class="form-control" type="text" name="username" value="<?= $data->user->username ?>" placeholder="<?= L('username') ?>">
						</div>
						<div class="form-group">
							<label class="form-label"><?= L('user email') ?></label>
							<input class="form-control" type="text" name="email" value="<?= $data->user->email ?>" placeholder="<?= L('email') ?>">
						</div>
						<div class="form-group">
							<label class="form-label"><?= L('user name') ?></label>
							<input class="form-control" type="password" name="pass" placeholder="<?= L('password') ?>">
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
						        <input type="checkbox" class="module-checkbox custom-control-input" id="modules" value="modules"<?= in_array('modules', $data->rights) ? ' checked="checked"' : '' ?> name="roles[]" >
						        <label class="custom-control-label" for="modules">Modules</label>
						    </div>							
						</div>
						<div class="form-group modules-list">
							<?php foreach($data->modules as $mod) { ?>
								<div class="form-group">
									<div class="custom-control custom-checkbox">
								        <input type="checkbox" class="submodule-checkbox custom-control-input" id="sub-module-<?= $mod->id ?>" <?= isset($mod->moduleid) ? ' checked="checked"' : '' ?> value="<?= $mod->id ?>" name="modules[]" >
								        <label class="custom-control-label" for="sub-module-<?= $mod->id ?>"><?= $mod->name ?></label>
								    </div>	
				              	</div>
				            <?php } ?>
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
						        <input type="checkbox" class="custom-control-input" id="texts" value="texts"<?= in_array('texts', $data->rights) ? ' checked="checked"' : '' ?> name="roles[]" >
						        <label class="custom-control-label" for="texts">texts</label>
						    </div>							
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
						        <input type="checkbox" class="custom-control-input" id="texts_add_remove" value="texts_add_remove"<?= in_array('texts', $data->rights) ? ' checked="checked"' : '' ?> name="roles[]" >
						        <label class="custom-control-label" for="texts_add_remove">texts add/ texts delete</label>
						    </div>							
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
						        <input type="checkbox" class="custom-control-input" id="users" value="users"<?= in_array('users', $data->rights) ? ' checked="checked"' : '' ?> name="roles[]" >
						        <label class="custom-control-label" for="users">Users</label>
						    </div>							
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
						        <input type="checkbox" class="custom-control-input" id="super_user" value="super_user"<?= in_array('super_user', $data->rights) ? ' checked="checked"' : '' ?> name="roles[]" >
						        <label class="custom-control-label" for="super_user">Super User</label>
						    </div>							
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
						        <input type="checkbox" class="custom-control-input" id="logs" value="logs"<?= in_array('logs', $data->rights) ? ' checked="checked"' : '' ?> name="roles[]" >
						        <label class="custom-control-label" for="logs">Logs</label>
						    </div>							
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
						        <input type="checkbox" class="custom-control-input" id="trash" value="trash"<?= in_array('trash', $data->rights) ? ' checked="checked"' : '' ?> name="roles[]" >
						        <label class="custom-control-label" for="trash">trash</label>
						    </div>							
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
						        <input type="checkbox" class="custom-control-input" id="seo" value="seo"<?= in_array('seo', $data->rights) ? ' checked="checked"' : '' ?> name="roles[]" >
						        <label class="custom-control-label" for="seo">seo</label>
						    </div>							
						</div>
						<div class="form-group">
							<input type="hidden" value="<?= $data->user->id ?>" class="buttonS bLightBlue" name="userid">
							<input type="hidden" value="<?= L('update') ?>" name="upsertUser">
							<button type="submit" value="<?= L('update') ?>" class="btn btn-primary mr-2 waves-effect waves-themed">Update</button>
						</div>
					</form>
			 	 </div>
			</div>
		</div>
	</div>
</div>

<script>
$(function() {
  $('.module-checkbox').click(function() {
    var val = $(this).prop('checked');
    
    $('.submodule-checkbox').prop({ checked: val })
  })
})
</script>
     
