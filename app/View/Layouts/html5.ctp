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
<html class="passbolt no-js alpha version launching no-passboltplugin <?php echo User::get('Role.name'); ?>" lang="en">
<head>
  <meta charset="utf-8">
  <!--
           ____                  __          ____
          / __ \____  _____ ____/ /_  ____  / / /_
         / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/
        / ____/ /_/ (__  |__  ) /_/ / /_/ / / /_
       /_/    \__,_/____/____/_.___/\____/_/\__/

       The password management solution
       (c) 2015 passbolt.com

   -->
  <base href="<?php echo Router::url('/',true);?>">
	<title><?php echo sprintf(Configure::read('App.title'),$this->fetch('title')); ?></title>
	<meta name="description" content="<?php echo Configure::read('App.punchline'); ?>">
	<meta name="keywords" content="Passbolt, password manager, online password manager, open source password manager">
	<meta name="viewport" content="width=device-width">
<?php echo $this->fetch('css'); ?>
<?php if(Configure::read('debug') > 0) echo $this->html->css('devel'); ?>
<?php echo $this->element('scriptHeader'); ?>
</head>
<body>
<!-- main -->
<div id="container" class="page <?php echo $this->fetch('page_classes') ?>">
	<?php echo $this->fetch('content'); ?>
</div>
<!-- footer -->
<footer>
<div class="footer">
<?php echo $this->element('public/footer'); ?>
</div>
</footer>
<?php echo $this->fetch('scriptBottom'); ?>
<?php echo $this->element('analytics/piwik'); ?>
<?php if(Configure::read('App.js.build') === 'production') : ?>
<script type="text/javascript" src="/js/lib/steal/steal.production.js" config="js/stealconfig.js" main="app/passbolt" env="production"></script>
<?php else: ?>
<script type="text/javascript" src="/js/lib/steal/steal.js" config="js/stealconfig.js" main="passbolt"></script>
<?php endif; ?>
<?php
// load devel materials.
if(Configure::read('debug') >= 2) {
	echo $this->element('devel/sqlTrace');
}
?>
</body>
</html>
