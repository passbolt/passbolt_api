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
 * @since         3.3.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->scope('/', function (RouteBuilder $routes) {
    $routes->redirect('.well-known/jwks.json', '/auth/jwt/jwks.json');
});

$routes->plugin('Passbolt/JwtAuthentication', ['path' => '/auth/jwt'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    // WARNING - if you add routes, check whether it should be included in
    // JwtRequestDetectionService::useJwtAuthentication / isJwtLoginRoute

    $routes->connect('/rsa', ['controller' => 'Jwks', 'action' => 'rsa'])
        ->setMethods(['GET']);

    $routes->connect('/jwks', ['controller' => 'Jwks', 'action' => 'jwks'])
        ->setMethods(['GET']);

    $routes->connect('/login', ['controller' => 'JwtLogin', 'action' => 'loginPost'])
        ->setMethods(['POST']);

    $routes->connect('/refresh', ['controller' => 'RefreshToken', 'action' => 'refreshPost'])
        ->setMethods(['POST']);

    $routes->connect('/logout', ['controller' => 'JwtLogout', 'action' => 'logoutPost'])
        ->setMethods(['POST']);
});
