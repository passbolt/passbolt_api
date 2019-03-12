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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Groups;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use App\Utility\UuidFactory;

class GroupsViewControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use GroupsUsersModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Groups',
        'app.Base/GroupsUsers', 'app.Base/Gpgkeys', 'app.Base/Permissions'
    ];

    public function testGroupsViewSuccess()
    {
        $this->authenticateAs('ada');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->getJson("/groups/$groupId.json?api-version=2");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected content.
        $this->assertGroupAttributes($this->_responseJsonBody);
        // Not expected content.
        $this->assertObjectNotHasAttribute('modifier', $this->_responseJsonBody);
        $this->assertObjectNotHasAttribute('users', $this->_responseJsonBody);
        $this->assertObjectNotHasAttribute('group_user', $this->_responseJsonBody);
        $this->assertObjectNotHasAttribute('my_group_user', $this->_responseJsonBody);
    }

    public function testGroupsViewApiV1Success()
    {
        $this->authenticateAs('ada');
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->getJson("/groups/$groupId.json");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected fields.
        $this->assertObjectHasAttribute('Group', $this->_responseJsonBody);
        $this->assertGroupAttributes($this->_responseJsonBody->Group);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('Modifier', $this->_responseJsonBody);
        $this->assertObjectNotHasAttribute('User', $this->_responseJsonBody);
    }

    public function testGroupsViewContainSuccess()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'contain[modifier]=1';
        $urlParameter .= '&contain[modifier.profile]=1';
        $urlParameter .= '&contain[user]=1';
        $urlParameter .= '&contain[group_user]=1';
        $urlParameter .= '&contain[group_user.user.profile]=1';
        $urlParameter .= '&contain[my_group_user]=1';
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->getJson("/groups/$groupId.json?$urlParameter&api-version=2");
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

        // Check that the my_group_user attribute is not null for a group the user is member of
        $this->authenticateAs('hedy');
        $groupId = UuidFactory::uuid('group.id.board');
        $this->getJson("/groups/$groupId.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);
        $this->assertObjectHasAttribute('my_group_user', $this->_responseJsonBody);
        $this->assertGroupUserAttributes($this->_responseJsonBody->my_group_user);
    }

    public function testGroupsViewContainApiV1SSuccess()
    {
        $this->authenticateAs('ada');
        $urlParameter = 'contain[modifier]=1&contain[user]=1&contain[group_user]=1&contain[group_user.user.profile]=1';
        $groupId = UuidFactory::uuid('group.id.freelancer');
        $this->getJson("/groups/$groupId.json?$urlParameter");
        $this->assertSuccess();
        $this->assertNotNull($this->_responseJsonBody);

        // Expected content.
        $this->assertObjectHasAttribute('Group', $this->_responseJsonBody);
        $this->assertGroupAttributes($this->_responseJsonBody->Group);
        $this->assertObjectHasAttribute('Modifier', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->Modifier);
        $this->assertObjectHasAttribute('User', $this->_responseJsonBody);
        $this->assertUserAttributes($this->_responseJsonBody->User[0]);
        $this->assertObjectHasAttribute('GroupUser', $this->_responseJsonBody);
        $this->assertGroupUserAttributes($this->_responseJsonBody->GroupUser[0]);
        $this->assertObjectHasAttribute('User', $this->_responseJsonBody->GroupUser[0]);
        $this->assertObjectHasAttribute('Profile', $this->_responseJsonBody->GroupUser[0]->User);
        $this->assertProfileAttributes($this->_responseJsonBody->GroupUser[0]->User->Profile);
    }

    public function testGroupsViewErrorNotAuthenticated()
    {
        $this->getJson('/groups.json');
        $this->assertAuthenticationError();
    }

    public function testGroupsViewErrorNotValidId()
    {
        $this->authenticateAs('ada');
        $groupId = 'invalid-id';
        $this->getJson("/groups/$groupId.json");
        $this->assertError(400, 'The group id is not valid.');
    }

    public function testGroupsViewErrorNotFound()
    {
        $this->authenticateAs('ada');
        $groupId = UuidFactory::uuid('not-found');
        $this->getJson("/groups/$groupId.json");
        $this->assertError(404, 'The group does not exist.');
    }

    public function testGroupsViewErrorDeletedGroup()
    {
        $this->authenticateAs('ada');
        $groupId = UuidFactory::uuid('group.id.deleted');
        $this->getJson("/groups/$groupId.json");
        $this->assertError(404, 'The group does not exist.');
    }
}
