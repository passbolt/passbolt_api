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
    $routes->redirect('login', '/auth/login');
    $routes->redirect('users/login', '/auth/login');
    $routes->redirect('logout', '/auth/logout');
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
 * Authentication routes
 */
Router::scope('/auth', function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/login', ['prefix' => 'Auth', 'controller' => 'AuthLogin', 'action' => 'loginGet'])
        ->setMethods(['GET']);

    $routes->connect('/login', ['prefix' => 'Auth', 'controller' => 'AuthLogin', 'action' => 'loginPost'])
        ->setMethods(['POST']);

    $routes->connect('/verify', ['prefix' => 'Auth', 'controller' => 'AuthVerify', 'action' => 'verifyGet'])
        ->setMethods(['GET']);

    $routes->connect('/verify', ['prefix' => 'Auth', 'controller' => 'AuthLogin', 'action' => 'loginPost'])
        ->setMethods(['POST']);

    $routes->connect('/checksession', ['prefix' => 'Auth', 'controller' => 'AuthCheckSession', 'action' => 'checkSessionGet'])
        ->setMethods(['GET']);

    $routes->connect('/checkSession', ['prefix' => 'Auth', 'controller' => 'AuthCheckSession', 'action' => 'checkSessionGet'])
        ->setMethods(['GET']);

    $routes->connect('/logout', ['prefix' => 'Auth', 'controller' => 'AuthLogout', 'action' => 'logoutGet'])
        ->setMethods(['GET']);
});

/**
 * Favorites prefixed routes
 */
Router::prefix('Favorites', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/resource/:foreignId', ['controller' => 'FavoritesAdd', 'action' => 'add'])
        ->setPass(['foreignId'])
        ->setMethods(['POST']);

    $routes->connect('/:id', ['controller' => 'FavoritesDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);
});

/**
 * Gpgkeys prefixed routes
 */
Router::prefix('/gpgkeys', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'gpgkeys', 'controller' => 'GpgkeysIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/:id', ['prefix' => 'gpgkeys', 'controller' => 'GpgkeysView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);
});

/**
 * Healthchecks routes
 */
Router::prefix('/healthcheck', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/status', ['prefix' => 'healthcheck', 'controller' => 'HealthcheckStatus', 'action' => 'status'])
        ->setMethods(['GET']);

    $routes->connect('/', ['prefix' => 'healthcheck', 'controller' => 'HealthcheckIndex', 'action' => 'index'])
        ->setPass(['id'])
        ->setMethods(['GET']);
});

/**
 * Resources prefixed routes
 */
Router::prefix('Resources', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['controller' => 'ResourcesIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/:id', ['controller' => 'ResourcesView', 'action' => 'view'])
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
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
