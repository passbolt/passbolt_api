<?php
/**
 * Login layout
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<!doctype html>
<html class="passbolt no-js no-passboltplugin alpha version <?php echo User::get('Role.name'); ?>" lang="en">
<head>
	<meta charset="utf-8">
<?php echo $this->element('asciiart'); ?>
	<base href="<?php echo Router::url('/',true);?>">
	<title><?php echo sprintf(Configure::read('App.title'),$this->fetch('title')); ?></title>
	<meta name="description" content="<?php echo Configure::read('App.punchline'); ?>">
	<meta name="keywords" content="Passbolt, password manager, online password manager, open source password manager">
	<meta name="viewport" content="width=device-width">
	<?php $this->Html->css('main.min', null, array('inline' => false)); ?>
	<?php echo $this->fetch('css'); ?>
	<?php echo $this->element('scriptHeader'); ?>
	<?php if(Configure::read('debug') > 0) echo $this->html->css('devel.min'); ?>
</head>
<body>
<div id="container" class="error page <?php echo $this->fetch('page_classes') ?>">
	<?php echo $this->element('public/disclaimers'); ?>
	<!-- header -->
	<header>
		<div class="header first ">
			<?php echo $this->element('public/topNavigation'); ?>
		</div>
	</header>
	<!-- main -->
	<div class="grid">
		<div class="row">
	<?php echo $this->fetch('content'); ?>
		</div>
	</div>

	<!-- footer -->
	<footer>
		<div class="footer">
			<?php echo $this->element('public/footer'); ?>
		</div>
	</footer>
</div>
<?php echo $this->fetch('scriptBottom'); ?>
<?php
// load devel materials.
if(Configure::read('debug') >= 2) {
	echo $this->element('devel/sqlTrace');
}
?>
</body>
</html>
