<?php
/**
 * Demo Dialog Logs
 *
 * @copyright		 copyright 2013 passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.View.Elements.demo.dialog.logs
 * @since			 version 2.13.02
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>		<html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>		<html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Passbolt Logs Dialog static demo</title>
	<base href="<?php echo Router::url('/',true);?>">
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<link href="css/default/main.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/lib/compat/modernizr-2.6.2.min.js"></script>
</head>
<body>
<div id="container" class="page">
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
			<?php echo $this->element('demo/table'); ?>
		</div>
	</div>
	<div class="footer">
		<div class="context-info">
			last seen from 127.0.0.1 <a href="#">(more)</a>
		</div>
		<div class="footnotes">
			<span class="copyright">2012-2013 &copy; Passbolt.</span> &bullet;
			<a href="#help">help</a> <a href="#privacy">privacy</a> &bullet; <a href="#tos">TOS</a>
		</div>
	</div>
</div>
<?php echo $this->element('demo/dialog-logs'); ?>
<script>
	// if there is not css styling for scrollbar
	// uses jScrollPane with mousewheel
	Modernizr.load({
		test: Modernizr.cssscrollbar,
		nope: [
			'js/lib/jquery/jquery-1.8.3.js',
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
</body>
</html>
