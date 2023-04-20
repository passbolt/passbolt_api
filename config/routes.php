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
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 */
Router::defaultRouteClass(DashedRoute::class);

/** @var RouteBuilder $routes */

/**
 * Shorthands and legacy redirect
 */
$routes->scope('/', function (RouteBuilder $routes) {
    $routes->redirect('auth/register', '/users/register');
    $routes->redirect('register', '/users/register');
    $routes->redirect('login', '/auth/login');
    $routes->redirect('users/login', '/auth/login');
    $routes->redirect('logout', '/auth/logout');
    $routes->redirect('recover', '/users/recover');
});

/**
 * Home page
 */
$routes->scope('/', function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'Pages', 'controller' => 'Home', 'action' => 'apiExtApp'])
        ->setMethods(['GET']);
});

/**
 * Authentication routes
 */
$routes->scope('/auth', function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    // Session based
    $routes->connect('/login', ['prefix' => 'Auth', 'controller' => 'AuthLogin', 'action' => 'loginGet'])
        ->setMethods(['GET']);

    $routes->connect('/login', ['prefix' => 'Auth', 'controller' => 'AuthLogin', 'action' => 'loginPost'])
        ->setMethods(['POST']);

    $routes->connect('/verify', ['prefix' => 'Auth', 'controller' => 'AuthVerify', 'action' => 'verifyGet'])
        ->setMethods(['GET']);

    $routes->connect('/verify', ['prefix' => 'Auth', 'controller' => 'AuthLogin', 'action' => 'loginPost'])
        ->setMethods(['POST']);

    $routes->connect('/is-authenticated', ['prefix' => 'Auth', 'controller' => 'AuthIsAuthenticated', 'action' => 'isAuthenticated'])
        ->setMethods(['GET']);

    $routes->connect('/logout', ['prefix' => 'Auth', 'controller' => 'AuthLogout', 'action' => 'logout'])
        ->setMethods(['GET', 'POST']);
});

/**
 * Favorites prefixed routes
 */
$routes->scope('/favorites', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/resource/{foreignId}', ['prefix' => 'Favorites', 'controller' => 'FavoritesAdd', 'action' => 'add'])
        ->setPass(['foreignId'])
        ->setMethods(['POST']);

    $routes->connect('/{id}', ['prefix' => 'Favorites', 'controller' => 'FavoritesDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);
});

/**
 * Comments prefixed routes
 */
$routes->scope('/comments', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/{model}/{id}', ['prefix' => 'Comments', 'controller' => 'CommentsView', 'action' => 'view'])
        ->setPass(['model', 'id'])
        ->setMethods(['GET']);

    $routes->connect('/resource/{foreignId}', ['prefix' => 'Comments', 'controller' => 'CommentsAdd', 'action' => 'addPost'])
        ->setPass(['foreignId'])
        ->setMethods(['POST']);

    $routes->connect('/{commentId}', ['prefix' => 'Comments', 'controller' => 'CommentsUpdate', 'action' => 'update'])
        ->setPass(['commentId'])
        ->setMethods(['PUT']);

    $routes->connect('/{commentId}', ['prefix' => 'Comments', 'controller' => 'CommentsDelete', 'action' => 'delete'])
        ->setPass(['commentId'])
        ->setMethods(['DELETE']);
});

/**
 * Gpgkeys prefixed routes
 */
$routes->scope('/gpgkeys', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'Gpgkeys', 'controller' => 'GpgkeysIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/{id}', ['prefix' => 'Gpgkeys', 'controller' => 'GpgkeysView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);
});

/**
 * Groups prefixed routes
 */
$routes->prefix('/groups', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'Groups', 'controller' => 'GroupsIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/{id}', ['prefix' => 'Groups', 'controller' => 'GroupsView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);

    $routes->connect('/{id}', ['prefix' => 'Groups', 'controller' => 'GroupsDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    $routes->connect('/{id}/dry-run', ['prefix' => 'Groups', 'controller' => 'GroupsDelete', 'action' => 'dryRun'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    $routes->connect('/', ['prefix' => 'Groups', 'controller' => 'GroupsAdd', 'action' => 'addPost'])
        ->setMethods(['POST']);

    $routes->connect('/{id}/dry-run', ['prefix' => 'Groups', 'controller' => 'GroupsUpdate', 'action' => 'dryRun'])
        ->setPass(['id'])
        ->setMethods(['PUT']);

    $routes->connect('/{id}', ['prefix' => 'Groups', 'controller' => 'GroupsUpdate', 'action' => 'update'])
        ->setPass(['id'])
        ->setMethods(['PUT']);
});

/**
 * Healthchecks routes
 */
$routes->scope('/healthcheck', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/status', ['prefix' => 'Healthcheck', 'controller' => 'HealthcheckStatus', 'action' => 'status'])
        ->setMethods(['GET']);

    $routes->connect('/', ['prefix' => 'Healthcheck', 'controller' => 'HealthcheckIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/error', ['prefix' => 'Healthcheck', 'controller' => 'HealthcheckError', 'action' => 'internal'])
        ->setMethods(['GET','POST', 'PUT', 'DELETE']);
});

/**
 * Permissions prefixed routes
 */
$routes->scope('/permissions', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/resource/{acoForeignKey}', ['prefix' => 'Permissions', 'controller' => 'PermissionsView', 'action' => 'viewAcoPermissions'])
        ->setPass(['acoForeignKey'])
        ->setMethods(['GET']);
});

/**
 * Resources prefixed routes
 */
$routes->scope('/resources', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'Resources', 'controller' => 'ResourcesIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/{id}', ['prefix' => 'Resources', 'controller' => 'ResourcesView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);

    $routes->connect('/', ['prefix' => 'Resources', 'controller' => 'ResourcesAdd', 'action' => 'add'])
        ->setMethods(['POST']);

    $routes->connect('/{id}', ['prefix' => 'Resources', 'controller' => 'ResourcesUpdate', 'action' => 'update'])
        ->setPass(['id'])
        ->setMethods(['PUT']);

    $routes->connect('/{id}', ['prefix' => 'Resources', 'controller' => 'ResourcesDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);
});

/**
 * Resources types prefixed routes
 */
$routes->scope('/resource-types', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'ResourceTypes', 'controller' => 'ResourceTypesIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/{id}', ['prefix' => 'ResourceTypes', 'controller' => 'ResourceTypesView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);
});

/**
 * Roles prefixed routes
 */
$routes->scope('/roles', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'Roles', 'controller' => 'RolesIndex', 'action' => 'index'])
        ->setMethods(['GET']);
});

/**
 * Share prefixed routes
 */
$routes->prefix('/share', function ($routes) {
    $routes->setExtensions(['json']);

    // @deprecated since v2.4.0 will be removed in v2.6
    // replaced by /share/search-aros
    $routes->connect('/search-users/resource/{acoForeignKey}', ['prefix' => 'Share', 'controller' => 'ShareSearch', 'action' => 'searchArosToShareWith'])
        ->setPass(['acoForeignKey'])
        ->setMethods(['GET']);

    $routes->connect('/search-aros', ['prefix' => 'Share', 'controller' => 'ShareSearch', 'action' => 'searchArosToShareWith'])
        ->setMethods(['GET']);

    $routes->connect('/simulate/resource/{acoForeignKey}', ['prefix' => 'Share', 'controller' => 'Share', 'action' => 'dryRun'])
        ->setPass(['acoForeignKey'])
        ->setMethods(['POST']);

    $routes->connect('/resource/{acoForeignKey}', ['prefix' => 'Share', 'controller' => 'Share', 'action' => 'share'])
        ->setPass(['acoForeignKey'])
        ->setMethods(['PUT']);
});

/**
 * Users prefixed routes
 */
$routes->scope('/users', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'Users', 'controller' => 'UsersIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/', ['prefix' => 'Users', 'controller' => 'UsersAdd', 'action' => 'addPost'])
        ->setMethods(['POST']);

    $routes->connect('/register', ['prefix' => 'Users', 'controller' => 'UsersRegister', 'action' => 'registerGet'])
        ->setMethods(['GET']);

    $routes->connect('/register', ['prefix' => 'Users', 'controller' => 'UsersRegister', 'action' => 'registerPost'])
        ->setMethods(['POST']);

    $routes->connect('/recover', ['prefix' => 'Users', 'controller' => 'UsersRecover', 'action' => 'recoverGet'])
        ->setMethods(['GET']);

    $routes->connect('/recover', ['prefix' => 'Users', 'controller' => 'UsersRecover', 'action' => 'recoverPost'])
        ->setMethods(['POST']);

    $routes->connect('/{id}', ['prefix' => 'Users', 'controller' => 'UsersView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);

    $routes->connect('/{id}', ['prefix' => 'Users', 'controller' => 'UsersEdit', 'action' => 'editPost'])
        ->setPass(['id'])
        ->setMethods(['PUT', 'POST']);

    $routes->connect('/{id}/dry-run', ['prefix' => 'Users', 'controller' => 'UsersDelete', 'action' => 'dryrun'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    $routes->connect('/{id}', ['prefix' => 'Users', 'controller' => 'UsersDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    $routes->connect('/csrf-token', ['prefix' => 'Users', 'controller' => 'GetCsrfToken', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/validateAccount/{userId}', ['prefix' => 'Setup', 'controller' => 'SetupComplete', 'action' => 'complete'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);
});

/**
 * Avatars prefixed routes
 */
$routes->scope('/avatars', function (RouteBuilder $routes) {
    $routes->connect('/view/{id}/{format}', ['prefix' => 'Avatars', 'controller' => 'AvatarsView', 'action' => 'view'])
        ->setPass(['id', 'format'])
        ->setMethods(['GET']);
});

/**
 * Secrets prefixed routes
 */
$routes->scope('/secrets', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/resource/{resourceId}', ['prefix' => 'Secrets', 'controller' => 'SecretsView', 'action' => 'view'])
        ->setPass(['resourceId'])
        ->setMethods(['GET']);
});

/**
 * Settings prefixed routes
 */
$routes->scope('/settings', function ($routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['prefix' => 'Settings', 'controller' => 'SettingsIndex', 'action' => 'index'])
        ->setMethods(['GET']);
});

/**
 * Setup routes
 */
$routes->scope('/setup', function ($routes) {
    $routes->setExtensions(['json']);

    // new routes
    $routes->connect('/start/{userId}/{tokenId}', ['prefix' => 'Setup', 'controller' => 'SetupStart', 'action' => 'start'])
        ->setPass(['userId', 'tokenId'])
        ->setMethods(['GET']);

    $routes->connect('/complete/{userId}', ['prefix' => 'Setup', 'controller' => 'SetupComplete', 'action' => 'complete'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);

    $routes->connect('/recover/start/{userId}/{tokenId}', ['prefix' => 'Setup', 'controller' => 'RecoverStart', 'action' => 'start'])
        ->setPass(['userId', 'tokenId'])
        ->setMethods(['GET']);

    $routes->connect('/recover/complete/{userId}', ['prefix' => 'Setup', 'controller' => 'RecoverComplete', 'action' => 'complete'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);

    $routes->connect('/recover/abort/{userId}', ['prefix' => 'Setup', 'controller' => 'RecoverAbort', 'action' => 'abort'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);

    // Legacy v1 backward compatibility routes
    $routes->connect('/install/{userId}/{tokenId}', ['prefix' => 'Setup', 'controller' => 'SetupStart', 'action' => 'start'])
        ->setPass(['userId', 'tokenId'])
        ->setMethods(['GET']);

    $routes->connect('/recover/{userId}/{tokenId}', ['prefix' => 'Setup', 'controller' => 'RecoverStart', 'action' => 'start'])
        ->setPass(['userId', 'tokenId'])
        ->setMethods(['GET']);

    $routes->connect('/completeRecovery/{userId}', ['prefix' => 'Setup', 'controller' => 'RecoverComplete', 'action' => 'complete'])
        ->setPass(['userId'])
        ->setMethods(['PUT', 'POST']);
});

/**
 * Appjs routes
 */
$routes->scope('/app', function ($routes) {
    $routes->connect('/administration/*', ['prefix' => 'Pages', 'controller' => 'Home', 'action' => 'apiApp']);
    $routes->connect('/settings/mfa/*', ['prefix' => 'Pages', 'controller' => 'Home', 'action' => 'apiApp']);
    $routes->connect('/*', ['prefix' => 'Pages', 'controller' => 'Home', 'action' => 'apiExtApp']);
});
