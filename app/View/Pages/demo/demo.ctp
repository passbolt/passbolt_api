<?php
/**
 * Demo Password Workspace Page 
 *
 * @copyright		 copyright 2013 passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.View.Elements.demo.tree
 * @since				 version 2.13.02
 */
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
	<link href="css/default/reset.css" rel="stylesheet" type="text/css" >
	<link href="css/default/helpers.css" rel="stylesheet" type="text/css" >
	<link href="css/default/typo.css" rel="stylesheet" type="text/css">
	<link href="css/default/colors.css" rel="stylesheet" type="text/css">
	<link href="css/default/icons.css" rel="stylesheet" type="text/css">
	<link href="css/default/buttons.css" rel="stylesheet" type="text/css">
	<link href="css/default/layout.css" rel="stylesheet" type="text/css">
	<link href="css/default/form.css" rel="stylesheet" type="text/css">
	<link href="css/default/header.css" rel="stylesheet" type="text/css">
	<link href="css/default/tree.css" rel="stylesheet" type="text/css">
	<link href="css/default/table.css" rel="stylesheet" type="text/css">
	<link href="css/default/footer.css" rel="stylesheet" type="text/css">
	<link href="css/default/scrollbar.css" rel="stylesheet" type="text/css">
 </style>
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
			<span class="copyright">2012-2013 &copy; bolt software pvt. ltd.</span> &bullet; 
			<a href="#help">help</a> <a href="#privacy">privacy</a> &bullet; <a href="#tos">TOS</a></div>
		</div>
	</div>
</div>
</body>
</html>
