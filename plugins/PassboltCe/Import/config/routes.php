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

/**
 * Import routes
 */

use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->scope('/import', ['plugin' => null], function (RouteBuilder $routes): void {
    $routes->setExtensions(['json']);

    $routes->connect('/resources', ['prefix' => 'Resources', 'controller' => 'ResourcesAdd', 'action' => 'add'])
           ->setMethods(['POST']);
});
