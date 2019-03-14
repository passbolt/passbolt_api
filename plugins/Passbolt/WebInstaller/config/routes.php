<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
use Cake\Routing\Router;

/**
 * Selenium tests routes
 */
Router::plugin('Passbolt/WebInstaller', ['path' => '/install'], function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['controller' => 'GettingStarted', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/system_check', ['controller' => 'SystemCheck', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/license_key', ['controller' => 'LicenseKey', 'action' => 'index'])
        ->setMethods(['GET', 'POST']);

    $routes->connect('/database', ['controller' => 'Database', 'action' => 'index'])
        ->setMethods(['GET', 'POST']);

    $routes->connect('/gpg_key', ['controller' => 'GpgKeyGenerate', 'action' => 'index'])
        ->setMethods(['GET', 'POST']);

    $routes->connect('/gpg_key_import', ['controller' => 'GpgKeyImport', 'action' => 'index'])
        ->setMethods(['GET', 'POST']);

    $routes->connect('/email', ['controller' => 'Email', 'action' => 'index'])
        ->setMethods(['GET', 'POST']);

    $routes->connect('/options', ['controller' => 'Option', 'action' => 'index'])
        ->setMethods(['GET', 'POST']);

    $routes->connect('/installation', ['controller' => 'Installation', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/installation/do_install', ['controller' => 'Installation', 'action' => 'install'])
        ->setMethods(['GET']);

    $routes->connect('/account_creation', ['controller' => 'AccountCreation', 'action' => 'index'])
        ->setMethods(['GET', 'POST']);
});
