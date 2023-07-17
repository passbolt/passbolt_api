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
 * @since         3.9.0
 */
use Cake\Core\Configure;
use Cake\Routing\RouteBuilder;
use Passbolt\Sso\Model\Entity\SsoSetting;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/Sso', ['path' => '/sso'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    /**
     * Returns list of enabled providers.
     */
    $routes
        ->connect('/providers', [
            'prefix' => 'Providers',
            'controller' => 'SsoProvidersGet',
            'action' => 'getEnabledInSystemConfig',
        ])
        ->setMethods(['GET']);

    /**
     * Endpoints related to Azure provider.
     */

    $azure = SsoSetting::PROVIDER_AZURE;
    if (Configure::read("passbolt.plugins.sso.providers.{$azure}")) {
        $routes
            ->connect('/azure/login', [
                'prefix' => 'Azure',
                'controller' => 'SsoAzureStage1',
                'action' => 'stage1',
            ])
            ->setMethods(['POST']);

        $routes
            ->connect('/azure/login/dry-run', [
                'prefix' => 'Azure',
                'controller' => 'SsoAzureStage1DryRun',
                'action' => 'stage1DryRun',
            ])
            ->setMethods(['POST']);

        $routes
            ->connect('/azure/redirect', [
                'prefix' => 'Azure',
                'controller' => 'SsoAzureStage2',
                'action' => 'triage',
            ])
            ->setMethods(['GET']);
    }

    /**
     * Endpoints related to Google provider.
     */

    $google = SsoSetting::PROVIDER_GOOGLE;
    if (Configure::read("passbolt.plugins.sso.providers.{$google}")) {
        $routes
            ->connect('/google/login/dry-run', [
                'prefix' => 'Google',
                'controller' => 'SsoGoogleStage1DryRun',
                'action' => 'stage1DryRun',
            ])
            ->setMethods(['POST']);

        $routes
            ->connect('/google/login', [
                'prefix' => 'Google',
                'controller' => 'SsoGoogleStage1',
                'action' => 'stage1',
            ])
            ->setMethods(['POST']);

        $routes
            ->connect('/google/redirect', [
                'prefix' => 'Google',
                'controller' => 'SsoGoogleStage2',
                'action' => 'triage',
            ])
            ->setMethods(['GET']);
    }

    // Generic success pages

    $routes->connect('/login/success', [
            'prefix' => 'Success',
            'controller' => 'SsoSuccess',
            'action' => 'ssoSuccess',
        ])
        ->setMethods(['GET']);

    $routes->connect('/login/dry-run/success', [
            'prefix' => 'Success',
            'controller' => 'SsoSuccessDryRun',
            'action' => 'ssoSuccess',
        ])
        ->setMethods(['GET']);

    // Keys

    $routes->connect('/keys', [
            'prefix' => 'Keys',
            'controller' => 'SsoKeysCreate',
            'action' => 'create',
        ])
        ->setMethods(['POST']);

    $routes->connect('/keys/{keyId}/{userId}/{token}', [
            'prefix' => 'Keys',
            'controller' => 'SsoKeysGet',
            'action' => 'get',
        ])
        ->setPass(['keyId', 'userId', 'token'])
        ->setMethods(['GET']);

    $routes->connect('/keys/{id}', [
            'prefix' => 'Keys',
            'controller' => 'SsoKeysDelete',
            'action' => 'delete',
        ])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    // Settings

    $routes->connect('/settings', [
            'prefix' => 'Settings',
            'controller' => 'SsoSettingsCreate',
            'action' => 'create',
        ])
        ->setMethods(['POST']);

    $routes->connect('/settings/{id}', [
            'prefix' => 'Settings',
            'controller' => 'SsoSettingsView',
            'action' => 'view',
        ])
        ->setPass(['id'])
        ->setMethods(['GET']);

    $routes->connect('/settings/{id}', [
            'prefix' => 'Settings',
            'controller' => 'SsoSettingsDelete',
            'action' => 'delete',
        ])
        ->setPass(['id'])
        ->setMethods(['DELETE']);

    $routes->connect('/settings/{id}', [
            'prefix' => 'Settings',
            'controller' => 'SsoSettingsActivate',
            'action' => 'activate',
        ])
        ->setPass(['id'])
        ->setMethods(['POST', 'PUT']);

    $routes->connect('/settings/current', [
            'prefix' => 'Settings',
            'controller' => 'SsoSettingsViewCurrent',
            'action' => 'viewCurrent',
        ])
        ->setMethods(['GET']);

    $routes->connect('/settings', [
            'prefix' => 'Settings',
            'controller' => 'SsoSettingsIndex',
            'action' => 'index',
        ])
        ->setMethods(['GET']);
});
