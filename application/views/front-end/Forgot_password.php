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
						Forgot your password ?
					</span>
				</div>
				<?php if ($flash_message) { ?>
					<div class="alert alert-primary alert-dismissible fade show" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
						<?= $flash_message ?>
					</div>
					

				<?php } ?>

				<?= form_open("Forgot_password", array("class" => "login100-form validate-form")) ?>
				<?php
				$token_name = $this->security->get_csrf_token_name();
				$token_hash = $this->security->get_csrf_hash();
				?>

				<input type="hidden" id="csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />

				<div class="wrap-input100 validate-input m-b-26" data-validate="Email is required">
					<span class="label-input100">Enter Your email</span>
					<?php if (form_error('email')) {
						echo "<i class='validation-error'>" . form_error('email') . "</i>";
					}
					?>
					<?= form_input($email) ?>
					<span class="focus-input100"></span>
				</div>

				<div class="container-login100-form-btn">
					<?= form_submit('submit', lang('forgot_password_submit_btn'), array("class" => "login100-form-btn")) ?>
				</div>
				</form>
			</div>
		</div>
	</div>

	<script src="<?= base_url('assets/login/vendor/jquery/jquery-3.2.1.min.js') ?>"></script>
	<script src="<?= base_url('assets/login/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/login/vendor/select2/select2.min.js') ?>"></script>
	<script src="<?= base_url('assets/login/js/main.js') ?>"></script>

</body>

</html>