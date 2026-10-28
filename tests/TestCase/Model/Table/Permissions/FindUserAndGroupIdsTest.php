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
 * @since         5.18.0
 */
namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Table\PermissionsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;

/**
 * @covers \App\Model\Table\PermissionsTable
 */
class FindUserAndGroupIdsTest extends AppTestCase
{
    private PermissionsTable $Permissions;

    public function setUp(): void
    {
        parent::setUp();
        /** @var \App\Model\Table\PermissionsTable $Permissions */
        $Permissions = $this->fetchTable('Permissions');
        $this->Permissions = $Permissions;
    }

    public function tearDown(): void
    {
        unset($this->Permissions);
        parent::tearDown();
    }

    public function testFindUserAndGroupIds_Success_UserAndTheirGroups(): void
    {
        [$user, $otherUser] = UserFactory::make(2)->user()->persist();
        $groups = GroupFactory::make(2)->withGroupsUsersFor([$user])->persist();
        GroupFactory::make()->withGroupsUsersFor([$otherUser])->persist();

        $aroIds = $this->Permissions->findUserAndGroupIds($user->get('id'))
            ->all()
            ->extract('aro_id')
            ->toList();

        $expectedAroIds = [$user->get('id'), $groups[0]->get('id'), $groups[1]->get('id')];
        sort($expectedAroIds);
        sort($aroIds);
        $this->assertSame($expectedAroIds, $aroIds);
    }

    public function testFindUserAndGroupIds_Success_SeveralUsersCombinedInOneQuery(): void
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();

        $userIds = $this->fetchTable('Users')->find()
            ->select(['Users.id'])
            ->where(['OR' => [
                ['Users.id IN' => $this->Permissions->findUserAndGroupIds($userA->get('id'))],
                ['Users.id IN' => $this->Permissions->findUserAndGroupIds($userB->get('id'))],
            ]])
            ->all()
            ->extract('id')
            ->toList();

        $expectedUserIds = [$userA->get('id'), $userB->get('id')];
        sort($expectedUserIds);
        sort($userIds);
        $this->assertSame($expectedUserIds, $userIds);
    }
}
