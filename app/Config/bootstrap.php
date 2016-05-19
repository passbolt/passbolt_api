<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

/**
 * Setup a 'default' cache configuration for use in the application.
 */
$commonCache = Configure::read('Cache.Common');
Cache::config('default', $commonCache);

/**
 * App Configuration
 */
Configure::load('version'); // Version
Configure::load('app'); // Application
require_once (APP . 'Controller' . DS . 'Component' . DS . 'Common.php'); // Utility class
require_once (APP . 'Lib' . DS . 'Error' . DS . 'exceptions.php'); // Special Exceptions classes
require_once (APP . 'Lib' . DS . 'Status.php'); // Status constants

/**
 * GPG Keyring
 */
if (Configure::read('GPG.env.setenv')) {
	putenv('GNUPGHOME=' . Configure::read('GPG.env.home'));
}

/**
 * Html purifier.
 */
CakePlugin::load('HtmlPurifier', array('bootstrap' => true));
Purifier::config('nohtml', array(
	'HTML.AllowedElements' => '',
	'Cache.SerializerPath' => APP . 'tmp' . DS . 'purifier',
));

/**
 * File storage plugin for file manipulation.
 */
CakePlugin::load('FileStorage', array(
	'bootstrap' => true
));
CakePlugin::load('Imagine', array(
	'bootstrap' => true
));
CakePlugin::load('EmailQueue');
require_once (APP . 'Config' . DS . 'file_storage.php');

/**
 * Attach event listeners to the request lifecycle as Dispatcher Filter.
 * AssetDispatcher serve asset files (css, images, js, etc) from your themes and plugins
 * CacheDispatcher read the Cache.check configure variable and try to serve cached content generated from controllers
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

/**
 * Testsuite
 */

// Selenium tests config
if (Configure::read('debug') > 0 && Configure::read('App.selenium')) {
	if (file_exists(TMP . DS . 'selenium' . DS . 'core_extra_config.php')) {
		Configure::config('extra_config', new PhpReader(TMP . DS . 'selenium' . DS));
		Configure::load('core_extra_config.php', 'extra_config');
	}
}

// Fixtures
CakePlugin::load('DataDefault');
if (Configure::read('debug') > 0) {
	CakePlugin::load('DataSeleniumTests');
	CakePlugin::load('DataUnitTests');
}

require_once dirname(dirname(__DIR__)) . DS . 'Vendor' . DS . 'autoload.php';

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); // Loads a single plugin named DebugKit
 */

/**
 * To prefer app translation over plugin translation, you can set
 *
 * Configure::write('I18n.preferApp', true);
 */
