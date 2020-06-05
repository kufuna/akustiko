<?php
  if($data->error) {
    echo '<div class="nNote nFailure"><p>'.$data->error.'</p></div><br />';
  }
?>

<?php if ($data->seo_enabled): ?>
<form method="post" action="<?= ADMIN_URL.'seo/' ?>" class="main" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-hdr color-success-600">
            <h2>
                Sitemap
            </h2>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <p>
                    <?php if($data->sitemap_exists) { ?>
                    Sitemap last updated: <?= $data->sitemap_updated ?>
                    <?php } else { ?>
                    Sitemap does not exists
                    <?php } ?>
                </p>
                <input type="hidden" name="action" value="generate-sitemap" />
                <button class="btn btn-success mr-2">Generate sitemap</button>
            </div>
        </div>
    </div>
</form>

<form method="post" action="<?= ADMIN_URL.'seo/' ?>" class="main" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-hdr color-success-600">
            <h2>
                General settings
            </h2>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <div class="form-group">
                    <label class="form-label" for="simpleinput">Google verification code:</label>
                    <input type="text" name="google-verification-code" value="<?= $data->google_verification_code ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label" for="example-email-2">Google analytics ID:</label>
                    <input type="text" name="google-analytics-id" value="<?= $data->google_analytics_id ?>" class="form-control">
                </div>
                <input type="hidden" name="action" value="update-settings" />
                <button class="btn btn-success mr-2">Update</button>
            </div>
        </div>
    </div>
</form>
<?php endif ?>

<form method="post" action="<?= ADMIN_URL.'seo/' ?>" class="main" enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-hdr color-success-600">
            <h2>
                Seo Status
            </h2>
        </div>
        <div class="panel-container show">
            <?php if ($data->seo_enabled): ?>
            <div class="panel-content">
                <p>
                    Seo is Enabled
                </p>
                <input type="hidden" name="action" value="disable-seo" />
                <button class="btn btn-danger mr-2">Disable seo</button>
            </div>
            <?php else: ?>
            <div class="panel-content">
                <p>
                    Seo is Disabled
                </p>
                <input type="hidden" name="action" value="enable-seo" />
                <button class="btn btn-success mr-2">Enable seo</button>
            </div>
            <?php endif ?>
        </div>
    </div>
</form>
