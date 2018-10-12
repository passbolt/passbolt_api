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
 * @since         2.5.0
 */
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin('Passbolt/MultiFactorAuthentication', ['path' => '/mfa'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/setup/totp/start', ['prefix' => 'Totp', 'controller' => 'TotpSetupGet', 'action' => 'start'])
        ->setMethods(['GET']);

    $routes->connect('/setup/totp', ['prefix' => 'Totp', 'controller' => 'TotpSetupGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/setup/totp', ['prefix' => 'Totp', 'controller' => 'TotpSetupPost', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/setup/totp', ['prefix' => 'Totp', 'controller' => 'TotpSetupDelete', 'action' => 'delete'])
        ->setMethods(['DELETE']);

    $routes->connect('/verify/totp', ['prefix' => 'Totp', 'controller' => 'TotpVerifyGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/verify/totp', ['prefix' => 'Totp', 'controller' => 'TotpVerifyPost', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/verify/error', ['controller' => 'MfaVerifyAjaxError', 'action' => 'get'])
        ->setMethods(['GET', 'POST', 'PUT', 'DELETE']);
});
