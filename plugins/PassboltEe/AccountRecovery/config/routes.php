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
 * @since         3.6.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/AccountRecovery', ['path' => '/account-recovery'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    // View an account organization policy
    // GET /account-recovery/organization-policies.json
    $routes->connect('/organization-policies', [
            'prefix' => 'AccountRecoveryOrganizationPolicies',
            'controller' => 'AccountRecoveryOrganizationPoliciesGet',
            'action' => 'get',
        ])
        ->setMethods(['GET']);

    // Create or update an account organization policy
    // POST|PUT /account-recovery/organization-policies.json
    $routes->connect('/organization-policies', [
            'prefix' => 'AccountRecoveryOrganizationPolicies',
            'controller' => 'AccountRecoveryOrganizationPoliciesSet',
            'action' => 'createOrUpdate',
        ])
        ->setMethods(['POST', 'PUT']);

    // View the account recovery private key passwords (for the organization recovery key)
    // GET /account-recovery/private-key-passwords.json
    $routes->connect('/private-key-passwords', [
            'prefix' => 'AccountRecoveryPrivateKeyPasswords',
          'controller' => 'AccountRecoveryPrivateKeyPasswordsIndex',
           'action' => 'index',
        ])
        ->setMethods(['GET']);

    // Browse index of all the account recovery requests
    // GET /account-recovery/requests.json
    $routes->connect('/requests', [
            'prefix' => 'AccountRecoveryRequests',
            'controller' => 'AccountRecoveryRequestsIndex',
            'action' => 'index',
        ])
        ->setMethods(['GET']);

    // View one account recovery request details
    // GET /account-recovery/requests/:uuid.json
    $routes->connect('/requests/{id}', [
        'prefix' => 'AccountRecoveryRequests', 'controller' => 'AccountRecoveryRequestsView', 'action' => 'view'])
        ->setPass(['id'])
        ->setMethods(['GET']);

    // Create one account recovery request
    // POST /account-recovery/requests.json
    $routes->connect('/requests', [
        'prefix' => 'AccountRecoveryRequests', 'controller' => 'AccountRecoveryRequestsCreate', 'action' => 'create',
    ])->setMethods(['POST']);

    // Landing page for account request request continue (sent by email)
    // GET /account-recovery/continue/:userId/:tokenId
    $routes->connect('/continue/{userId}/{tokenId}', [
        'prefix' => 'AccountRecoveryContinue', 'controller' => 'AccountRecoveryContinue', 'action' => 'get'])
        ->setPass(['userId', 'tokenId'])
        ->setMethods(['GET']);

    // View one account recovery request details (non logged in user)
    // GET /account-recovery/requests/:userId/:tokenId.json
    $routes->connect('/requests/{requestId}/{userId}/{tokenId}', [
        'prefix' => 'AccountRecoveryRequests', 'controller' => 'AccountRecoveryRequestsGet', 'action' => 'get',
    ])
    ->setPass(['requestId', 'userId', 'tokenId'])
    ->setMethods(['GET']);

    // POST /account-recovery/responses.json
    $routes->connect('/responses', [
        'prefix' => 'AccountRecoveryResponses', 'controller' => 'AccountRecoveryResponsesCreate', 'action' => 'post',
    ])->setMethods(['POST']);

    // Enroll or reject account recovery policy
    // POST /account-recovery/user-settings.json
    $routes->connect('/user-settings', [
        'prefix' => 'AccountRecoveryUserSettings',
        'controller' => 'AccountRecoveryUserSettingsSet',
        'action' => 'createOrUpdate',
    ])->setMethods(['POST']);
});
