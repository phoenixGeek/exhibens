<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login V15</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/login/images/icons/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/css/main.css">
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-form-title" style="background-image: url('<?= base_url() ?>assets/login/images/bg-01.jpg');">
                    <span class="login100-form-title-1">
                        Reset Your Password
                    </span>
                </div>
                <?php
                if ($flash_message) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $flash_message ?>
                    </div>

                <?php
                }
                ?>
                <?= form_open("Reset_password/index/" . $code, array("class" => "login100-form validate-form")) ?>
               
                <div class="wrap-input100 validate-input m-b-18" data-validate="Password is required">
                    <span class="label-input100">New Password</span>
                    <?php if (form_error('password')) echo "<i class='validation-error'>" . form_error('password') . "</i>"; ?>
                    <?= form_input($password) ?>
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input m-b-18" data-validate="Password is required">
                    <span class="label-input100">Verify Password</span>
                    <?php if (form_error('password_confirm')) echo "<i class='validation-error'>" . form_error('password_confirm') . "</i>"; ?>
                    <?= form_input($password_confirm) ?>
                    <span class="focus-input100"></span>
                </div>

                <?php echo form_input($user_id); ?>
                <div class="container-login100-form-btn">
                    <?= form_submit('submit', "Change Password", array("class" => "login100-form-btn")) ?>
                    
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= base_url() ?>assets/login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url() ?>assets/login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/login/vendor/select2/select2.min.js"></script>
    <script src="<?= base_url() ?>assets/login/js/main.js"></script>

</body>

</html>