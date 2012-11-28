<?php
/**
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    api_generator.views.layouts
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo __d('api_generator', 'CakePHP: API Generator'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php 
		echo $this->Html->meta('icon');
		echo $this->Html->css('/api_generator/css/base.css');
	?>
	<script type="text/javascript">
		var basePath = "<?php echo $this->base; ?>";
	</script>
	<?php
		echo $this->Html->script('/api_generator/js/mootools');
		echo $this->Html->script('/api_generator/js/api_generator');

		echo $scripts_for_layout;
	?>
</head>
<body class="api">
	<?php $bodyClass = (isset($showSidebar) && $showSidebar) ? 'with-sidebar' : 'no-sidebar'; ?>
	<div id="wrapper" class="<?php echo $bodyClass; ?>">
		<div id="header" class="clearfix">
			<h1><?php echo $this->Html->link(__d('api_generator', 'CakePHP: API Generator'), 'http://cakephp.org'); ?></h1>
			<?php echo $this->element('header_search'); ?>
			<?php echo $this->element('api_menu');?>
		</div>
		<div id="content" class="clearfix">
			<?php echo $this->Session->flash(); ?>
			<div id="content-inner">
				<?php echo $content_for_layout; ?>
			</div>
			<?php if (isset($showSidebar) && $showSidebar): ?>
			<div id="sidebar">
				<?php echo $this->element($sidebarElement)?>
			</div>
			<?php endif; ?>
		</div>
		<div id="footer">
			<?php echo $this->Html->image('cake.power.gif', array(
				'alt'=> __d('api_generator', "CakePHP: the rapid development php framework"), 
				'border' => "0", 
				'url' => 'http:://cakephp.org'
			));
			?>
		</div>
	</div>
</body>
</html>