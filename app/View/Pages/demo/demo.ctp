<?php
/**
 * Demo Password Workspace Page 
 *
 * @copyright		 copyright 2013 passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.View.Elements.demo.tree
 * @since				 version 2.13.02
 */
	$this->layout = 'demo';
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>		<html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>		<html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Password Workspace Static Demo</title>
	<base href="<?php echo Router::url('/',true);?>">
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<link href="css/default/main.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/lib/compat/modernizr-2.6.2.min.js"></script>
	<script type="text/javascript" src="js/lib/jquery/jquery-1.11.0.min.js"></script>
</head>
<body>
<div id="container" class="page">
<?php echo $this->element('demo/loading-screen'); ?>
<?php echo $this->element('demo/loading-bar'); ?>
<?php echo $this->element('demo/notification'); ?>
<?php echo $this->element('demo/contextual-menu'); ?>
<?php echo $this->element('demo/dialog'); ?>
	<div class="header first">
<?php echo $this->element('demo/nav'); ?>
	</div>
	<div class="header second">
<?php echo $this->element('demo/header'); ?>
	</div>
	<div class="header third">
<?php echo $this->element('demo/actions'); ?>
	</div>
	<div class="panel main">
		<div class="panel left">
<?php echo $this->element('demo/tree'); ?>
		</div>
		<div class="panel middle">
<?php echo $this->element('demo/breadcrumb'); ?>
<?php echo $this->element('demo/table'); ?>
		</div>
		<div class="panel aside">
<?php echo $this->element('demo/sidebar'); ?>
		</div>
	</div>
	<div class="footer">
<?php echo $this->element('demo/footer'); ?>
	</div>
</div>
<script>
	// if there is not css styling for scrollbar
	// uses jScrollPane with mousewheel
	Modernizr.load({
		test: Modernizr.cssscrollbar,
		nope: [
			'js/lib/jquery/jquery-1.11.0.min.js',
			'js/lib/compat/jquery.mousewheel.js',
			'js/lib/compat/jquery.jscrollpane.min.js'
		],
		complete : function() {
			if (!Modernizr.cssscrollbar) {
				var settings = {
					showArrows: false,
					autoReinitialise: true
				};
				$('.scroll').jScrollPane(settings);
			}
		}
	});
</script>
<script type="text/javascript" src="js/pages/demo.js"></script>
</body>
</html>
