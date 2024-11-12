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
 * @since         3.0.0
 */

use Cake\Routing\RouteBuilder;

/**
 * Enterprise Edition aka Passbolt Pro
 *
 * @uses \Passbolt\Reports\Controller\Reports\ReportsViewController::getReport()
 */

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/Subscription', ['path' => '/ee'], function (RouteBuilder $routes) {
 // BC?
    $routes->setExtensions(['json']);

    /**
     * Subscriptions
     *
     * @uses \Passbolt\Subscription\Controller\Subscriptions\SubscriptionsViewController::view()
     */
    $routes->connect('/subscription/key', [
            'prefix' => 'Subscriptions', 'controller' => 'SubscriptionsView', 'action' => 'view',
        ])->setMethods(['GET']);

    $routes->connect('/subscription/key', [
            'prefix' => 'Subscriptions', 'controller' => 'SubscriptionsCreate', 'action' => 'create',
        ])->setMethods(['POST']);

    $routes->connect('/subscription/key', [
            'prefix' => 'Subscriptions', 'controller' => 'SubscriptionsUpdate', 'action' => 'update',
        ])->setMethods(['PUT']);
});
