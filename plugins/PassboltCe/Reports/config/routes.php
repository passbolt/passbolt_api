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

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/Reports', ['path' => '/reports'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    /**
     * Generate and return a report
     *
     * @uses \Passbolt\Reports\Controller\Reports\ReportsViewController::getReport()
     */
    $routes->connect('/{reportSlug}/{arg1}', ['prefix' => 'Reports', 'controller' => 'ReportsView', 'action' => 'view'])
        ->setMethods(['GET'])
        ->setPass(['reportSlug', 'arg1']);

    $routes->connect('/{reportSlug}', ['prefix' => 'Reports', 'controller' => 'ReportsView', 'action' => 'view'])
        ->setMethods(['GET'])
        ->setPass(['reportSlug']);
});
