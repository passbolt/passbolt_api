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
use App\Utility\UuidFactory;

/**
 * @covers \App\Controller\Groups\GroupsViewController
 */
class GroupsViewControllerWithFactoriesTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use GroupsUsersModelTrait;

    public function testGroupsViewController_Success(): void
    {
        $groupId = GroupFactory::make()->persist()->id;

        $this->logInAsUser();
        $this->getJson("/groups/$groupId.json");

        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        // Expected content
        $this->assertGroupAttributes($this->_responseJsonBody);
        // Not expected content.
        $this->assertObjectNotHasAttribute('modifier', $this->_responseJsonBody);
        $this->assertObjectNotHasAttribute('users', $this->_responseJsonBody);
        $this->assertObjectNotHasAttribute('my_group_user', $this->_responseJsonBody);
    }

    public function testGroupsViewController_Success_DeprecatedContain(): void
    {
        $urlParameter = 'contain[modifier]=1';
        $urlParameter .= '&contain[modifier.profile]=1';
        $urlParameter .= '&contain[user]=1';
        $urlParameter .= '&contain[group_user]=1';
        $urlParameter .= '&contain[group_user.user.profile]=1';
        $urlParameter .= '&contain[my_group_user]=1';
        $user = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$user])
            ->with('Modifier')
            ->persist();
        $groupId = $group->id;

        $this->logInAsUser();
        $this->getJson("/groups/{$groupId}.json?{$urlParameter}&api-version=2");

        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        // Expected content.
        $this->assertGroupAttributes($this->_responseJsonBody);
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->modifier);
        $this->assertObjectHasAttribute('profile', $this->_responseJsonBody->modifier);
        $this->assertProfileAttributes($this->_responseJsonBody->modifier->profile);
        $this->assertObjectHasAttribute('users', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->users[0]);
        $this->assertObjectHasAttribute('groups_users', $this->_responseJsonBody);
        $this->assertGroupUserAttributes($this->_responseJsonBody->groups_users[0]);
        $this->assertObjectHasAttribute('user', $this->_responseJsonBody->groups_users[0]);
        $this->assertObjectHasAttribute('profile', $this->_responseJsonBody->groups_users[0]->user);
        $this->assertProfileAttributes($this->_responseJsonBody->groups_users[0]->user->profile);
        $this->assertObjectHasAttribute('my_group_user', $this->_responseJsonBody);
        $this->assertNull($this->_responseJsonBody->my_group_user);

        /**
         * Check that the my_group_user attribute is not null for a group the user is member of
         */
        $this->logInAs($user);
        $this->getJson("/groups/{$groupId}.json?{$urlParameter}&api-version=2");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertObjectHasAttribute('my_group_user', $this->_responseJsonBody);
        $this->assertGroupUserAttributes($this->_responseJsonBody->my_group_user);
    }

    public function testGroupsViewController_Success_InUseContain(): void
    {
        $urlParameter = 'contain[modifier]=1';
        $urlParameter .= '&contain[modifier.profile]=1';
        $urlParameter .= '&contain[users]=1';
        $urlParameter .= '&contain[groups_users]=1';
        $urlParameter .= '&contain[groups_users.user.profile]=1';
        $urlParameter .= '&contain[my_group_user]=1';
        $user = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$user])
            ->with('Modifier')
            ->persist();
        $groupId = $group->id;

        $this->logInAsUser();
        $this->getJson("/groups/{$groupId}.json?{$urlParameter}&api-version=2");

        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        // Expected content
        $this->assertGroupAttributes($this->_responseJsonBody);
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->modifier);
        $this->assertObjectHasAttribute('profile', $this->_responseJsonBody->modifier);
        $this->assertProfileAttributes($this->_responseJsonBody->modifier->profile);
        $this->assertObjectHasAttribute('users', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->users[0]);
        $this->assertObjectHasAttribute('groups_users', $this->_responseJsonBody);
        $this->assertGroupUserAttributes($this->_responseJsonBody->groups_users[0]);
        $this->assertObjectHasAttribute('user', $this->_responseJsonBody->groups_users[0]);
        $this->assertObjectHasAttribute('profile', $this->_responseJsonBody->groups_users[0]->user);
        $this->assertProfileAttributes($this->_responseJsonBody->groups_users[0]->user->profile);
        $this->assertObjectHasAttribute('my_group_user', $this->_responseJsonBody);
        $this->assertNull($this->_responseJsonBody->my_group_user);

        /**
         * Check that the my_group_user attribute is not null for a group the user is member of
         */
        $this->logInAs($user);
        $this->getJson("/groups/{$groupId}.json?{$urlParameter}&api-version=2");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertObjectHasAttribute('my_group_user', $this->_responseJsonBody);
        $this->assertGroupUserAttributes($this->_responseJsonBody->my_group_user);
    }

    public function testGroupsViewController_Error_NotAuthenticated(): void
    {
        $this->getJson('/groups.json');
        $this->assertAuthenticationError();
    }

    public function testGroupsViewController_Error_NotValidId(): void
    {
        $this->logInAsUser();
        $groupId = 'invalid-id';
        $this->getJson("/groups/{$groupId}.json");
        $this->assertError(400, 'The group id is not valid.');
    }

    public function testGroupsViewController_Error_NotFound(): void
    {
        $this->logInAsUser();
        $groupId = UuidFactory::uuid();
        $this->getJson("/groups/{$groupId}.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testGroupsViewController_Error_DeletedGroup(): void
    {
        $this->logInAsUser();
        $groupId = GroupFactory::make()->deleted()->persist()->id;
        $this->getJson("/groups/{$groupId}.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testGroupsViewController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $groupId = GroupFactory::make()->persist()->id;
        $this->get("/groups/{$groupId}");
        $this->assertResponseCode(404);
    }
}
