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
use Cake\Core\Configure;
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/AuditLog', ['path' => '/actionlog'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/resource/{resourceId}', ['controller' => 'ResourceLogs', 'action' => 'view'])
           ->setPass(['resourceId'])
           ->setMethods(['GET']);

    $routes->connect('/user/{userId}', ['controller' => 'UserLogs', 'action' => 'view'])
        ->setPass(['userId'])
        ->setMethods(['GET']);

    if (Configure::read('passbolt.plugins.folders.enabled')) {
        $routes->connect('/folder/{folderId}', ['controller' => 'FolderLogs', 'action' => 'view'])
               ->setPass(['folderId'])
               ->setMethods(['GET']);
    }

    $routes->connect('/logs', ['controller' => 'ActionLogsIndex', 'action' => 'index'])
        ->setMethods(['GET']);
});
