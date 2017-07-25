<?php
/**
 * Login layout
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<!doctype html>
<html class="passbolt no-js no-passboltplugin alpha version <?php echo Role::GUEST; ?>" lang="en">
<head>
	<meta charset="utf-8">
<?php echo $this->element('asciiart'); ?>
	<title><?php echo sprintf(Configure::read('App.title'),$this->fetch('title')); ?></title>
<?php echo $this->element('meta'); ?>
<?php echo $this->element('css'); ?>
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
</div>
<?php echo $this->element('footer'); ?>
<?php echo $this->element('scriptBottom'); ?>
<?php
if(Configure::read('App.js.build') === 'production') :
    echo $this->html->script('/js/lib/steal/steal.production.js', [
        'config' => Router::url('/js/stealconfig.js'),
        'main' => 'app/login',
        'env' => 'production'
    ]);
else:
    echo $this->html->script('/js/lib/steal/steal.js', [
        'config' => Router::url('/js/stealconfig.js'),
        'main' => 'app/login',
    ]);
endif;
?>
</body>
</html>
