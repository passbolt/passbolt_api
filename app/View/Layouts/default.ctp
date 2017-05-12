<?php
/**
 * HTML5 layout
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<!doctype html>
<html class="passbolt no-js alpha version launching no-passboltplugin <?php echo User::get('Role.name'); ?>" lang="en">
<head>
	<meta charset="utf-8">
	<?php echo $this->element('asciiart'); ?>
	<title><?php echo sprintf(Configure::read('App.title'), $this->fetch('title')); ?></title>
	<?php echo $this->element('meta'); ?>
	<?php echo $this->element('css'); ?>
	<?php echo $this->element('scriptHeader'); ?>
</head>
<body>
<!-- main -->
<div id="container" class="page <?php echo $this->fetch('page_classes') ?>">
	<?php echo $this->fetch('header'); ?>
	<?php echo $this->fetch('content'); ?>
</div>
<?php echo $this->element('footer'); ?>
<?php echo $this->element('scriptBottom'); ?>
</body>
</html>
