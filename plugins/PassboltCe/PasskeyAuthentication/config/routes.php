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
 * @since         5.14.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/PasskeyAuthentication', ['path' => '/passkey'], function (RouteBuilder $routes): void {
    $routes->setExtensions(['json']);

    /**
     * Enrollment (authenticated). Two-step ceremony: "begin" issues the creation options + a
     * challenge token, "finish" verifies the attestation and stores the credential together with the
     * server half of the passphrase kit. The GET endpoint renders the top-level ceremony page served
     * from the passbolt origin so navigator.credentials.create() binds to the correct relying party.
     */
    $routes->connect('/setup', ['controller' => 'PasskeySetupGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/setup/begin', ['controller' => 'PasskeySetupBegin', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/setup/finish', ['controller' => 'PasskeySetupFinish', 'action' => 'post'])
        ->setMethods(['POST']);

    /**
     * Management (authenticated): list and delete the user's passkey credentials.
     */
    $routes->connect('/settings', ['controller' => 'PasskeyIndex', 'action' => 'index'])
        ->setMethods(['GET']);

    $routes->connect('/settings', ['controller' => 'PasskeyDelete', 'action' => 'delete'])
        ->setMethods(['DELETE']);

    $routes->connect('/settings/{credentialId}', ['controller' => 'PasskeyDelete', 'action' => 'delete'])
        ->setPass(['credentialId'])
        ->setMethods(['DELETE']);

    /**
     * Organization-level toggle. GET is unauthenticated so the login page can decide whether to show
     * the passkey button; POST is administrator-only.
     */
    $routes->connect('/organization/settings', ['controller' => 'PasskeyOrgSettingsGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/organization/settings', ['controller' => 'PasskeyOrgSettingsPost', 'action' => 'post'])
        ->setMethods(['POST']);

    /**
     * Passkey login (UNAUTHENTICATED — no session yet). "begin" takes the target user id and
     * issues the assertion options (restricted to that user's passkey credentials) + a challenge
     * token; "finish" verifies the assertion and, only then, returns the server half of the kit so
     * the client can recover the passphrase locally and run the normal GPGAuth challenge. The GET
     * endpoint renders the session-less top-level ceremony page at the passbolt origin.
     */
    $routes->connect('/login', ['controller' => 'PasskeyLoginGet', 'action' => 'get'])
        ->setMethods(['GET']);

    $routes->connect('/login/begin', ['controller' => 'PasskeyLoginBegin', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/login/finish', ['controller' => 'PasskeyLoginFinish', 'action' => 'post'])
        ->setMethods(['POST']);

    /**
     * Ceremony bridge (UNAUTHENTICATED). WebAuthn can only run at the passbolt top-level origin (never
     * in the extension's cross-origin app iframe), so the ceremony runs in a top-level page that
     * deposits the raw credential onto the challenge token; the extension collects it out of band and
     * finishes. The success page is the url the extension watches to know the ceremony completed.
     */
    $routes->connect('/deposit', ['controller' => 'PasskeyDeposit', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/collect', ['controller' => 'PasskeyCollect', 'action' => 'post'])
        ->setMethods(['POST']);

    $routes->connect('/ceremony/complete', ['controller' => 'PasskeyCeremonyCompleteGet', 'action' => 'get'])
        ->setMethods(['GET']);
});
