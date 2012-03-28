<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */
Inflector::rules('plural', array('rules' => array('/password_history/i'=>'passwords_history'), 'irregular' => array(), 'uninflected' => array()));
Inflector::rules('singular', array('rules' => array('/passwords_history/i'=>'password_history'), 'irregular' => array(), 'uninflected' => array()));



/*************** PASSBOLT CUSTOM SETTINGS **************/
	
Configure::write('db_prefix', 'pb_');

/**
 * install_mode is the type of installation
 * The software can be either used as saas or in standalone
 * 2 options :
 * 'saas' : the software is used by many clients, each client accessing his app with a url like name.passbolt.com
 * 'standalone' : the software is installed locally on a server. Only one app, the same, for everybody
 */
Configure::write('install_mode', 'saas');
	
if(Configure::read('install_mode') == 'saas'){
	// Load domain-specific config
	if ( isset($_SERVER['SERVER_NAME']) ) { // web
		preg_match('@^(.+)\.{1}(passbolt).*@i', $_SERVER['SERVER_NAME'], $matches);
		$appid = $matches[1];
	    $bootstrap = CONFIGS .'domains'.DS.$appid.'.php';
	} elseif ( count($_SERVER['argv']) ) { // cli
	    $appid = $_SERVER['argv'][count($_SERVER['argv'])-1];
	    $bootstrap = CONFIGS .'domains'.DS.$appid.'.php';
	}
	

	Configure::write('appid', $appid);
	
	// At the bottom you can override configurations if you found you had to.
	// This is also where you define defaults for constants. (keeps the if out of the domain file)
	if ( !defined('CLIENT_NAME') ) {
	    define('CLIENT_NAME', 'No Client');
	} 
}




