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
 * @since         3.4.0
 */
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin('Passbolt/AccountRecovery', ['path' => '/account-recovery'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    // View an account organization policy
    // GET /account-recovery/organization-policies.json
    $routes->connect('/organization-policies', [
            'prefix' => 'AccountRecoveryOrganizationPolicies',
            'controller' => 'AccountRecoveryOrganizationPoliciesGet',
            'action' => 'get',
        ])
        ->setMethods(['GET']);

//    // Create or update an account organization policy
//    // POST|PUT /account-recovery/organization-policies.json
//    $routes->connect('/organization-policies', [
//            'prefix' => '', 'controller' => '', 'action' => ''])
//        ->setMethods(['POST', 'PUT']);
//
//    // View the account recovery private key passwords (for the organization recovery key)
//    // GET /account-recovery/private-key-passwords.json
//    $routes->connect('/private-key-passwords', [
//        'prefix' => '', 'controller' => '', 'action' => ''])
//        ->setMethods(['GET']);
//
//    // Create temporary private key passwords
//    // POST /account-recovery/temp/private-key-passwords.json
//    $routes->connect('/temp/private-key-passwords', [
//            'prefix' => '', 'controller' => '', 'action' => ''])
//        ->setMethods(['POST']);
//
//    // Browse index of all the account recovery requests
//    // GET /account-recovery/requests.json
//    $routes->connect('/requests', [
//            'prefix' => '', 'controller' => '', 'action' => ''])
//        ->setMethods(['GET']);
//
//    // Create one account recovery request
//    // POST /account-recovery/requests.json
//    $routes->connect('/requests', [
//        'prefix' => '', 'controller' => '', 'action' => ''])
//        ->setMethods(['POST']);
//
//    // View one account recovery request details
//    // GET /account-recovery/requests/:uuid.json
//    $routes->connect('/requests/:id', [
//            'prefix' => '', 'controller' => '', 'action' => ''])
//        ->setPass(['id'])
//        ->setMethods(['GET']);
//
//    // Landing page for account request request completion (sent by email)
//    // GET /account-recovery/requests/:userId/:tokenId
//    // View one account recovery request details (non logged in user)
//    // GET /account-recovery/requests/:userId/:tokenId.json
//    $routes->connect('/requests/:userId/:tokenId', [
//            'prefix' => '', 'controller' => '', 'action' => ''])
//        ->setPass(['id', 'tokenId'])
//        ->setMethods(['GET']);
//
//    // POST /account-recovery/responses.json
//    $routes->connect('/responses', [
//            'prefix' => '', 'controller' => '', 'action' => ''])
//        ->setMethods(['POST']);
});
