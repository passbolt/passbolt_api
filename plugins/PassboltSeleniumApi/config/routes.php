<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
use Cake\Routing\Router;

/**
 * Selenium tests routes
 */
Router::plugin('PassboltSeleniumApi', ['path' => '/seleniumtests'], function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/resetInstance/:dataset', ['controller' => 'ResetInstance', 'action' => 'resetInstance'])
        ->setPass(['dataset'])
        ->setMethods(['GET']);

    $routes->connect('/config', ['controller' => 'Config', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/setExtraConfig', ['controller' => 'Config', 'action' => 'setExtraConfig'])
        ->setMethods(['POST']);

    $routes->connect('/resetExtraConfig', ['controller' => 'Config', 'action' => 'resetExtraConfig'])
        ->setMethods(['GET']);

    $routes->connect('/error400', ['controller' => 'SimulateError', 'action' => 'error400'])
        ->setMethods(['GET']);

    $routes->connect('/error404', ['controller' => 'SimulateError', 'action' => 'error404'])
        ->setMethods(['GET']);

    $routes->connect('/error403', ['controller' => 'SimulateError', 'action' => 'error403'])
        ->setMethods(['GET']);

    $routes->connect('/error500', ['controller' => 'SimulateError', 'action' => 'error500'])
        ->setMethods(['GET']);

    $routes->connect('/showlastemail/:username', ['controller' => 'Email', 'action' => 'showLastEmail'])
        ->setPass(['username'])
        ->setMethods(['GET']);

    // Legacy v1 backward compatibility routes
    $routes->connect('/showLastEmail/:username', ['controller' => 'Email', 'action' => 'showLastEmail'])
        ->setPass(['username'])
        ->setMethods(['GET']);
});
