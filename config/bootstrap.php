<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$isCli = PHP_SAPI === 'cli';

/*
 * Configure paths required to find CakePHP + general filepath
 * constants
 */
require __DIR__ . '/paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Error\ConsoleErrorHandler;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Security;

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
    Configure::load('default', 'default', false); // passbolt default config
    if (\file_exists(CONFIG . DS . 'passbolt.php')) {
        Configure::load('passbolt', 'default', true); // merge with default config

         // Deduplicate multiple from address for email
         // Can happen if from is also set as array in passbolt.php
        $from = Configure::read('Email.default.from');
        if (isset($from) && is_array($from) && count($from) > 1) {
            Configure::write('Email.default.from', array_slice($from, -1, count($from))); // pick the last one
        }
    }
    Configure::load('version', 'default', true);
} catch (\Exception $e) {
    // let cli handle issues
    if (!$isCli) {
        exit($e->getMessage() . "\n");
    }
}

/**
 * Overwrite these paths. This is a helper to ensure CakePHP3 to 4 retro-compatibility
 * It will also be helpful if we ever have multiple plugin directories. Same goes for locales.
 */
Configure::write('App.paths', [
    'plugins' => [ROOT . DS . 'plugins' . DS],
    'templates' => [ROOT . DS . 'templates' . DS],
    'locales' => [RESOURCES . 'locales' . DS],
]);

/*
 * Load an environment local configuration file.
 * You can use a file like app_local.php to provide local overrides to your
 * shared configuration.
 */
//Configure::load('app_local', 'default');

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
    Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/*
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 */
date_default_timezone_set('UTC');

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', 'en_UK');

/*
 * Register application error and exception handlers.
 */
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

/*
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 */
$fullBaseUrl = Configure::read('App.fullBaseUrl');
if (!$fullBaseUrl) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        $fullBaseUrl = 'http' . $s . '://' . $httpHost;
    }
    unset($httpHost, $s);
}
if ($fullBaseUrl) {
    Router::fullBaseUrl($fullBaseUrl);
}
unset($fullBaseUrl);

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Mailer::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * Setup detectors for mobile and tablet.
 */
//ServerRequest::addDetector('mobile', function ($request) {
//    $detector = new \Detection\MobileDetect();
//    return $detector->isMobile();
//});
//ServerRequest::addDetector('tablet', function ($request) {
//    $detector = new \Detection\MobileDetect();
//    return $detector->isTablet();
//});

/*
 * Enable immutable time objects in the ORM.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link http://book.cakephp.org/3.0/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
Type::build('time')->useImmutable();
Type::build('date')->useImmutable();
Type::build('datetime')->useImmutable();
Type::build('timestamp')->useImmutable();

/*
 * Set process user constant
 */
$uid = posix_getuid();
$user = posix_getpwuid($uid);
define('PROCESS_USER', $user['name']);

// Are we running passbolt pro?
define('PASSBOLT_PRO', Configure::read('passbolt.edition') === 'pro');
