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
    $routes->redirect('recover', '/users/recover');
});

/**
 * Home page
 */
Router::scope('/', function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'Pages', 'controller' => 'Home', 'action' => 'view'])
        ->setMethods(['GET']);

    $routes->connect('/home', ['prefix' => 'Pages', 'controller' => 'Home', 'action' => 'view'])
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
 * Comments prefixed routes
 */
Router::prefix('Comments', function ($routes) {
	$routes->setExtensions(['json']);

	$routes->connect('/:model/:id', ['controller' => 'CommentsView', 'action' => 'view'])
		->setPass(['model', 'id'])
		->setMethods(['GET']);

	$routes->connect('/resource/:foreignId', ['controller' => 'CommentsAdd', 'action' => 'addPost'])
	       ->setPass(['foreignId'])
	       ->setMethods(['POST']);

    $routes->connect('/:commentId', ['controller' => 'CommentsUpdate', 'action' => 'update'])
        ->setPass(['commentId'])
        ->setMethods(['PUT']);

    $routes->connect('/:commentId', ['controller' => 'CommentsDelete', 'action' => 'delete'])
        ->setPass(['commentId'])
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
 * Groups prefixed routes
 */
Router::prefix('Groups', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['controller' => 'GroupsIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/:id', ['controller' => 'GroupsView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);

    $routes->connect('/:id', ['controller' => 'GroupsDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    $routes->connect('/:id/dry-run', ['controller' => 'GroupsDelete', 'action' => 'dryRun'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    $routes->connect('/', ['controller' => 'GroupsAdd', 'action' => 'addPost'])
        ->setMethods(['POST']);

    $routes->connect('/:id/dry-run', ['controller' => 'GroupsUpdate', 'action' => 'dryRun'])
        ->setPass(['id'])
        ->setMethods(['PUT']);

    $routes->connect('/:id', ['controller' => 'GroupsUpdate', 'action' => 'update'])
        ->setPass(['id'])
        ->setMethods(['PUT']);
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
 * Permissions prefixed routes
 */
Router::prefix('Permissions', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/resource/:acoForeignKey', ['controller' => 'PermissionsView', 'action' => 'viewAcoPermissions'])
        ->setPass(['acoForeignKey'])
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

    $routes->connect('/', ['controller' => 'ResourcesAdd', 'action' => 'add'])
        ->setMethods(['POST']);

    $routes->connect('/:id', ['controller' => 'ResourcesUpdate', 'action' => 'update'])
        ->setPass(['id'])
        ->setMethods(['PUT']);

    $routes->connect('/:id', ['controller' => 'ResourcesDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);
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
 * Share prefixed routes
 */
Router::prefix('Share', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/search-users/resource/:acoForeignKey', ['controller' => 'ShareSearch', 'action' => 'searchArosToShareWith'])
        ->setPass(['acoForeignKey'])
        ->setMethods(['GET']);

    $routes->connect('/simulate/resource/:acoForeignKey', ['controller' => 'Share', 'action' => 'dryRun'])
        ->setPass(['acoForeignKey'])
        ->setMethods(['POST']);

    $routes->connect('/resource/:acoForeignKey', ['controller' => 'Share', 'action' => 'share'])
        ->setPass(['acoForeignKey'])
        ->setMethods(['PUT']);
});

/**
 * Users prefixed routes
 */
Router::prefix('Users', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['controller' => 'UsersIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/', ['controller' => 'UsersAdd', 'action' => 'addPost'])
        ->setMethods(['POST']);

    $routes->connect('/register', ['controller' => 'UsersRegister', 'action' => 'registerGet'])
        ->setPass(['id'])
        ->setMethods(['GET']);

    $routes->connect('/register', ['controller' => 'UsersRegister', 'action' => 'registerPost'])
        ->setPass(['id'])
        ->setMethods(['POST']);

    $routes->connect('/recover', ['controller' => 'UsersRecover', 'action' => 'recoverGet'])
        ->setPass(['id'])
        ->setMethods(['GET']);

    $routes->connect('/recover', ['controller' => 'UsersRecover', 'action' => 'recoverPost'])
        ->setPass(['id'])
        ->setMethods(['POST']);

    $routes->connect('/:id', ['controller' => 'UsersView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);

    $routes->connect('/:id', ['controller' => 'UsersEdit', 'action' => 'editPost'])
        ->setPass(['id'])
        ->setMethods(['PUT', 'POST']);

    $routes->connect('/:id/dry-run', ['controller' => 'UsersDelete', 'action' => 'dryrun'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    $routes->connect('/:id', ['controller' => 'UsersDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    // @TODO remove deprated legacy v1 backward compatibility routes
    $routes->connect('/validateAccount/:userId', ['prefix' => 'Setup', 'controller' => 'SetupComplete', 'action' => 'complete'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);

    $routes->connect('/avatar', ['controller' => 'UsersAvatar', 'action' => 'complete'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);

    Router::connect('/users/avatar/*', array('controller' => 'users', 'action' => 'editAvatar', '[method]' => 'POST'));
});

/**
 * Setup routes
 */
Router::prefix('/setup', function ($routes) {
    $routes->setExtensions(['json']);

    // new routes
    $routes->connect('/start/:userId/:tokenId', ['prefix' => 'Setup', 'controller' => 'SetupStart', 'action' => 'start'])
        ->setPass(['userId', 'tokenId'])
        ->setMethods(['GET']);

    $routes->connect('/complete/:userId', ['prefix' => 'Setup', 'controller' => 'SetupComplete', 'action' => 'complete'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);

    $routes->connect('/recover/start/:userId/:tokenId', ['prefix' => 'Setup', 'controller' => 'RecoverStart', 'action' => 'start'])
        ->setPass(['userId', 'tokenId'])
        ->setMethods(['GET']);

    $routes->connect('/recover/complete/:userId', ['prefix' => 'Setup', 'controller' => 'RecoverComplete', 'action' => 'complete'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);

    // @TODO remove deprated legacy v1 backward compatibility routes
    $routes->connect('/install/:userId/:tokenId', ['prefix' => 'Setup', 'controller' => 'SetupStart', 'action' => 'start'])
        ->setPass(['userId', 'tokenId'])
        ->setMethods(['GET']);

    $routes->connect('/recover/:userId/:tokenId', ['prefix' => 'Setup', 'controller' => 'RecoverStart', 'action' => 'start'])
        ->setPass(['userId', 'tokenId'])
        ->setMethods(['GET']);

    $routes->connect('/completeRecovery/:userId', ['prefix' => 'Setup', 'controller' => 'RecoverComplete', 'action' => 'complete'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);

});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
