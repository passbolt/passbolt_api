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
 * @since         2.13.0
 */

use Cake\Routing\RouteBuilder;

/**
 * Folders prefixed routes
 */

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/Folders', ['path' => '/folders'], function (RouteBuilder $routes) {
    $routes->setExtensions('json');

    /**  @uses \Passbolt\Folders\Controller\Folders\FoldersViewController::view() */
    $routes->connect('/{folderId}', ['prefix' => 'Folders', 'controller' => 'FoldersView', 'action' => 'view'])
        ->setPass(['folderId'])
        ->setMethods(['GET']);

    /** @uses \Passbolt\Folders\Controller\Folders\FoldersCreateController::create() */
    $routes->connect('/', ['prefix' => 'Folders', 'controller' => 'FoldersCreate', 'action' => 'create'])
        ->setMethods(['POST']);

    /** @uses \Passbolt\Folders\Controller\Folders\FoldersUpdateController::update() */
    $routes->connect('/{folderId}', ['prefix' => 'Folders', 'controller' => 'FoldersUpdate', 'action' => 'update'])
        ->setPass(['folderId'])
        ->setMethods(['PUT', 'POST']);

    /** @uses \Passbolt\Folders\Controller\Folders\FoldersDeleteController::delete() */
    $routes->connect('/{folderId}', ['prefix' => 'Folders', 'controller' => 'FoldersDelete', 'action' => 'delete'])
        ->setPass(['folderId'])
        ->setMethods(['DELETE']);

    /** @uses \Passbolt\Folders\Controller\Folders\FoldersIndexController::index() */
    $routes->connect('/', ['prefix' => 'Folders', 'controller' => 'FoldersIndex', 'action' => 'index'])
        ->setMethods(['GET']);
});

/**
 * Move prefixed routes
 */
$routes->plugin('Passbolt/Folders', ['path' => '/move'], function (RouteBuilder $routes) {
    $routes->setExtensions('json');

    /** @uses \Passbolt\Folders\Controller\FoldersRelations\FoldersRelationsMoveController::index() */
    $routes->connect('/{foreignModel}/{foreignId}', [
            'prefix' => 'FoldersRelations', 'controller' => 'FoldersRelationsMove', 'action' => 'move',
        ])
        ->setPass(['foreignModel', 'foreignId'])
        ->setMethods(['PUT', 'POST']);
});

/**
 * Share prefixed routes
 */
$routes->plugin('Passbolt/Folders', ['path' => '/share'], function (RouteBuilder $routes) {
    $routes->setExtensions('json');

    /** @uses \Passbolt\Folders\Controller\FoldersRelations\FoldersRelationsMoveController::index() */
    $routes->connect('/folder/{folderId}', ['prefix' => 'Folders', 'controller' => 'FoldersShare', 'action' => 'share'])
        ->setPass(['folderId'])
        ->setMethods(['PUT', 'POST']);
});
