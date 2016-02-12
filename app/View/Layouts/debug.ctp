<?php
/**
 * Debug layout
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<!doctype html>
<html class="passbolt no-js no-passboltplugin alpha version debug <?php echo User::get('Role.name'); ?>" lang="en">
<head>
	<meta charset="utf-8">
	<base href="<?php echo Router::url('/',true);?>">
	<title><?php echo sprintf(Configure::read('App.title'),$this->fetch('title')); ?></title>
	<meta name="robots" content="noindex,nofollow">
	<meta name="viewport" content="width=device-width">
	<?php echo $this->Html->script('lib/jquery/dist/jquery.js'); ?>
	<?php echo $this->html->css('main'); ?>
	<?php if(Configure::read('debug') > 0) echo $this->html->css('devel.min'); ?>
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
