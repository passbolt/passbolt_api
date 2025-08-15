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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller\V2;

use App\Test\Factory\UserFactory;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Test\Factory\ScimEntryFactory;
use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;
use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestTrait;

/**
 * IntegrationUsersTest class
 */
class IntegrationUsersTestCase extends ScimApiIntegrationTestCase
{
    use ScimApiIntegrationTestTrait;

    /**
     * Test: The user does not exist in passbolt
     *
     * Scenario:
     * ----------
     * this is a fresh passbolt install with (almost) no users and the systems are connected together and
     * the administrator creates a user in Azure.
     * The user does not exist in passbolt and there is no SCIM entry table for that user
     * ---------
     * In this scenario the Azure SCIM process will send a GET request with a userName filter to match an existing user
     * and the SCIM plugin returns Empty List/Query Response.
     * Then the Azure SCIM process will send a POST request to create the user.
     * When receiving this POST request the SCIM plugin passbolt creates the user and
     * creates the scim_entry table entry for that user (including the scim_name (username) and the external_id sent by Azure)
     * and returns the newly created user record (User Resource response) including the id from users table.
     * Azure associates the passbolt user id as a reference on their side.
     */
    public function testCreate_UserAndEntryDontExistInPassbolt()
    {
        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $userEmail = self::USER_1_EMAIL;

        $existingEntry = $this->getScimEntryByName($scimName);
        $this->assertNull($existingEntry);
        $existingUser = $this->getUserByUsername($userEmail);
        $this->assertNull($existingUser);

        // Check if the user exists
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users?filter=userName+eq+%22' . urlencode($scimName) . '%22'));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:ListResponse');
        $this->assertResponseContains('"totalResults": 0');

        // create the user
        $this->configScimAuth();
        $this->post($this->getScimEndpoint('Users'), $this->getUserPostData($scimName, email: $userEmail));
        $this->assertResponseCode(201);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"value": "' . $userEmail . '"');

        $newEntry = $this->getScimEntryByName($scimName);
        $this->assertNotNull($newEntry);
        $newUser = $this->getUserByUsername($userEmail);
        $this->assertNotNull($newUser);

        $this->assertSame($newEntry->scim_name, $scimName);
        $this->assertSame($newEntry->foreign_model, ScimEntry::FOREIGN_MODEL_USERS);
        $this->assertSame($newEntry->foreign_key, $newUser->id);
        $this->assertSame($newUser->username, $userEmail);
        $this->assertFalse($newUser->active);
    }

    /**
     * Test: The user exists in passbolt, it was created in Passbolt, and there is no scim_entries record yet
     *
     * Scenario:
     * ----------
     * the feature is released and an administrator now wants to use it on an existing instance
     * where they were managing users manually (or via LDAP sync).
     * ---------
     * In this scenario since there is no scim_entries for that user, passbolt returns user not found,
     * e.g. Passbolt pretends not to know about the user.
     * This is because we do not want to create a scim_entries record on a GET request.
     *
     * Azure follows by sending a POST request with the user information.
     * SCIM plugin in passbolt reuses the user entry in passbolt and creates a new entry in SCIM table using the username to map.
     *
     * If the name is different, the name in passbolt is updated.
     * Passbolt replies with the updated User resource that includes the id of the existing user.
     */
    public function testCreate_UserExistAndEntryDontExistInPassbolt()
    {
        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $userEmail = self::USER_1_EMAIL;
        UserFactory::make(['username' => $userEmail])->persist();

        $existingEntry = $this->getScimEntryByName($scimName);
        $this->assertNull($existingEntry);
        $existingUser = $this->getUserByUsername($userEmail);
        $this->assertNotNull($existingUser);

        // Check if the user exists
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users?filter=userName+eq+%22' . urlencode($scimName) . '%22'));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:ListResponse');
        $this->assertResponseContains('"totalResults": 0');

        // create the user
        $this->configScimAuth();
        $firstNameModified = $existingUser->profile->first_name . ' - modified';
        $this->post($this->getScimEndpoint('Users'), $this->getUserPostData($scimName, email: $userEmail, firstName: $firstNameModified));
        $this->assertResponseCode(201);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"value": "' . $userEmail . '"');

        $newEntry = $this->getScimEntryByName($scimName);
        $this->assertNotNull($newEntry);
        $updatedUser = $this->getUserByUsername($userEmail);
        $this->assertNotNull($updatedUser);

        $this->assertSame($newEntry->scim_name, $scimName);
        $this->assertSame($newEntry->foreign_model, ScimEntry::FOREIGN_MODEL_USERS);
        $this->assertSame($newEntry->foreign_key, $updatedUser->id);
        $this->assertSame($updatedUser->username, $userEmail);
        $this->assertSame($updatedUser->id, $existingUser->id);
        $this->assertSame($updatedUser->username, $existingUser->username);
        $this->assertSame($updatedUser->profile->first_name, $firstNameModified);
    }

    /**
     * Test: The user exists in passbolt and there is a scim_entries record, it was created by SCIM sync
     *
     * Scenario:
     * ----------
     * the SCIM sync was enabled in Azure and then the SCIM configuration was deleted in Azure.
     * Then a SCIM configuration is recreated in Azure and a sync is started.
     * There is a user that was created by SCIM and is still present.
     * ---------
     * In this scenario Azure sends a GET request with a filter (because it has no passbolt user id associated),
     * the SCIM plugin in passbolt returns the User record that matches the filter because the scim_entries exists and
     * can be reused and Azure can associate the passbolt user id.
     * Azure then associates the passbolt user id obtained in the response and
     * sends a PATCH request to update attributes that may have changed.
     */
    public function testCreate_UserExistAndEntryExistInPassbolt()
    {
        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $userEmail = self::USER_1_EMAIL;
        /** @var ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->username, $userEmail);
        $this->assertSame($scimEntry->user->profile->first_name, 'User 1');

        // Check if the user exists
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users?filter=userName+eq+%22' . urlencode($scimName) . '%22'));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:ListResponse');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"totalResults": 1');

        // Update user
        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'name.givenName',
                'value' => 'First name updated',
            ],
        ]));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"givenName": "First name updated"');

        $scimEntry = $this->getScimEntryByName($scimName, addUser: true);
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->username, $userEmail);
        $this->assertSame('First name updated', $scimEntry->user->profile->first_name);
    }

    /**
     * Test: The user is created, then deleted in Passbolt, then created in Azure
     *
     * Scenario:
     * ----------
     * the user was never synchronized with Azure.
     * ---------
     * In this scenario we recreate the user in passbolt.
     * Because it’s possible in passbolt to recreate a user if the username was previously used in the past.
     * In any case the new user doesn’t have access to previous user data.
     */
    public function testCreate_UserCreatedAndDeletedInPassbolt()
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $userEmail = self::USER_1_EMAIL;
        UserFactory::make(['username' => $userEmail])->persist();

        $existingUser = $this->getUserByUsername($userEmail);
        $this->assertNotNull($existingUser);
        $this->assertFalse($existingUser->deleted);
        $usersTable->softDelete($existingUser);
        $this->assertSame([], $existingUser->getErrors());

        // Check if the user exists
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users?filter=userName+eq+%22' . urlencode($scimName) . '%22'));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:ListResponse');
        $this->assertResponseContains('"totalResults": 0');

        // create the user
        $this->configScimAuth();
        $this->post($this->getScimEndpoint('Users'), $this->getUserPostData($scimName, email: $userEmail));
        $this->assertResponseCode(201);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"value": "' . $userEmail . '"');

        $newEntry = $this->getScimEntryByName($scimName);
        $this->assertNotNull($newEntry);
        $newUser = $this->getUserByUsername($userEmail);
        $this->assertNotNull($newUser);
        $this->assertFalse($newUser->deleted);
        $deletedUser = $this->getUserByUsername($userEmail, isDeleted: true);
        $this->assertNotNull($deletedUser);
        $this->assertTrue($deletedUser->deleted);
        $this->assertSame($deletedUser->id, $existingUser->id);
        $this->assertNotSame($newUser->id, $deletedUser->id);

        $this->assertSame($newEntry->scim_name, $scimName);
        $this->assertSame($newEntry->foreign_model, ScimEntry::FOREIGN_MODEL_USERS);
        $this->assertSame($newEntry->foreign_key, $newUser->id);
        $this->assertSame($newUser->username, $userEmail);
        $this->assertSame($newUser->username, $existingUser->username);
    }

    /**
     * Test: The user exists in passbolt and was created by SCIM, and the name was updated in Azure
     *
     * Scenario:
     * ----------
     * a classic update, for example the name is updated in Azure.
     * ---------
     * If the user was created in passbolt via SCIM,
     * Azure will send a GET request with the user ID and Passbolt returns the user information
     * and then a PATCH request is sent to update the attributes that have changed.
     * Passbolt updates the name and returns the updated User Resource.
     */
    public function testEdit_UserExistAndEntryExistInPassbolt_UpdateName()
    {
        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $userEmail = self::USER_1_EMAIL;
        /** @var ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->username, $userEmail);
        $this->assertSame($scimEntry->user->profile->first_name, 'User 1');

        // Check if the user exists
        $this->assertScimUserExistRequestById_Success($scimEntry->foreign_key, $scimName);

        // Update user
        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'name.givenName',
                'value' => 'First name updated',
            ],
        ]));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"givenName": "First name updated"');

        $scimEntry = $this->getScimEntryByName($scimName, addUser: true);
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->username, $userEmail);
        $this->assertSame('First name updated', $scimEntry->user->profile->first_name);
    }

    /**
     * Test: The user exists in passbolt and was created by SCIM, and the user is set to active=false|true in Azure
     *
     * Scenario:
     * ----------
     * another common update, this time with active=false|true in Azure instead of name.
     * ---------
     * Same than for the scenario above, we set the user to disabled=current time stamp|null in passbolt,
     * depending on if the user is active or not.
     * Careful not to map with “active” fields in passbolt as it means something else,
     * e.g. that the user has completed the setup
     *
     * @dataProvider providerUpdateActive
     */
    public function testEdit_UserExistAndEntryExistInPassbolt_UpdateActive(
        ?string $disabled,
        bool $patchActiveValue,
        ?string $expectedDisabled
    ) {
        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $userEmail = self::USER_1_EMAIL;
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = ScimEntryFactory::make(['scim_name' => $scimName])
            ->withUser(['username' => $userEmail, 'disabled' => $disabled])->persist();
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->active, true);
        $this->assertSame($scimEntry->user->disabled?->format('Y-m-d H:i:s'), $disabled);

        // Check if the user exists by id
        $this->assertScimUserExistRequestById_Success($scimEntry->foreign_key, $scimName);

        // Update user
        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'active',
                'value' => $patchActiveValue ? 'True' : 'False',
            ],
        ]));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"active": ' . json_encode($patchActiveValue));

        $scimEntry = $this->getScimEntryByName($scimName, addUser: true);
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->active, true);
        $this->assertSame($scimEntry->user->disabled?->format('Y-m-d H:i:s'), $expectedDisabled);
    }

    /**
     * Test: The user exists in passbolt and was created by SCIM, and the email was updated in Azure
     *
     * Scenario:
     * ----------
     * The contact email is updated in Azure.
     * However, the email cannot be updated in passbolt for security reasons
     * (email needs to be validated, emails are used to map the user's private/public key using uid,
     * you need the private key to change the uid in the public key).
     * ---------
     * Return 400 BadRequest with a descriptive error when a protected attribute is modified and error type `mutability`
     */
    public function testEdit_UserExistAndEntryExistInPassbolt_UpdateEmail()
    {
        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $userEmail = self::USER_1_EMAIL;
        /** @var ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->username, $userEmail);

        // Check if the user exists
        $this->assertScimUserExistRequestById_Success($scimEntry->foreign_key, $scimName);

        // Update user
        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'emails',
                'value' => [
                    [
                        'primary' => true,
                        'value' => 'modified@email.com',
                        'type' => 'work',
                    ],
                ],
            ],
        ]));
        $this->assertResponseCode(400);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('"detail": "Unable to apply operation `replace` for the attribute `emails` with mutability `immutable`"');

        $scimEntry = $this->getScimEntryByName($scimName, addUser: true);
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->username, $userEmail);
    }

    /**
     * Test: The user exists in Passbolt and Azure, but SCIM entry in Passbolt side was deleted
     *
     * Scenario:
     * ----------
     * user exists in passbolt but there is not in scim_entries table but is being updated by Azure.
     * This scenario is possible for example if someone messed with the database,
     * and loaded a backup before the SCIM sync was done.
     * ---------
     * This scenario is similar to the creation where the user already existed in passbolt,
     * except that is it handled via a PATCH instead of a POST because Azure already knows the user id.
     * In this scenario the SCIM plugin will match the user by the Passbolt user id that Azure has previously associated
     * and is sending in the requested url. A new SCIM entry will be created, and the PATCH operation will be processed.
     * The SCIM plugin will return the updated user information
     */
    public function testEdit_UserExistAndEntryDontExistInPassbolt()
    {
        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $userEmail = self::USER_1_EMAIL;
        $externalId = '1234';
        UserFactory::make(['username' => $userEmail])->persist();

        $existingEntry = $this->getScimEntryByName($scimName);
        $this->assertNull($existingEntry);
        $existingUser = $this->getUserByUsername($userEmail);
        $this->assertNotNull($existingUser);
        $this->assertSame($existingUser->username, $userEmail);

        // Check if the user exists
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users' . DS . $existingUser->id));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"id": "' . $existingUser->id . '"');
        $this->assertResponseContains('"externalId": null');
        $this->assertResponseContains('"userName": null');

        // Update user
        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $existingUser->id), $this->getPatchOpData([
            [
                'op' => 'Add',
                'path' => 'externalId',
                'value' => $externalId,
            ],
            [
                'op' => 'Add',
                'path' => 'userName',
                'value' => $scimName,
            ],
        ]));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"id": "' . $existingUser->id . '"');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"externalId": "' . $externalId . '"');

        $newEntry = $this->getScimEntryByName($scimName);
        $this->assertNotNull($newEntry);
        $this->assertSame($newEntry->foreign_key, $existingUser->id);
        $this->assertSame($newEntry->scim_name, $scimName);
        $this->assertSame($newEntry->external_identifier, $externalId);
    }

    /**
     * Provider for testEdit_UserExistAndEntryExistInPassbolt_UpdateActive
     *
     * @return array[]
     */
    public static function providerUpdateActive(): array
    {
        return [
            'active-to-inactive' => [
                'disabled' => null,
                'patchActiveValue' => false,
                'expectedDisabled' => self::DATETIME_TEST_NOW,
            ],
            // this one should not happen, but just in case
            'active-to-active' => [
                'disabled' => null,
                'patchActiveValue' => true,
                'expectedDisabled' => null,
            ],
            'inactive-to-active' => [
                'disabled' => self::DATETIME_TEST_NOW,
                'patchActiveValue' => true,
                'expectedDisabled' => null,
            ],
            // this one should not happen, but just in case, will just update the date of disabled field
            'inactive-to-inactive' => [
                'disabled' => '2000-01-01 00:00:00',
                'patchActiveValue' => false,
                'expectedDisabled' => self::DATETIME_TEST_NOW,
            ],
        ];
    }

    /**
     * Test: The user does not exist in Passbolt, it was deleted manually in Passbolt and updated in Azure
     *
     * Scenario:
     * ----------
     * if a user was sync’ with Azure and deleted by admin directly in passbolt instead of Azure,
     * it is then changed in Azure.
     * ---------
     * In this case there should not be any scim_entries on passbolt side,
     * so therefore it will be treated like a creation and not an update.
     * Since Azure has a passbolt user id associated with the user and something is modified,
     * it will send a GET request with the passbolt user id.
     * Passbolt will return 404 resources not found so Azure will send a POST request to create the user.
     */
    public function testEdit_UserExistAndEntryExistInPassbolt_SoftDeleted()
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        $userEmail = self::USER_1_EMAIL;
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->username, $userEmail);
        $this->assertFalse($scimEntry->user->deleted);
        $usersTable->softDelete($scimEntry->user);
        $this->assertSame([], $scimEntry->user->getErrors());
        $deletedScimEntry = $this->getScimEntryByName($scimName, addUser: true, isDeleted: true);
        $this->assertTrue($deletedScimEntry->user->deleted);

        // Check if the user exists by the associated id
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users' . DS . $deletedScimEntry->foreign_key));
        $this->assertResponseCode(404);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('The Users resource with id `' . $deletedScimEntry->foreign_key . '` is already deleted');

        // Since it gets a 404, then check if the user exists by username
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users?filter=userName+eq+%22' . urlencode($scimName) . '%22'));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:ListResponse');
        $this->assertResponseContains('"totalResults": 0');

        // Then it sends a POST request to create the user
        $this->configScimAuth();
        $this->post($this->getScimEndpoint('Users'), $this->getUserPostData($scimName, email: $userEmail));
        $this->assertResponseCode(201);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"value": "' . $userEmail . '"');

        $newEntry = $this->getScimEntryByName($scimName, addUser: true);
        $this->assertNotNull($newEntry);
        $this->assertSame($newEntry->scim_name, $scimName);
        $this->assertSame($newEntry->user->username, $userEmail);
        $this->assertNotSame($newEntry->id, $deletedScimEntry->id);
        $this->assertNotSame($newEntry->user->id, $deletedScimEntry->user->id);
    }

    /**
     * Test: The user does not exist in Passbolt
     *
     * Scenario:
     * ----------
     * a user was sync’ with Azure and deleted by admin directly in passbolt instead of Azure and is then deleted in Azure.
     * ---------
     * In this scenario passbolt will reply with a 404 to Azure, and Azure will not send a DELETE request.
     *  If it can ignore that request, act like the user was properly deleted even if that was already done
     *  and return a successful delete response.
     */
    public function testDelete_UserAlreadyDeletedInPassbolt()
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        /** @var ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertFalse($scimEntry->user->deleted);
        $usersTable->softDelete($scimEntry->user);
        $this->assertSame([], $scimEntry->user->getErrors());
        $scimEntry = $this->getScimEntryByName($scimName, addUser: true, isDeleted: true);
        $this->assertTrue($scimEntry->user->deleted);

        // Check if the user exists
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key));
        $this->assertResponseCode(404);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('The Users resource with id `' . $scimEntry->foreign_key . '` is already deleted');

        // in case it tries to Delete the user anyway
        $this->configScimAuth();
        $this->delete($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key));
        $this->assertResponseCode(404);
    }

    /**
     * Test: The user exists in Passbolt
     *
     * Scenario:
     * ----------
     * A normal user delete operation in Azure.
     * ---------
     * If the user is deleted in Azure or unassigned from the application,
     * Azure will send a PATCH request with a Replace operation setting the active attribute to false (soft delete).
     * This active (boolean) attribute is mapped in passbolt to the disabled field in user table (datetime|null).
     *
     * After 30 days (or if manually done before) Azure deletes the user permanently,
     * at this time a DELETE request to remove the user completely is sent to passbolt.
     */
    public function testDelete_UserExistInPassbolt()
    {
        $this->setTestNow();
        $scimName = self::USER_1_SCIM_NAME;
        /** @var ScimEntry $scimEntry */
        $scimEntry = $this->createScimUser1();
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertFalse($scimEntry->user->deleted);
        $this->assertSame($scimEntry->user->disabled, null);

        // Check if the user exists
        $this->assertScimUserExistRequestById_Success($scimEntry->foreign_key, $scimName);

        // first the user is disabled (soft deleted in Azure)
        $this->configScimAuth();
        $this->patch($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key), $this->getPatchOpData([
            [
                'op' => 'Replace',
                'path' => 'active',
                'value' => 'False',
            ],
        ]));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"userName": "' . $scimName . '"');
        $this->assertResponseContains('"active": false');

        $scimEntry = $this->getScimEntryByName($scimName, addUser: true);
        $this->assertSame($scimEntry->scim_name, $scimName);
        $this->assertSame($scimEntry->user->disabled?->format('Y-m-d H:i:s'), self::DATETIME_TEST_NOW);

        //Delete request when the user is permanently deleted in Azure
        $this->configScimAuth();
        $this->delete($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key));
        $this->assertResponseCode(204);
    }
}
