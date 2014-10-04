<?php
/**
 * HTML5 Boiler plate layout
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Layouts.html5
 * @since         version 2.12.6
 */
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <title><?php echo $this->fetch('title'); ?></title>
  <!--
           ____                  __          ____
          / __ \____  _____ ____/ /_  ____  / / /_
         / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/
        / ____/ /_/ (__  |__  ) /_/ / /_/ / / /_
       /_/    \__,_/____/____/_.___/\____/_/\__/

       The password management solution
       (c) 2014 passbolt.com

   -->
  <base href="<?php echo Router::url('/',true);?>">
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
<?php echo $this->fetch('css'); ?>
<?php echo $this->element('scriptHeader'); ?>
</head>
<body>
<!-- main -->
<div id="container" class="page">
<?php echo $this->fetch('content'); ?>
</div>
<!-- footer -->
<footer>
<div class="footer">
<?php echo $this->element('public/footer'); ?>
</div>
</footer>
<?php echo $this->fetch('scriptBottom'); ?>
<?php
// load devel materials.
if(Configure::read('debug')) {
	echo $this->element('devel/sqlTrace');
}
?>
</body>
</html>
