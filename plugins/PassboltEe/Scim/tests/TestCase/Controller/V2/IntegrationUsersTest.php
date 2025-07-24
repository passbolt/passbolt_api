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
 * @since         3.6.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller\V2;

use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Test\Utility\BaseIntegrationTest;

/**
 * IntegrationUsersTest class
 */
class IntegrationUsersTest extends BaseIntegrationTest
{
    /**
     * @var \App\Model\Entity\Role|null
     */
    protected ?Role $userRole = null;

    /**
     * @var \App\Model\Entity\User|null
     */
    protected ?User $adminUser = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->userRole = RoleFactory::make()->user()->persist();
        // @todo: make this user the one selected in the scim settings for logs
        $this->adminUser = UserFactory::make()->admin()->persist();
    }

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
     * Scenario: a classic update, for example the name is updated in Azure.
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
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key));
        $this->assertResponseCode(200);
        $this->assertResponseContains('urn:ietf:params:scim:schemas:core:2.0:User');
        $this->assertResponseContains('"userName": "' . $scimName . '"');

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
        $scimEntry = $this->getScimEntryByName($scimName, addUser: true);

        // Check if the user exists
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key));
        $this->assertResponseCode(404);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('The Users resource with id `' . $scimEntry->foreign_key . '` was not found');

        // try to Delete user anyway
        $this->configScimAuth();
        $this->delete($this->getScimEndpoint('Users' . DS . $scimEntry->foreign_key));
        $this->assertResponseCode(404);
    }
}
