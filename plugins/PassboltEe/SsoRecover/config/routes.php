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
 * @since         3.11.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->plugin('Passbolt/SsoRecover', ['path' => '/sso/recover'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    /**
     * Azure
     */
    $routes
        ->connect('/azure', [
            'prefix' => 'Azure',
            'controller' => 'AzureRecoverLogin',
            'action' => 'login',
        ])
        ->setMethods(['POST']);

    $routes
        ->connect('/azure/success', [
            'prefix' => 'Azure',
            'controller' => 'AzureRecoverSuccess',
            'action' => 'ssoRecoverSuccess',
        ])
        ->setMethods(['GET']);

    /**
     * Google
     */
    $routes
        ->connect('/google', [
            'prefix' => 'Google',
            'controller' => 'GoogleRecoverLogin',
            'action' => 'login',
        ])
        ->setMethods(['POST']);

    $routes
        ->connect('/google/success', [
            'prefix' => 'Google',
            'controller' => 'GoogleRecoverSuccess',
            'action' => 'ssoRecoverSuccess',
        ])
        ->setMethods(['GET']);

    /**
     * Get recover URL
     */
    $routes
        ->connect('/start', [
            'controller' => 'RecoverStart',
            'action' => 'start',
        ])
        ->setMethods(['POST']);

    /**
     * Redirection URL when user can self-register
     */
    $routes
        ->connect('/error', [
            'prefix' => 'SelfRegistration',
            'controller' => 'HandleError',
            'action' => 'handleError',
        ])
        ->setMethods(['GET']);
});
