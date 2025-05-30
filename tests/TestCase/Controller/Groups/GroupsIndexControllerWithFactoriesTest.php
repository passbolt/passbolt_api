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
 * @since         4.10.0
 */

namespace App\Test\TestCase\Controller\Groups;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use Cake\Utility\Hash;

/**
 * @covers \App\Controller\Groups\GroupsIndexController
 */
class GroupsIndexControllerWithFactoriesTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use GroupsUsersModelTrait;

    public function testGroupsIndexController_Success(): void
    {
        GroupFactory::make(3)->persist();
        $this->logInAsUser();

        $this->getJson('/groups.json');

        $this->assertSuccess();
        $this->assertCount(3, $this->_responseJsonBody);
        // Expected content.
        $this->assertGroupAttributes($this->_responseJsonBody[0]);
        // Make sure these keys are not present in the response
        $this->assertObjectNotHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('users', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('groups_users', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('my_group_user', $this->_responseJsonBody[0]);
    }

    public function testGroupsIndexController_Success_WithContain(): void
    {
        $user = UserFactory::make()->user()->persist();
        $groupA = GroupFactory::make()->persist();
        GroupFactory::make()
            ->withGroupsUsersFor([$user])
            ->with('Modifier')
            ->persist();
        $this->logInAs($user);

        $urlParameter = http_build_query([
            'contain' => [
                'modifier' => 1,
                'modifier.profile' => 1,
                'user' => 1,
                'group_user' => 1,
                'my_group_user' => 1,
            ],
            'api-version' => 2,
        ]);
        $this->getJson("/groups.json?{$urlParameter}");

        $this->assertSuccess();
        $this->assertCount(2, $this->_responseJsonBody);
        $responseGroupA = null;
        $responseGroupB = null;
        foreach ($this->_responseJsonBody as $response) {
            if ($response->id === $groupA->get('id')) {
                $responseGroupA = $response;
            } else {
                $responseGroupB = $response;
            }
        }
        // A group user is not a member
        $this->assertNull($responseGroupA->my_group_user);
        // A group user is a member
        $this->assertGroupAttributes($responseGroupB);
        $this->assertObjectHasAttribute('modifier', $responseGroupB);
        $this->assertUserAttributes($responseGroupB->modifier);
        $this->assertObjectHasAttribute('profile', $responseGroupB->modifier);
        $this->assertProfileAttributes($responseGroupB->modifier->profile);
        $this->assertObjectHasAttribute('users', $responseGroupB);
        $this->assertUserAttributes($responseGroupB->users[0]);
        $this->assertObjectHasAttribute('groups_users', $responseGroupB);
        $this->assertGroupUserAttributes($responseGroupB->groups_users[0]);
        $this->assertObjectHasAttribute('my_group_user', $responseGroupB);
        $this->assertGroupUserAttributes($responseGroupB->my_group_user);
    }

    public function testGroupsIndexController_Success_FilterHasUsers(): void
    {
        $user = UserFactory::make()->user()->persist();
        [$groupA, $groupB, $groupC] = GroupFactory::make(3)->withGroupsManagersFor([$user])->persist();
        $this->logInAsUser();

        $urlParameter = 'filter[has-users]=' . $user->id;
        $this->getJson("/groups.json?{$urlParameter}&api-version=2");

        $this->assertSuccess();
        $this->assertCount(3, $this->_responseJsonBody);
        $groupsIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedGroupsIds = [$groupA->id, $groupB->id, $groupC->id];
        $this->assertEqualsCanonicalizing($expectedGroupsIds, $groupsIds);
    }

    public function testGroupsIndexController_Success_FilterHasUsers_UpperCase(): void
    {
        $user = UserFactory::make()->user()->persist();
        GroupFactory::make(3)->withGroupsManagersFor([$user])->persist();
        $this->logInAsUser();

        $urlParameter = 'filter[has-users]=' . strtoupper($user->id);
        $this->getJson("/groups.json?{$urlParameter}&api-version=2");

        $this->assertSuccess();
        $this->assertCount(3, $this->_responseJsonBody);
    }

    public function testGroupsIndexController_Success_FilterHasManagers(): void
    {
        $user = UserFactory::make()->user()->persist();
        [$groupA, $groupB] = GroupFactory::make(2)->withGroupsManagersFor([$user])->persist();
        $this->logInAsUser();

        $urlParameter = 'filter[has-managers]=' . $user->id;
        $this->getJson("/groups.json?$urlParameter&api-version=2");

        $this->assertSuccess();
        $this->assertCount(2, $this->_responseJsonBody);
        $groupsIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedGroupsIds = [$groupA->id, $groupB->id];
        $this->assertEqualsCanonicalizing($expectedGroupsIds, $groupsIds);
    }

    public function testGroupIndexController_Error_NotAuthenticated(): void
    {
        $this->getJson('/groups.json');
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testGroupIndexController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->delete('/groups');
        $this->assertResponseCode(404);
    }
}
