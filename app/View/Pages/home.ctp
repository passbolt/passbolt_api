<?php
/**
 * Home Page
 *
 * @copyright    copyright 2012 passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.Pages.Home
 * @since        version 2.12.7
 */
	$this->assign('title',  __('Password Workspace'));
	$this->Html->script('lib/moment/moment.min.js', array('block' => 'scriptBottom'));
	$this->Html->script('steal/steal.js?app/passbolt.js', array('block' => 'scriptBottom'));
?>
    <div id="js_app_controller" class="container_16"/>

