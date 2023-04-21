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
 * @since         4.0.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/ResourceTypes', ['path' => '/resource-types'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes
        ->connect('/', ['controller' => 'ResourceTypesIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/{id}', ['controller' => 'ResourceTypesView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);
});
