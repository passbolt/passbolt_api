<?php
/**
 * Home Page
 *
 * @copyright    copyright 2012 passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.Pages.Home
 * @since        version 2.12.7
 */
	$this->assign('title', __('Passbolt - The simple password management system'));

	$this->Html->script('lib/can/lib/jquery.1.8.3.js', array('block' => 'scriptBottom'));
	$this->Html->script('lib/xregexp/xregexp-all-min.js', array('block' => 'scriptBottom'));
	$this->Html->script('lib/jquery/jquery-ui-1.10.3.custom.js', array('block' => 'scriptBottom'));
	$this->Html->script('lib/moment/moment.min.js', array('block' => 'scriptBottom'));
	$this->Html->css('main', null, array('inline' => false));

	// load the front end application
	if(!Configure::read('debug')) {
		$frontUri = 'steal/steal.production.js?app/passbolt.js';
	} else {
		$frontUri = 'steal/steal.js?app/passbolt.js';
	}

	// load devel materials.
	if(Configure::read('debug')) {
		$this->Html->css('devel', null, array('inline' => false));
	}

	$this->Html->script($frontUri, array('block' => 'scriptBottom'));
?>
<?php echo $this->element('loader'); ?>
<div id="js_app_controller">
</div>
