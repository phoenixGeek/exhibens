<html>

<body>
	<p>Hello,</p>
	<p>Thank you for registering.</p>
	<p><?php echo sprintf(lang('email_activate_subheading'), anchor('auth/activate/' . $id . '/' . $activation, lang('email_activate_link'))); ?></p>
</body>

</html>