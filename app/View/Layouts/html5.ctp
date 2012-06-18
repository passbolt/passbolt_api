<?php
/**
 * HTML5 Boiler plate layout
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.View.Layouts
 * @since         version 2.12.6
 * @license       http://www.passbolt.com/license
 */
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php echo $title_for_layout; ?></title>
  <base href="<?php echo Router::Url(null,true); ?>"> 
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/jquery/ui-lightness/jquery-ui-1.8.20.custom.css" />
<?php echo $this->fetch('css'); ?>
  <script src="js/compat/modernizr-2.5.3.min.js"></script>
</head>
<body>
<!-- header -->
<header>
<?php echo $this->fetch('header'); ?>
</header>
<!-- main -->
<div role="main" id="container">
<?php echo $this->fetch('content'); ?>
</div>
<!-- footer -->
<footer>
<?php echo $this->fetch('footer'); ?>
<small><?php echo 'v.'.Configure::read('App.version.number'); ?></small>
</footer>
<?php echo $this->element('script'); ?>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>
