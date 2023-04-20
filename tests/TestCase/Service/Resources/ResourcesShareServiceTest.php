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
 * @since         2.13.0
 */

namespace App\Test\TestCase\Service\Resources;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Service\Resources\ResourcesShareService;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * \App\Test\TestCase\Service\Resources\ResourcesShareServiceTest Test Case
 *
 * @covers \App\Test\TestCase\Service\Resources\ResourcesShareServiceTest
 */
class ResourcesShareServiceTest extends AppTestCase
{
    /**
     * @var ResourcesTable $Resources
     */
    public $Resources;

    /**
     * @var FavoritesTable $Favorites
     */
    public $Favorites;

    /**
     * @var \Passbolt\AccountSettings\Model\Table\PermissionsTable $Permissions
     */
    public $Permissions;

    /**
     * @var UsersTable $Users
     */
    public $Users;

    /**
     * @var ResourcesShareService $service
     */
    public $service;

    public $fixtures = [
        'app.Base/Permissions', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Favorites',
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles',
        'app.Base/GroupsUsers', 'app.Base/Groups',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->service = new ResourcesShareService();
    }

    public function tearDown(): void
    {
        unset($this->Resources);
        unset($this->Permissions);
        unset($this->Users);

        parent::tearDown();
    }

    protected function getValidSecret()
    {
        return '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----';
    }

    /* SHARE */

    public function testShareSuccess()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);

        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        // Users
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userEId = UuidFactory::uuid('user.id.edith');
        $userFId = UuidFactory::uuid('user.id.frances');
        $userJId = UuidFactory::uuid('user.id.jean');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $userLId = UuidFactory::uuid('user.id.lynne');
        $userMId = UuidFactory::uuid('user.id.marlyn');
        $userNId = UuidFactory::uuid('user.id.nancy');
        // Groups
        $groupBId = UuidFactory::uuid('group.id.board');
        $groupFId = UuidFactory::uuid('group.id.freelancer');
        $groupAId = UuidFactory::uuid('group.id.accounting');

        // Build the changes.
        $changes = [];
        $secrets = [];

        // Expected results.
        $expectedAddedUsersIds = [];
        $expectedRemovedUsersIds = [];

        // Users permissions changes.
        // Change the permission of the user Ada to read (no users are expected to be added or removed).
        $changes[] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userAId"), 'type' => Permission::READ];
        // Delete the permission of the user Betty.
        $changes[] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"), 'delete' => true];
        $expectedRemovedUsersIds[] = $userBId;
        // Add an owner permission for the user Edith
        $changes[] = ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER];
        $secrets[] = ['user_id' => $userEId, 'data' => $this->getValidSecret()];
        $expectedAddedUsersIds[] = $userEId;

        // Groups permissions changes.
        // Change the permission of the group Board (no users are expected to be added or removed).
        $changes[] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupBId"), 'type' => Permission::OWNER];
        // Delete the permission of the group Freelancer.
        $changes[] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupFId"), 'delete' => true];
        $expectedRemovedUsersIds = array_merge($expectedRemovedUsersIds, [$userJId, $userKId, $userLId, $userMId, $userNId]);
        // Add a read permission for the group Accounting.
        $changes[] = ['aro' => 'Group', 'aro_foreign_key' => $groupAId, 'type' => Permission::READ];
        $secrets[] = ['user_id' => $userFId, 'data' => $this->getValidSecret()];
        $expectedAddedUsersIds = array_merge($expectedAddedUsersIds, [$userFId]);

        // Share.
        $resource = $this->service->share($uac, $resourceId, $changes, $secrets);
        $this->assertFalse($resource->hasErrors());

        // Load the resource.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Permissions', 'Secrets']]);

        // Verify that all the allowed users have a secret for the resource.
        $secretsUsersIds = Hash::extract($resource->secrets, '{n}.user_id');
        $hasAccessUsers = $this->Users->findIndex(Role::USER, ['filter' => ['has-access' => [$resourceId]]])->all()->toArray();
        $hasAccessUsersIds = Hash::extract($hasAccessUsers, '{n}.id');
        $this->assertEquals(count($secretsUsersIds), count($hasAccessUsersIds));
        $this->assertEmpty(array_diff($secretsUsersIds, $hasAccessUsersIds));

        // Ensure that the newly added users have a secret, and are allowed to access the resource.
        foreach ($expectedAddedUsersIds as $userId) {
            $this->assertContains($userId, $secretsUsersIds);
            $this->assertContains($userId, $hasAccessUsersIds);
        }
        // Ensure that the removed users don't have a secret, and are no more allowed to access the resource.
        foreach ($expectedRemovedUsersIds as $userId) {
            $this->assertNotContains($userId, $secretsUsersIds);
            $this->assertNotContains($userId, $hasAccessUsersIds);
        }
    }

    public function testShareLostAccessFavoritesDeleted()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);

        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.apache');
        // Users
        $userDId = UuidFactory::uuid('user.id.dame');

        // Build the changes.
        $changes = [];
        $secrets = [];

        // Expected results.
        $expectedRemovedUsersIds = [];

        // Users permissions changes.
        // Delete the permission of the user Betty.
        $changes[] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userDId"), 'delete' => true];
        $expectedRemovedUsersIds[] = $userDId;

        // Share.
        $resource = $this->service->share($uac, $resourceId, $changes, $secrets);
        $this->assertFalse($resource->hasErrors());

        // Ensure the apache favorite for Dame is deleted
        // But the other favorites for this resource are not touched.
        $resources = $this->Favorites->find()
            ->where(['user_id' => $userDId])
            ->all();
        $resourcesId = Hash::extract($resources->toArray(), '{n}.foreign_key');
        $this->assertNotContains($resourceId, $resourcesId);
        $this->assertcontains(UuidFactory::uuid('resource.id.april'), $resourcesId);
    }

    public function testShareValidationError()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $resourceApacheId = UuidFactory::uuid('resource.id.apache');
        $resourceAprilId = UuidFactory::uuid('resource.id.april');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userEId = UuidFactory::uuid('user.id.edith');
        $testCases = [
            'cannot update a permission that does not exist' => [
                'errorField' => 'permissions.0.id.exists',
                'data' => [
                    'permissions' => [[
                        'id' => UuidFactory::uuid()]]],
            ],
            'cannot delete a permission of another resource' => [
                'errorField' => 'permissions.0.id.exists',
                'data' => [
                    'permissions' => [[
                        'id' => UuidFactory::uuid("permission.id.$resourceAprilId-$userAId"),
                        'delete' => true]],
                ],
            ],
            'cannot add a permission with invalid data' => [
                'errorField' => 'permissions.0.aro_foreign_key._empty',
                'data' => [
                    'permissions' => [
                        ['aro' => 'User', 'type' => Permission::OWNER]]],
            ],
            'cannot update a permission with a wrong permission type' => [
                'errorField' => 'permissions.0.type.inList',
                'data' => [
                    'permissions' => [[
                        'id' => UuidFactory::uuid("permission.id.$resourceApacheId-$userAId"), 'type' => 42]]],
            ],
            'cannot add a secret with invalid data' => [
                'errorField' => 'secrets.0.data.isValidOpenPGPMessage',
                'data' => [
                    'permissions' => [[
                        'aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::READ]],
                    'secrets' => [[
                        'user_id' => $userEId, 'data' => 'INVALID GPG MESSAGE']],
                ],
            ],
            // Test build rules.
            'cannot remove the latest owner' => [
                'errorField' => 'permissions.at_least_one_owner',
                'data' => [
                    'permissions' => [[
                        'id' => UuidFactory::uuid("permission.id.$resourceApacheId-$userAId"),
                        'delete' => true]],
                ],
            ],
            'cannot add a permissions for a deleted user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => [
                    'permissions' => [[
                        'aro' => 'User',
                        'aro_foreign_key' => UuidFactory::uuid('user.id.sofia'),
                        'type' => Permission::OWNER]]],
            ],
            'cannot add a permissions for an inactive user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => [
                    'permissions' => [[
                        'aro' => 'User',
                        'aro_foreign_key' => UuidFactory::uuid('user.id.ruth'),
                        'type' => Permission::OWNER]]],
            ],
        ];

        foreach ($testCases as $caseLabel => $case) {
            $permissions = Hash::get($case, 'data.permissions', []);
            $secrets = Hash::get($case, 'data.secrets', []);
            try {
                $this->service->share($uac, $resourceApacheId, $permissions, $secrets);
            } catch (ValidationException $e) {
                $this->assertEquals('Could not validate resource data.', $e->getMessage());
                $error = Hash::get($e->getErrors(), $case['errorField']);
                $this->assertNotNull($error, "Expected error not found : {$case['errorField']}. Errors: " . json_encode($e->getErrors()));

                continue;
            }
            $this->assertFalse(true, 'The test should throw an exception.');
        }
    }

    public function testShareErrorRuleResourceIsNotSoftDeleted()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $data = [[
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER]];

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The resource does not exist.');
        $this->service->share($uac, $resourceId, $data);
    }

    /* SHARE DRY RUN */

    public function testShareDryRunSuccess()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);

        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        // Users
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userEId = UuidFactory::uuid('user.id.edith');
        $userFId = UuidFactory::uuid('user.id.frances');
        $userJId = UuidFactory::uuid('user.id.jean');
        $userKId = UuidFactory::uuid('user.id.kathleen');
        $userLId = UuidFactory::uuid('user.id.lynne');
        $userMId = UuidFactory::uuid('user.id.marlyn');
        $userNId = UuidFactory::uuid('user.id.nancy');
        // Groups
        $groupBId = UuidFactory::uuid('group.id.board');
        $groupFId = UuidFactory::uuid('group.id.freelancer');
        $groupAId = UuidFactory::uuid('group.id.accounting');

        // Expected results.
        $expectedAddedUsersIds = [];
        $expectedRemovedUsersIds = [];

        // Build the changes.
        $changes = [];

        // Users permissions changes.
        // Change the permission of the user Ada to read (no users are expected to be added or removed).
        $changes[] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userAId"), 'type' => Permission::READ];
        // Delete the permission of the user Betty.
        $changes[] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"), 'delete' => true];
        $expectedRemovedUsersIds[] = $userBId;
        // Add an owner permission for the user Edith
        $changes[] = ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER];
        $expectedAddedUsersIds[] = $userEId;

        // Groups permissions changes.
        // Change the permission of the group Board (no users are expected to be added or removed).
        $changes[] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupBId"), 'type' => Permission::OWNER];
        // Delete the permission of the group Freelancer.
        $changes[] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupFId"), 'delete' => true];
        $expectedRemovedUsersIds = array_merge($expectedRemovedUsersIds, [$userJId, $userKId, $userLId, $userMId, $userNId]);
        // Add a read permission for the group Accounting.
        $changes[] = ['aro' => 'Group', 'aro_foreign_key' => $groupAId, 'type' => Permission::READ];
        $expectedAddedUsersIds = array_merge($expectedAddedUsersIds, [$userFId]);

        // Share dry run.
        $result = $this->service->shareDryRun($uac, $resourceId, $changes);
        $this->assertNotEmpty($result);
        $this->assertNotEmpty($result['added']);
        $addedUsersIds = $result['added'];
        $this->assertNotEmpty($result['deleted']);
        $removedUsersIds = $result['deleted'];

        // Assert the results.
        $this->assertCount(count($expectedAddedUsersIds), $addedUsersIds);
        $this->assertCount(count($expectedRemovedUsersIds), $removedUsersIds);
        $this->assertEmpty(array_diff($expectedAddedUsersIds, $addedUsersIds));
        $this->assertEmpty(array_diff($expectedRemovedUsersIds, $removedUsersIds));
    }

    /*
     * The format validation is done by the Permissions model.
     * @see App\Test\TestCase\Model\Table\Permissions\PatchEntitiesWithChangesTest
     */

    public function testShareDryRunValidationError()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $resourceApacheId = UuidFactory::uuid('resource.id.apache');
        $resourceAprilId = UuidFactory::uuid('resource.id.april');
        $userAId = UuidFactory::uuid('user.id.ada');
        $testCases = [
            // Check some validation format rules, just to ensure they are well returned by the
            // PatchEntitiesWithChanges function
            'cannot update a permission that does not exist' => [
                'errorField' => 'permissions.0.id.exists',
                'data' => [['id' => UuidFactory::uuid()]],
            ],
            'cannot delete a permission of another resource' => [
                'errorField' => 'permissions.0.id.exists',
                'data' => [[
                    'id' => UuidFactory::uuid("permission.id.$resourceAprilId-$userAId"),
                    'delete' => true]],
            ],
            'cannot add a permission with invalid data' => [
                'errorField' => 'permissions.0.aro_foreign_key._empty',
                'data' => [['aro' => 'User', 'type' => Permission::OWNER]],
            ],
            'cannot update a permission with a wrong permission type' => [
                'errorField' => 'permissions.0.type.inList',
                'data' => [['id' => UuidFactory::uuid("permission.id.$resourceApacheId-$userAId"), 'type' => 42]],
            ],
            // Test build rules.
            'cannot remove the latest owner' => [
                'errorField' => 'permissions.at_least_one_owner',
                'data' => [[
                    'id' => UuidFactory::uuid("permission.id.$resourceApacheId-$userAId"),
                    'delete' => true]],
            ],
            'cannot add a permissions for a deleted user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => UuidFactory::uuid('user.id.sofia'),
                    'type' => Permission::OWNER]],
            ],
            'cannot add a permissions for an inactive user' => [
                'errorField' => 'permissions.0.aro_foreign_key.aro_exists',
                'data' => [[
                    'aro' => 'User',
                    'aro_foreign_key' => UuidFactory::uuid('user.id.ruth'),
                    'type' => Permission::OWNER]],
            ],
        ];

        foreach ($testCases as $caseLabel => $case) {
            try {
                $this->service->shareDryRun($uac, $resourceApacheId, $case['data']);
            } catch (ValidationException $e) {
                $this->assertEquals('Could not validate resource data.', $e->getMessage());
                $error = Hash::get($e->getErrors(), $case['errorField']);
                $this->assertNotNull($error, "Expected error not found : {$case['errorField']}. Errors: " . json_encode($e->getErrors()));

                continue;
            }
            $this->assertFalse(true, 'The test should throw an exception.');
        }
    }

    public function testShareDryRunErrorRuleResourceIsSoftDeleted()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);

        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $data = [[
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
        ]];
        $this->expectException(NotFoundException::class);
        $this->service->shareDryRun($uac, $resourceId, $data);
    }
}
