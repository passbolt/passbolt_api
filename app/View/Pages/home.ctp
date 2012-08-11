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
//	$this->Html->css('jquery/ui-lightness/jquery-ui-1.8.20.custom.css', null, array('block' => 'css'));
	$this->Html->css('grid/960.css', null, array('block' => 'css'));
	$this->Html->css('passbolt.css', null, array('block' => 'css'));
	$this->Html->script('lib/moment/moment.min.js', array('block' => 'scriptBottom'));
	$this->Html->script('steal/steal.js?app/passbolt.js', array('block' => 'scriptBottom'));
?>
    <div id="js_app_controller" class="container_16"/>
