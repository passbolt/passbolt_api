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
	$this->Html->script('lib/moment/moment.min.js', array('block' => 'scriptBottom'));
	$this->Html->css('default/main.min', null, array('inline' => false));
	// load the front end application
	if(!Configure::read('debug')) {
		$frontUri = 'steal/steal.production.js?app/passbolt.js';
	} else {
		$frontUri = 'steal/steal.js?app/passbolt.js';
	}
	$this->Html->script($frontUri, array('block' => 'scriptBottom'));
?>
    <div id="js_app_controller" class="container_16"/>

