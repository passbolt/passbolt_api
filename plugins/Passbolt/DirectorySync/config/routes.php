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
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin('Passbolt/DirectorySync', ['path' => '/directorysync'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/ignore/toggle/:foreign_model/:foreign_key', [
                'controller' => 'DirectoryIgnore', 'action' => 'toggle',
            ])
            ->setPass(['foreign_model', 'foreign_key'])
            ->setMethods(['GET']);

    $routes->connect('/ignore/:foreign_model/:foreign_key', ['controller' => 'DirectoryIgnore', 'action' => 'view'])
            ->setPass(['foreign_model', 'foreign_key'])
            ->setMethods(['GET']);

    $routes->connect('/ignore/:foreign_model/:foreign_key', ['controller' => 'DirectoryIgnore', 'action' => 'add'])
            ->setPass(['foreign_model', 'foreign_key'])
            ->setMethods(['POST']);

    $routes->connect('/ignore/:foreign_model/:foreign_key', ['controller' => 'DirectoryIgnore', 'action' => 'delete'])
            ->setPass(['foreign_model', 'foreign_key'])
            ->setMethods(['DELETE']);

    $routes->connect('/settings', ['controller' => 'DirectorySettings', 'action' => 'view'])
            ->setMethods(['GET']);

    $routes->connect('/settings', ['controller' => 'DirectorySettings', 'action' => 'update'])
            ->setMethods(['POST', 'PUT']);

    $routes->connect('/settings', ['controller' => 'DirectorySettings', 'action' => 'disable'])
            ->setMethods(['DELETE']);

    $routes->connect('/settings/test', ['controller' => 'DirectorySettings', 'action' => 'test'])
           ->setMethods(['POST', 'PUT']);

    $routes->connect('/synchronize', ['controller' => 'DirectorySync', 'action' => 'synchronize'])
           ->setMethods(['GET']);

    $routes->connect('/synchronize/dry-run', ['controller' => 'DirectorySync', 'action' => 'dryRun'])
           ->setMethods(['GET']);
});
