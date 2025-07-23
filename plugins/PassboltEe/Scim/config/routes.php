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
 * @since         4.1.0
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->plugin('Passbolt/Scim', ['path' => '/scim'], function (RouteBuilder $routes): void {
    $routes->setExtensions(['json']);

    $routes->connect('/settings', ['controller' => 'ScimGetSettings', 'action' => 'getSettings'])
        ->setMethods(['GET']);

    $routes->connect('/settings', ['controller' => 'ScimSetSettings', 'action' => 'setSettings'])
        ->setMethods(['POST']);

    $routes
        ->connect(
            '/settings/{id}',
            ['controller' => 'ScimSetSettings', 'action' => 'setSettings']
        )
        ->setPass(['id'])
        ->setMethods(['POST', 'PUT']);

    $routes
        ->connect(
            '/settings/{id}',
            ['controller' => 'ScimDeleteSettings', 'action' => 'deleteSettings']
        )
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    $routes->prefix('V2', function (RouteBuilder $routes): void {
        $routes->connect('/{settingId}/Schemas', ['controller' => 'Scim', 'action' => 'schemas'])
            ->setPass(['settingId'])
            ->setMethods(['GET']);
        $routes->connect('/{settingId}/Schemas/{schemaId}', ['controller' => 'Scim', 'action' => 'schemas'])
            ->setPass(['settingId', 'schemaId'])
            ->setMethods(['GET']);
        $routes->connect('/{settingId}/ResourceTypes', ['controller' => 'Scim', 'action' => 'resourceTypes'])
            ->setPass(['settingId'])
            ->setMethods(['GET']);
        $routes->connect(
            '/{settingId}/ResourceTypes/{resourceType}',
            [
                'controller' => 'Scim',
                'action' => 'resourceTypes',
            ]
        )
            ->setPass(['settingId', 'resourceType'])
            ->setMethods(['GET']);
        $routes->connect(
            '/{settingId}/ServiceProviderConfig',
            [
                'controller' => 'Scim',
                'action' => 'serviceProviderConfig',
            ]
        )
            ->setPass(['settingId'])
            ->setMethods(['GET']);

        $routes->connect('/{settingId}/ResourceTypes', ['controller' => 'Scim', 'action' => 'resourceTypes'])
            ->setPass(['settingId'])
            ->setMethods(['GET']);
        $routes->connect('/{settingId}/{resourceType}', ['controller' => 'Scim', 'action' => 'index'])
            ->setPass(['settingId', 'resourceType'])
            ->setMethods(['GET']);

        $routes->connect('/{settingId}/{resourceType}', ['controller' => 'Scim', 'action' => 'add'])
            ->setPass(['settingId', 'resourceType'])
            ->setMethods(['POST']);

        $routes->connect('/{settingId}/{resourceType}/{id}', ['controller' => 'Scim', 'action' => 'view'])
            ->setPass(['settingId', 'resourceType', 'id'])
            ->setMethods(['GET']);

        $routes->connect('/{settingId}/{resourceType}/{id}', ['controller' => 'Scim', 'action' => 'edit'])
            ->setPass(['settingId', 'resourceType', 'id'])
            ->setMethods(['PATCH']);

        $routes->connect('/{settingId}/{resourceType}/{id}', ['controller' => 'Scim', 'action' => 'delete'])
            ->setPass(['settingId', 'resourceType', 'id'])
            ->setMethods(['DELETE']);

        $routes->fallbacks(DashedRoute::class);
    });
});
