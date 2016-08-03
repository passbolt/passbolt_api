	<meta name="description" content="<?php echo Configure::read('App.punchline'); ?>">
	<meta name="keywords" content="Passbolt, password manager, online password manager, open source password manager">
	<?php if(Configure::read('App.meta.robots.index') !== true): ?>
	<meta name="robots" content="noindex">
	<?php endif; ?>
	<meta name="viewport" content="width=device-width">
<?php echo $this->fetch('meta'); ?>
