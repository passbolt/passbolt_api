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
 * @since         5.16.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/OfflineMode', ['path' => '/offline'], function (RouteBuilder $routes): void {
    $routes->setExtensions(['json']);

    $routes
        ->connect('/settings', ['controller' => 'OfflineSettingsGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes
        ->connect('/settings', ['controller' => 'OfflineSettingsPost', 'action' => 'post'])
        ->setMethods(['POST', 'PUT']);

    $routes
        ->connect('/settings/{id}', ['controller' => 'OfflineSettingsDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    /**
     * @uses \Passbolt\OfflineMode\Controller\Items\OfflineItemsAddController::add()
     */
    $routes
        ->connect('/{foreignModel}/{foreignKey}', [
            'prefix' => 'Items',
            'controller' => 'OfflineItemsAdd',
            'action' => 'add',
        ])
        ->setPass(['foreignModel', 'foreignKey'])
        ->setMethods(['POST', 'PUT']);

    /**
     * @uses \Passbolt\OfflineMode\Controller\Items\OfflineItemsDeleteController::delete()
     */
    $routes
        ->connect('/item/{id}', [
            'prefix' => 'Items',
            'controller' => 'OfflineItemsDelete',
            'action' => 'delete',
        ])
        ->setPass(['id'])
        ->setMethods(['DELETE']);
});
