<?php
declare(strict_types=1);

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
 * @since         5.2.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->plugin('Passbolt/UserKeyPolicies', ['path' => '/user-key-policies'], function (RouteBuilder $routes) { // phpcs:ignore
    $routes->setExtensions(['json']);

    $routes
        ->connect('/settings', ['controller' => 'UserKeyPoliciesGetSettings', 'action' => 'get'])
        ->setMethods(['GET']);
});

$routes->scope('/setup/user-key-policies', function (RouteBuilder $routes): void {
    $routes->setExtensions(['json']);

    $routes
        ->connect(
            '/settings',
            ['controller' => 'UserKeyPoliciesGetSettings', 'action' => 'get', 'plugin' => 'Passbolt/UserKeyPolicies']
        )
        ->setMethods(['GET']);
});
