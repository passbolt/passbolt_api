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
 * @since         4.10.0
 */
use Cake\Routing\RouteBuilder;
use Passbolt\Metadata\Middleware\MetadataSettingsSecurityMiddleware;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->plugin('Passbolt/Metadata', ['path' => '/metadata'], function (RouteBuilder $routes): void {
    $routes->setExtensions(['json']);
    $routes->registerMiddleware(MetadataSettingsSecurityMiddleware::class, new MetadataSettingsSecurityMiddleware());

    /**
     * Metadata type settings routes
     */
    $routes->connect('/types/settings', ['controller' => 'MetadataTypesSettingsGet', 'action' => 'get'])
        ->setMethods(['GET']);
    $routes->connect('/types/settings', ['controller' => 'MetadataTypesSettingsPost', 'action' => 'post'])
        ->setMethods(['PUT', 'POST'])
        ->setMiddleware([MetadataSettingsSecurityMiddleware::class]);

    /**
     * Metadata keys settings routes
     */
    $routes->connect('/keys/settings', ['controller' => 'MetadataKeysSettingsGet', 'action' => 'get'])
        ->setMethods(['GET']);
    $routes->connect('/keys/settings', ['controller' => 'MetadataKeysSettingsPost', 'action' => 'post'])
        ->setMethods(['PUT', 'POST'])
        ->setMiddleware([MetadataSettingsSecurityMiddleware::class]);

    /**
     * Metadata keys routes
     */
    $routes->connect('/keys', ['controller' => 'MetadataKeysIndex', 'action' => 'index'])
        ->setMethods(['GET']);
    $routes->connect('/keys', ['controller' => 'MetadataKeyCreate', 'action' => 'create'])
        ->setMethods(['POST'])
        ->setMiddleware([MetadataSettingsSecurityMiddleware::class]);
    $routes->connect('/keys/{id}', ['controller' => 'MetadataKeyUpdate', 'action' => 'update'])
        ->setPass(['id'])
        ->setMethods(['PUT', 'POST'])
        ->setMiddleware([MetadataSettingsSecurityMiddleware::class]);
    $routes->connect('/keys/{id}', ['controller' => 'MetadataKeyDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE'])
        ->setMiddleware([MetadataSettingsSecurityMiddleware::class]);

    /**
     * Metadata private keys routes
     */
    $routes->connect('/keys/private/{id}', ['controller' => 'MetadataPrivateKeysUpdate', 'action' => 'update'])
        ->setPass(['id'])
        ->setMethods(['PUT', 'POST']);
    $routes->connect('/keys/{id}/private', ['controller' => 'MetadataPrivateKeysCreate', 'action' => 'create'])
        ->setPass(['id'])
        ->setMethods(['POST']);
    $routes->connect('/keys/privates', ['controller' => 'MetadataMissingPrivateKeysShare', 'action' => 'share'])
        ->setMethods(['POST']);
    // alias of /keys/privates
    $routes->connect('/keys/private', ['controller' => 'MetadataMissingPrivateKeysShare', 'action' => 'share'])
        ->setMethods(['PUT', 'POST']);

    /**
     * Metadata session keys routes
     */
    $routes->connect('/session-keys', ['controller' => 'MetadataSessionKeysGet', 'action' => 'get'])
        ->setMethods(['GET']);
    $routes->connect('/session-keys', ['controller' => 'MetadataSessionKeyCreate', 'action' => 'create'])
        ->setMethods(['POST']);
    $routes->connect('/session-keys/{id}', ['controller' => 'MetadataSessionKeyUpdate', 'action' => 'update'])
        ->setPass(['id'])
        ->setMethods(['POST', 'PUT']);
    $routes->connect('/session-keys/{id}', ['controller' => 'MetadataSessionKeyDelete', 'action' => 'delete'])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    /**
     * Resources upgrade endpoints.
     */
    $routes->scope('/upgrade', function (RouteBuilder $routes): void {
        $routes->setExtensions(['json']);

        $routes
            ->connect(
                '/resources',
                ['prefix' => 'Upgrade', 'controller' => 'MetadataUpgradeResourcesIndex', 'action' => 'index']
            )
            ->setMethods(['GET']);

        $routes
            ->connect(
                '/resources',
                ['prefix' => 'Upgrade', 'controller' => 'MetadataUpgradeResourcesPost', 'action' => 'post']
            )
            ->setMethods(['POST']);

        $routes
            ->connect(
                '/folders',
                ['prefix' => 'Upgrade', 'controller' => 'MetadataUpgradeFoldersIndex', 'action' => 'index']
            )
            ->setMethods(['GET']);

        $routes
            ->connect(
                '/folders',
                ['prefix' => 'Upgrade', 'controller' => 'MetadataUpgradeFoldersPost', 'action' => 'post']
            )
            ->setMethods(['POST']);
    });

    /**
     * Key rotation endpoints.
     */
    $routes->scope('/rotate-key', function (RouteBuilder $routes): void {
        $routes->setExtensions(['json']);

        $routes
            ->connect(
                '/resources',
                ['prefix' => 'RotateKey', 'controller' => 'MetadataRotateKeyResourcesIndex', 'action' => 'index']
            )
            ->setMethods(['GET']);

        $routes
            ->connect(
                '/resources',
                ['prefix' => 'RotateKey', 'controller' => 'MetadataRotateKeyResourcesPost', 'action' => 'post']
            )
            ->setMethods(['POST']);

        $routes
            ->connect(
                '/folders',
                ['prefix' => 'RotateKey', 'controller' => 'MetadataRotateKeyFoldersIndex', 'action' => 'index']
            )
            ->setMethods(['GET']);

        $routes
            ->connect(
                '/folders',
                ['prefix' => 'RotateKey', 'controller' => 'MetadataRotateKeyFoldersPost', 'action' => 'post']
            )
            ->setMethods(['POST']);
    });
});
