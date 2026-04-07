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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Groups;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUpdateDryRunControllerTest extends AppIntegrationTestCase
{
    /**
     * @var \App\Model\Table\GroupsTable
     */
    protected $Groups;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    /*
     * Tested scenarios :
     *
     * - Update membership type of users
     *   => expected result: no secret are requested for deletion or encryption
     *
     * - Remove a user who will lose access to all the resources a group has access
     *   => expected result: all the secrets of the resources the group has access should be requested for deletion
     *
     * - Remove a user who will lose access to the resources a group has access, except the ones the user has direct
     *   access or inherits access from another group
     *   => expected result: only the secrets the user does not have access through another source should be requested
     *      for deletion
     *
     * - Add a user who will gain access to the resources a group has access
     *   => expected result: all the secrets of the resources the group has access should be requested for encryption
     *
     * - Add a user who will gain access to the resources a group has access, a user who already has access to all the
     *   resources the group has access
     *   => expected result: no secret are requested for encryption
     *
     * - Add a user who will gain access to the resources a group has access, a user who already has access to some of
     *   the resources the group has access
     *   => expected result: only the secrets the user does not have already access through another source should be
     *      requested for encryption
     */

    public function testGroupsUpdateDryRunAsGroupManagerSuccess(): void
    {
        // Define actors of this tests
        [$j, $k, $l, $m, $n, $a, $c, $f] = UserFactory::make(8)->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$j])->withGroupsUsersFor([$k, $l, $m, $n])->persist();
        [$fosdem, $grunt] = ResourceFactory::make(2)
            ->withPermissionsFor([$group, $c])
            ->withSecretsFor([$group, $c])
            ->persist();
        $chai = ResourceFactory::make()->withPermissionsFor([$group, $c, $l])
            ->withSecretsFor([$group, $c, $l])
            ->persist();
        ResourceFactory::make(4)->withPermissionsFor([$group, $c, $a])->withSecretsFor([$group, $c, $a])->persist();

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($group->id)->all()->toArray();
        $groupHasAccess = Hash::extract($resources, '{n}.id');

        // Build the request data.
        $changes = [];

        // Build group users map
        $groupUsersMap = TableRegistry::getTableLocator()->get('GroupsUsers')
            ->find()
            ->where(['group_id' => $group->id])
            ->all()
            ->combine('user_id', 'id')
            ->toArray();

        // Update memberships.
        // Remove Jean as admin
        $changes[] = ['id' => $groupUsersMap[$j->id], 'is_admin' => false];
        // Make Kathleen admin
        $changes[] = ['id' => $groupUsersMap[$n->id], 'is_admin' => true];

        // Remove users from the group
        // Remove Kathleen who has access to the group resources only because of her membership.
        $changes[] = ['id' => $groupUsersMap[$k->id], 'delete' => true];

        // Remove a user who has its own access to the same resource the group has.
        // Remove lynne who has a direct access to the resource chai.
        $changes[] = ['id' => $groupUsersMap[$l->id], 'delete' => true];

        // Add a user who has not access to the group resources before adding it to the group.
        // Add Frances.
        $changes[] = ['user_id' => $f->id];

        // Add a user who already has access to all of the resources the group has access.
        // Carol has the same access as the group Freelancer.
        // No secret need to be encrypted for the user.
        $changes[] = ['user_id' => $c->id];

        // Add a user who already has access to some of the resources the group has access.
        // Ada already has access to few resources the group has access : chai, fosdem, grunt
        // Expect the secrets Ada had no access to be encrypted.
        $changes[] = ['user_id' => $a->id];

        // Update the group users.
        $this->logInAs($j);
        $this->putJson("/groups/$group->id/dry-run.json", ['groups_users' => $changes]);

        $this->assertSuccess();
        $result = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($result);

        // Extract from the result the secrets to add and the secrets of the current users that will be used to encrypt
        // the secret of the new users.
        $this->assertNotEmpty($result['dry-run']);
        $this->assertNotEmpty($result['dry-run']['SecretsNeeded']);
        $secretsToAdd = $result['dry-run']['SecretsNeeded'];
        $this->assertNotEmpty($result['dry-run']['Secrets']);
        $secretsToEncrypt = $result['dry-run']['Secrets'];

        // Assert that all the secrets that need to be added are in the result.
        $expectedSecretsToAdd = [];

        // Membership of Jean and Nancy are changed. Nothing expected.
        // kathleen should not have anymore access to the group resources. Nothing expected.
        // Lynne should not have anymore access to the group resources (except chai). Nothing expected.
        // Frances should have access to the group resources.
        foreach ($groupHasAccess as $resourceId) {
            $expectedSecretsToAdd[] = ['resource_id' => $resourceId, 'user_id' => $f->id];
        }
        // Carol already have access to the resources, nothing expected.
        // Ada should have access to the group resources.
        $userHasNotAccess = [$chai->id, $fosdem->id, $grunt->id];
        foreach ($userHasNotAccess as $resourceId) {
            $expectedSecretsToAdd[] = ['resource_id' => $resourceId, 'user_id' => $a->id];
        }

        // Assert the added secrets are as expected.
        $this->assertCount(count($expectedSecretsToAdd), $secretsToAdd);
        foreach ($expectedSecretsToAdd as $secret) {
            $extract = Hash::extract($secretsToAdd, "{n}.Secret[user_id={$secret['user_id']}][resource_id={$secret['resource_id']}]");
            $this->assertNotEmpty($extract);
        }

        // Assert that all the secrets that will be used to encrypt the secret to add are in the result.
        $expectedSecretsToEncryptIds = Hash::extract($secretsToAdd, '{n}.Secret.resource_id');
        $expectedSecretsToEncryptIds = array_unique($expectedSecretsToEncryptIds);
        $secretsToEncryptIds = Hash::extract($secretsToEncrypt, '{n}.Secret.0.resource_id');
        $this->assertCount(count($expectedSecretsToEncryptIds), $secretsToEncryptIds);
        $this->assertEmpty(array_diff($expectedSecretsToEncryptIds, $secretsToEncryptIds));
    }

    public function testGroupsUpdateDryRunAsAdminSuccess(): void
    {
        // Define actors of this tests
        $group = GroupFactory::make()->persist();
        $user = UserFactory::make()->user()->persist();

        // Try to add the user Ada.
        $data = [
            'name' => 'Name changed',
            'groups_users' => [['user_id' => $user->id]],
        ];

        // Update the group name.
        $this->logInAsAdmin();
        $this->putJson("/groups/$group->id/dry-run.json", $data);
        $this->assertSuccess();

        // No secrets should be requested nor source secrets given.
        $result = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($result['dry-run']);
        $this->assertEmpty($result['dry-run']['SecretsNeeded']);
        $this->assertEmpty($result['dry-run']['Secrets']);
    }
}
