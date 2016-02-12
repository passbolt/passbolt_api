<?php
/**
 * Login layout
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<!doctype html>
<html class="passbolt no-js no-passboltplugin alpha version <?php echo User::get('Role.name'); ?>" lang="en">
<head>
	<meta charset="utf-8">
	<!-- hey yo
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
<?php if(Configure::read('debug') > 0) echo $this->html->css('devel.min'); ?>
<?php echo $this->element('scriptHeader'); ?>
</head>
<body>
<div id="container" class="page <?php echo $this->fetch('page_classes') ?>">
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
<?php echo $this->element('analytics/piwik'); ?>
<?php
// load devel materials.
if(Configure::read('debug') >= 2) {
	echo $this->element('devel/sqlTrace');
}
?>
</body>
</html>
