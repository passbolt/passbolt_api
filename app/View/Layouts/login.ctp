<?php
/**
 * Login layout
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Layouts.login
 * @since         version 2.12.9
 */
?>
<!doctype html>
<html class="no-js no-passboltplugin alpha version" lang="en">
<head>
  <meta charset="utf-8">
  <!--
           ____                  __          ____
          / __ \____  _____ ____/ /_  ____  / / /_
         / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/
        / ____/ /_/ (__  |__  ) /_/ / /_/ / / /_
       /_/    \__,_/____/____/_.___/\____/_/\__/

       The password management solution
       (c) 2014 passbolt.com

   -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<base href="<?php echo Router::url('/',true);?>">
  <title><?php echo $this->fetch('title'); ?></title>
  <title><?php echo $this->fetch('description'); ?></title>
  <meta name="viewport" content="width=device-width">
  <?php echo $this->fetch('css'); ?>
<?php echo $this->element('scriptHeader'); ?>
</head>
<body>
<div class="container login page">
<?php echo $this->element('public/disclaimers'); ?>
<!-- header -->
<header>
<div class="header first ">
<?php echo $this->element('public/topNavigation'); ?>
</div>
</header>
<!-- main -->
<?php echo $this->fetch('content'); ?>
<!-- footer -->
<footer>
<div class="footer">
<?php echo $this->element('public/footer'); ?>
</div>
</footer>
</div>
<?php echo $this->fetch('scriptBottom'); ?>
<?php //echo $this->element('debug');?>
</body>
</html>
