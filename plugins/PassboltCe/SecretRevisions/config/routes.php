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
 * @since         5.7.0
 */
use Cake\Routing\RouteBuilder;
use Passbolt\SecretRevisions\Middleware\SecretRevisionsSettingsMiddleware;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->plugin('Passbolt/SecretRevisions', ['path' => '/secret-revisions'], function (RouteBuilder $routes): void {
    $routes->setExtensions(['json']);
    $routes->registerMiddleware(SecretRevisionsSettingsMiddleware::class, new SecretRevisionsSettingsMiddleware());

    /**
     * Settings.
     */
    $routes->connect('/settings', ['controller' => 'SecretRevisionsSettingsPost', 'action' => 'post'])
        ->setMethods(['PUT', 'POST'])
        ->setMiddleware([SecretRevisionsSettingsMiddleware::class]);
    $routes->connect('/settings', ['controller' => 'SecretRevisionsSettingsGet', 'action' => 'get'])
        ->setMethods(['GET']);
    $routes->connect('/settings', ['controller' => 'SecretRevisionsSettingsDelete', 'action' => 'delete'])
        ->setMethods(['DELETE'])
        ->setMiddleware([SecretRevisionsSettingsMiddleware::class]);

    /**
     * Secret revisions of a resource
     */
    $routes->connect('/resource/{id}', ['controller' => 'SecretRevisionsResourceGet', 'action' => 'get'])
        ->setPass(['id'])
        ->setMethods(['GET']);
});
