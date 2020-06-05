<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>CONNECT CMS</title>
        <base href="<?= ROOT_URL.'admin_resources/' ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="msapplication-tap-highlight" content="no">
        <!-- base css -->
        <link rel="stylesheet" media="screen, print" href="v2/css/vendors.bundle.css">
        <link rel="stylesheet" media="screen, print" href="v2/css/app.bundle.css">
        <link rel="icon" type="image/png" href="<?= ROOT_URL ?>img/favicon.ico"/>
        <link rel="stylesheet" media="screen, print" href="v2/css/page-login.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <div class="blankpage-form-field login-page">
            <?php if(isset($data->error) and $data->error) { ?>
                <div class="alert alert-primary alert-dismissible">
                    <div class="d-flex flex-start w-100" style="align-items: center;">
                        <div class="mr-2 d-sm-none d-md-block">
                            <span class="icon-stack icon-stack-lg">
                                <i class="base base-6 icon-stack-3x opacity-100 color-primary-500"></i>
                                <i class="base base-10 icon-stack-2x opacity-100 color-primary-300 fa-flip-vertical"></i>
                                <i class="fal fa-info icon-stack-1x opacity-100 color-white"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-fill">
                            <div class="flex-fill">
                                <div><?= $data->error ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">
                <form action="<?= ADMIN_URL.'login' ?>" method="post">
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" id="username" class="form-control" placeholder="your username" name="username">
                        <span class="help-block">
                            Your unique username to app
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" class="form-control" placeholder="password" name="pass">
                        <span class="help-block">
                            Your password
                        </span>
                    </div>
                    <div class="form-group">
                      <div class="g-recaptcha" data-sitekey="<?=CAPTCHA_PUBLIC_KEY?>"></div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-default float-right">Login</button>
                </form>
            </div>
        </div>

        <script src="v2/js/vendors.bundle.js"></script>
        <script src="v2/js/app.bundle.js"></script>
    </body>
</html>