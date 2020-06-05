<style>
  textarea { height: 27px; resize: none; color: #000; }
</style>

<div class="row">
  <div class="col-xl-12">
    <div id="panel-3" class="panel">
      <div class="panel-hdr">
        <h2>
          CMS Setting
        </h2>
          <div class="panel-toolbar">
          <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
          </div>
      </div>
      <div class="panel-container show">
          <div class="panel-content">
            <form action="<?= ADMIN_URL ?>super_user" method="POST">
                <?php
                  foreach($data->settings as $key => $val) {
                    if($key == 'DEBUG') { ?>
                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="<?= $key ?>" class="custom-control-input" id="<?= $key ?>" <?= $val == 1 ? ' checked="checked"' : '' ?> <?= $key ?> >
                            <label class="custom-control-label" for="<?= $key ?>"><?= $key ?>: </label>
                        </div>
                      </div>
                    <?php } else { ?>
                      <div class="form-group">
                        <label class="form-label"><?= $key ?>: </label>
                        <input type="text" value="<?= $val ?>" name="<?= $key ?>"   class="form-control"/>
                      </div>
                    <?php }
                  } ?>
                <div class="form-group">
                    <input type="hidden" value="Save" name="update_settings">
                    <button type="submit" class="btn btn-primary mr-2 waves-effect waves-themed">Update</button>
                </div>
            </form>
         </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div id="panel-3" class="panel">
      <div class="panel-hdr">
        <h2>
          Truncate tables
        </h2>
          <div class="panel-toolbar">
          <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
          </div>
      </div>
      <div class="panel-container show">
          <div class="panel-content">

              <form action="<?= ADMIN_URL ?>super_user/" method="POST" onsubmit="return confirmTrashTruncate()" style="float: left;">
                <div class="form-group">
                    <input type="hidden" value="Truncate trash" name="truncate-trash">
                    <button type="submit" class="btn btn-danger mr-2 waves-effect waves-themed">Truncate Trash</button>
                </div>
              </form>

              <form action="<?= ADMIN_URL ?>super_user/" method="POST" onsubmit="return confirmLogsTruncate()" style="float: left;">
                <input type="hidden" value="Truncate logs" name="truncate-logs">
                <button type="submit" class="btn btn-danger mr-2 waves-effect waves-themed">Truncate Logs</button>
              </form>

              <div class="clear"></div>
         </div>
      </div>
    </div>
  </div>
</div>

<?php if (isset($data->settings->MINIFY_FILES)): ?>
  <!-- <fieldset style="margin-top: 50px;">
    <div class="fluid">
      <div class="widget grid6" style="width: 100%">
        <div class="whead"><h6>Minify</h6><div class="clear"></div></div>

      <div class="formRow">
        <form action="<?= ADMIN_URL ?>super_user/" method="POST" style="float: left; margin-right: 30px;">
          <input type='submit' style="margin: 0 auto; width: 160px; height: 40px;" value="Minify Css" name="minify-css" class="sideB bLightBlue">
        </form>

        <form action="<?= ADMIN_URL ?>super_user/" method="POST" style="float: left; margin-right: 30px;">
          <input type='submit' style="margin: 0 auto; width: 160px; height: 40px;" value="Minify Js" name="minify-js" class="sideB bLightBlue">
        </form>

        <div class="clear"></div>
      </div>

      <div class="formRow">
        <form action="<?= ADMIN_URL ?>super_user" method="POST">
          <div class="formRow">
            <div class="grid3" style="width: 200px"><label>Use minify files:</label></div>
            <div class="grid9 on_off minify-mod">
              <div class="floatL mr10">
                <input type="checkbox" <?= $data->settings->MINIFY_FILES == 1 ? ' checked="checked"' : '' ?> />
                <input type="hidden" name="set-minify-mode">
              </div>
            </div>
            <div class="clear"></div>
          </div>
        </form>
      </div>

      <div class="clear"></div>
    </div>
  </fieldset> -->
<?php endif ?>


<script type="text/javascript" src="js/plugins/forms/jquery.ibutton.js"></script>
<script>
  $(function() {
    $('.on_off').iButton({ labelOn: "" , labelOff: "" });
  })

  $('.minify-mod input[type="checkbox"]').on('change', function(event) {
    $('.minify-mod input[type="hidden"]').val($(this)[0].checked ? 1 : 0);
    $('.minify-mod').closest('form').submit();
  });

  function confirmTrashTruncate() {
    return confirm('Do you really want to truncate trash?');
  }

  function confirmLogsTruncate() {
    return confirm('Do you really want to truncate logs?');
  }
</script>
