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
 * @since         2.5.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/MultiFactorAuthentication', ['path' => '/mfa'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->redirect('/setup', '/mfa/setup/select');

    /**
     * Setup start page
     */
    $routes->connect('/setup/select', ['controller' => 'MfaSetupSelectProvider', 'action' => 'get'])
        ->setMethods(['GET']);

    /**
     * TOTP
     */
    $routes->connect('/setup/totp/start', ['prefix' => 'Totp', 'controller' => 'TotpSetupGet', 'action' => 'start'])
        ->setMethods(['GET']);

    $routes->connect('/setup/totp', ['prefix' => 'Totp', 'controller' => 'TotpSetupGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/setup/totp', ['prefix' => 'Totp', 'controller' => 'TotpSetupPost', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/setup/totp', ['prefix' => 'Totp', 'controller' => 'TotpSetupDelete', 'action' => 'delete'])
        ->setMethods(['DELETE']);

    $routes->connect('/verify/totp', ['prefix' => 'Totp', 'controller' => 'TotpVerifyGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/verify/totp', ['prefix' => 'Totp', 'controller' => 'TotpVerifyPost', 'action' => 'post'])
        ->setMethods(['POST']);

    /**
     * Yubikey
     */
    $routes->connect('/setup/yubikey', ['prefix' => 'Yubikey', 'controller' => 'YubikeySetupGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/setup/yubikey', ['prefix' => 'Yubikey', 'controller' => 'YubikeySetupPost', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/setup/yubikey', [
            'prefix' => 'Yubikey', 'controller' => 'YubikeySetupDelete', 'action' => 'delete',
        ])
        ->setMethods(['DELETE']);

    $routes->connect('/verify/yubikey', ['prefix' => 'Yubikey', 'controller' => 'YubikeyVerifyGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/verify/yubikey', [
            'prefix' => 'Yubikey', 'controller' => 'YubikeyVerifyPost', 'action' => 'post',
        ])
        ->setMethods(['POST']);

    /**
     * Duo
     */
    $routes->connect('/setup/duo', ['prefix' => 'Duo', 'controller' => 'DuoSetupGet', 'action' => 'get'])
        ->setMethods(['GET']);

    /** @deprecated Now using /setup/duo/prompt endpoint instead, redirecting to Duo instead of displaying a iFrame */
    $routes->connect('/setup/duo', ['prefix' => 'Duo', 'controller' => 'DuoSetupPost', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/setup/duo', ['prefix' => 'Duo', 'controller' => 'DuoSetupDelete', 'action' => 'delete'])
        ->setMethods(['DELETE']);

    $routes->connect('/setup/duo/prompt', ['prefix' => 'Duo', 'controller' => 'DuoSetupPromptPost', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/setup/duo/callback', [
            'prefix' => 'Duo', 'controller' => 'DuoSetupCallbackGet', 'action' => 'get',
        ])
        ->setMethods(['GET']);

    /** @deprecated Now using /verify/duo/prompt endpoint instead, redirecting to Duo instead of displaying a iFrame */
    $routes->connect('/verify/duo', ['prefix' => 'Duo', 'controller' => 'DuoVerifyGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/verify/duo', ['prefix' => 'Duo', 'controller' => 'DuoVerifyPost', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/verify/duo/prompt', [
            'prefix' => 'Duo', 'controller' => 'DuoVerifyPromptPost', 'action' => 'post',
        ])
        ->setMethods(['POST']);

    $routes->connect('/verify/duo/callback', [
            'prefix' => 'Duo', 'controller' => 'DuoVerifyCallbackGet', 'action' => 'get',
        ])
        ->setMethods(['GET']);

    $routes->connect('/verify/error', ['controller' => 'MfaVerifyAjaxError', 'action' => 'get'])
        ->setMethods(['GET', 'POST', 'PUT', 'DELETE']);

    /**
     * Org settings
     */
    $routes->connect('/settings', ['prefix' => 'OrgSettings', 'controller' => 'MfaOrgSettingsGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/settings', ['prefix' => 'OrgSettings', 'controller' => 'MfaOrgSettingsPost', 'action' => 'post'])
        ->setMethods(['PUT', 'POST']);

    /**
     * User settings
     */
    $routes->connect('/setup/{userId}', [
            'prefix' => 'UserSettings', 'controller' => 'MfaUserSettingsDelete', 'action' => 'delete',
        ])
        ->setPass(['userId'])
        ->setMethods(['DELETE']);
});
