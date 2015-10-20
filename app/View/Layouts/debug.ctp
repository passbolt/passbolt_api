<?php
/**
 * Debug layout
 *
 * @copyright     copyright 2015 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Layouts.debug
 * @since         version 2.15.9
 */
?>
<!doctype html>
<html class="no-js no-passboltplugin alpha version debug <?php echo User::get('Role.name'); ?>" lang="en">
<head>
	<meta charset="utf-8">
	<base href="<?php echo Router::url('/',true);?>">
	<title><?php echo sprintf(Configure::read('App.title'),$this->fetch('title')); ?></title>
	<meta name="robots" content="noindex,nofollow">
	<meta name="viewport" content="width=device-width">
	<?php echo $this->Html->script('lib/jquery/dist/jquery.js'); ?>
	<?php echo $this->html->css('main'); ?>
	<?php echo $this->html->css('devel'); ?>
</head>
<body>
<div id="container" class="debug page">
<?php
	echo $this->fetch('content');
	if (Configure::Read('debug') >= 2){
		echo $this->element('devel/sqlTrace');
	}
?>
</body>
</html>
