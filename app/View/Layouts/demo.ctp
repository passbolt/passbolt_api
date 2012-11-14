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
       (c) 2012 passbolt.com

   -->
  <base href="<?php echo Router::url('/',true);?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width">
<?php echo $this->element('css'); ?>
<?php echo $this->element('scriptHeader'); ?>
</head>
<body>
<!-- footer -->
<header>
<div class="header">
<?php echo $this->element('header'); ?>
</div>
</header>
<!-- main -->
<div role="main" id="container">
<?php echo $this->fetch('content'); ?>
</div>
<!-- footer -->
<footer>
<div class="footer">
<?php echo $this->element('footer'); ?>
</div>
</footer>
<?php echo $this->fetch('scriptBottom'); ?>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>
