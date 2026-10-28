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

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Text;

/**
 * @covers \App\Model\Table\PermissionsTable
 */
class FindAcoIdsAccessibleByUserTest extends AppTestCase
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

    public function testFindAcoIdsAccessibleByUser_Success_DirectPermission(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $this->assertSame([$resource->id], $this->findAccessibleResourceIds($user->id));
    }

    public function testFindAcoIdsAccessibleByUser_Success_PermissionThroughGroup(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsUsersFor([$user])->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$group], Permission::READ)->persist();

        $this->assertSame([$resource->id], $this->findAccessibleResourceIds($user->id));
    }

    public function testFindAcoIdsAccessibleByUser_Success_DirectAndGroupsPermissionsReturnedOnce(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $groups = GroupFactory::make(2)->withGroupsUsersFor([$user])->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user, ...$groups], Permission::READ)
            ->persist();

        $this->assertSame([$resource->id], $this->findAccessibleResourceIds($user->id));
    }

    public function testFindAcoIdsAccessibleByUser_Success_ExcludesItemsOfOtherUsersAndGroups(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $otherUser = UserFactory::make()->user()->persist();
        $otherGroup = GroupFactory::make()->withGroupsUsersFor([$otherUser])->persist();
        ResourceFactory::make()->withPermissionsFor([$otherUser])->persist();
        ResourceFactory::make()->withPermissionsFor([$otherGroup])->persist();

        $this->assertSame([], $this->findAccessibleResourceIds($user->id));
    }

    public function testFindAcoIdsAccessibleByUser_Success_FiltersOnAcoType(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $folderId = Text::uuid();
        PermissionFactory::make([
            'aco' => PermissionsTable::FOLDER_ACO,
            'aco_foreign_key' => $folderId,
            'aro' => PermissionsTable::USER_ARO,
            'aro_foreign_key' => $user->id,
        ])->persist();

        $this->assertSame([$resource->id], $this->findAccessibleResourceIds($user->id));
        $folderIds = $this->Permissions
            ->findAcoIdsAccessibleByUser(PermissionsTable::FOLDER_ACO, $user->id)
            ->all()
            ->extract('aco_foreign_key')
            ->toList();
        $this->assertSame([$folderId], $folderIds);
    }

    public function testFindAcoIdsAccessibleByUser_Success_SeveralUsersCombinedInOneQuery(): void
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $groupA = GroupFactory::make()->withGroupsUsersFor([$userA])->persist();
        ResourceFactory::make()->withPermissionsFor([$groupA])->persist();
        ResourceFactory::make()->withPermissionsFor([$userB])->persist();
        $sharedResource = ResourceFactory::make()->withPermissionsFor([$groupA, $userB])->persist();

        $resourceIdsAccessibleByBoth = $this->fetchTable('Resources')->find()
            ->select(['Resources.id'])
            ->innerJoin(
                ['AccessibleByA' => $this->Permissions->findAcoIdsAccessibleByUser(PermissionsTable::RESOURCE_ACO, $userA->get('id'))],
                ['AccessibleByA.aco_foreign_key' => new IdentifierExpression('Resources.id')],
            )
            ->innerJoin(
                ['AccessibleByB' => $this->Permissions->findAcoIdsAccessibleByUser(PermissionsTable::RESOURCE_ACO, $userB->get('id'))],
                ['AccessibleByB.aco_foreign_key' => new IdentifierExpression('Resources.id')],
            )
            ->all()
            ->extract('id')
            ->toList();

        $this->assertSame([$sharedResource->get('id')], $resourceIdsAccessibleByBoth);
    }

    public function testFindAcoIdsAccessibleByUser_Error_InvalidUserId(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The identifier should be a valid UUID.');

        $this->Permissions->findAcoIdsAccessibleByUser(PermissionsTable::RESOURCE_ACO, 'not-a-uuid');
    }

    /**
     * @param string $userId The user id.
     * @return array<string>
     */
    private function findAccessibleResourceIds(string $userId): array
    {
        return $this->Permissions
            ->findAcoIdsAccessibleByUser(PermissionsTable::RESOURCE_ACO, $userId)
            ->all()
            ->extract('aco_foreign_key')
            ->toList();
    }
}
