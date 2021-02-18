<html>
<body>
	<p><?php echo sprintf(lang('email_forgot_password_heading'), $identity);?></p>
	<p><?php echo sprintf(lang('email_forgot_password_subheading'), anchor('/reset_password/index/'. $forgotten_password_code, lang('email_forgot_password_link')));?></p>
</body>
</html>