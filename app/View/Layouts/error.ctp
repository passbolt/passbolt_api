<?php
/**
 * Login layout
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<!doctype html>
<html class="passbolt no-js no-passboltplugin alpha version>" lang="en">
<head>
	<meta charset="utf-8">
<?php echo $this->element('asciiart'); ?>
	<title><?php echo sprintf(Configure::read('App.title'),$this->fetch('title')); ?></title>
<?php echo $this->element('meta'); ?>
	<?php $this->Html->css('main.min', null, array('inline' => false)); ?>
<?php echo $this->element('css'); ?>
<?php echo $this->element('scriptHeader'); ?>
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
</div>
<?php echo $this->element('footer'); ?>
<?php echo $this->fetch('scriptBottom'); ?>
<?php
// load devel materials.
if(Configure::read('debug') >= 2) {
	echo $this->element('devel/sqlTrace');
}
?>
</body>
</html>
