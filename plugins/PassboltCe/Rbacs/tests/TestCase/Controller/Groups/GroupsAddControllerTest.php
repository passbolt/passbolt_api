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
 * @since         5.8.0
 */

namespace Passbolt\Rbacs\Test\TestCase\Controller\Groups;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\RbacsPlugin;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Lib\RbacsIntegrationTestCase;

/**
 * @covers \App\Controller\Groups\GroupsAddController
 */
class GroupsAddControllerTest extends RbacsIntegrationTestCase
{
    use EmailQueueTrait;

    public function testGroupsAddController_Success_As_Admin(): void
    {
        [$user, $userToAdd] = UserFactory::make(2)->admin()->persist();
        $name = 'My group';
        $groupsUsers = [
            ['user_id' => $userToAdd->id, 'is_admin' => 1],
        ];
        $this->logInAs($user);

        $data = ['name' => $name, 'groups_users' => $groupsUsers];
        $this->postJson('/groups.json', $data);

        $this->assertResponseSuccess();

        $this->assertEmailInBatchContains([
            $user->profile->first_name . ' added you to the group ' . $name,
        ]);
    }

    public function testGroupsAddController_Success_As_User_With_Rbac_Control(): void
    {
        [$user, $userToAdd] = UserFactory::make(2)->persist();
        $action = ActionFactory::make()->name('GroupsAdd.addPost')->persist();
        RbacFactory::make()
            ->setAction($action)
            ->setField('role_id', $user->role_id)
            ->persist();
        $name = 'My group';
        $groupsUsers = [
            ['user_id' => $userToAdd->id, 'is_admin' => 1],
        ];
        $this->logInAs($user);

        $data = ['name' => $name, 'groups_users' => $groupsUsers];
        $this->postJson('/groups.json', $data);

        $this->assertResponseSuccess();
        $this->assertEmailInBatchContains([
            $user->profile->first_name . ' added you to the group ' . $name,
        ]);
    }

    public function testGroupsAddController_RBACS_Plugin_Disabled(): void
    {
        $this->disableFeaturePlugin(RbacsPlugin::class);
        [$user, $userToAdd] = UserFactory::make(2)->persist();
        $action = ActionFactory::make()->name('GroupsAdd.addPost')->persist();
        RbacFactory::make()
            ->setAction($action)
            ->setField('role_id', $user->role_id)
            ->persist();
        $name = 'My group';
        $groupsUsers = [
            ['user_id' => $userToAdd->id, 'is_admin' => 1],
        ];
        $this->logInAs($user);

        $data = ['name' => $name, 'groups_users' => $groupsUsers];
        $this->postJson('/groups.json', $data);
        $this->assertForbiddenError('You are not authorized to access that location.');
    }

    public function testGroupsAddController_User_With_No_Rbac(): void
    {
        [$user, $userToAdd] = UserFactory::make(2)->persist();
        $action = ActionFactory::make()->name('GroupsAdd.addPost')->persist();
        RbacFactory::make()
            ->setAction($action)
            ->setField('role_id', $user->role_id)
            ->deny()
            ->persist();
        $name = 'My group';
        $groupsUsers = [
            ['user_id' => $userToAdd->id, 'is_admin' => 1],
        ];
        $this->logInAs($user);

        $data = ['name' => $name, 'groups_users' => $groupsUsers];
        $this->postJson('/groups.json', $data);
        $this->assertForbiddenError('You are not authorized to access that location.');
    }
}
