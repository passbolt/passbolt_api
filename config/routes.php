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
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 */
Router::defaultRouteClass(DashedRoute::class);

/**
 * Shorthands and legacy redirect
 */
Router::scope('/', function (RouteBuilder $routes) {
    $routes->redirect('register', '/users/register');
});


/**
 * Home page
 */
Router::scope('/', function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);
    $routes->connect('/', ['prefix' => 'Home', 'controller' => 'Home', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/home', ['prefix' => 'Home', 'controller' => 'Home', 'action' => 'index'])
        ->setMethods(['GET']);
});

/**
 * Users prefixed routes
 */
Router::prefix('Users', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['controller' => 'UsersIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/register', ['controller' => 'UsersRegister', 'action' => 'registerGet'])
        ->setPass(['id'])
        ->setMethods(['GET']);

    $routes->connect('/register', ['controller' => 'UsersRegister', 'action' => 'registerPost'])
        ->setPass(['id'])
        ->setMethods(['POST']);

    $routes->connect('/:id', ['controller' => 'UsersView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);
});

/**
 * Roles prefixed routes
 */
Router::prefix('Roles', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['controller' => 'RolesIndex', 'action' => 'index'])
        ->setMethods(['GET']);
});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
