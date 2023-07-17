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
 * @since         3.7.0
 */

namespace Passbolt\Tags\Test\TestCase\Service\GroupsUsers;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Service\GroupsUsers\HandleGroupUserDeletedService;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\Model\ResourcesTagsModelTrait;
use Passbolt\Tags\Test\Lib\Model\TagsModelTrait;
use Passbolt\Tags\Test\Lib\TagTestCase;

/**
 * Passbolt\Tags\Service\Groups\HandleGroupUserDeletedServices Test Case
 *
 * @uses \Passbolt\Tags\Service\GroupsUsers\HandleGroupUserDeletedService
 */
class HandleGroupUserDeletedServiceTest extends TagTestCase
{
    use TagsModelTrait;
    use ResourcesTagsModelTrait;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var HandleGroupUserDeletedService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->service = new HandleGroupUserDeletedService();
    }

    public function testHandleGroupUserDeletedServiceSuccess_RemoveResourcesTagsForLostResourceAccess(): void
    {
        [$u1, $u2, $g1, $r1] = $this->insertFixture_2GroupUsers_1SharedResource_TaggedWith2Tags();
        // Hard delete u2 from the group g1
        $this->groupsUsersTable->delete($g1->groups_users[1]);

        $this->service->handle($g1->groups_users[1]);

        $this->assertEquals(1, TagFactory::count());
        $this->assertEquals(1, ResourcesTagFactory::count());
        $this->assertPersonalResourceTagExistsFor($r1->id, $r1->resources_tags[0]->tag->id, $u1->id);
        $this->assertTagExists($r1->resources_tags[0]->tag->id);
    }

    public function testHandleGroupUserDeletedServiceSuccess_RemoveResourcesTagsAndUnusedForLostResourceAccess(): void
    {
        [$u1, $u2, $g1, $r1] = $this->insertFixture_2GroupUsers_1SharedResource_TaggedWith2Tags();
        // Hard delete u2 from the group g1
        $this->groupsUsersTable->delete($g1->groups_users[1]);

        $this->service->handle($g1->groups_users[1]);

        $this->assertEquals(1, TagFactory::count());
        $this->assertEquals(1, ResourcesTagFactory::count());
        $this->assertPersonalResourceTagExistsFor($r1->id, $r1->resources_tags[0]->tag->id, $u1->id);
        $this->assertTagExists($r1->resources_tags[0]->tag->id);
    }

    public function testHandleGroupUserDeletedServiceSuccess_DontRemoveResourcesTagsAndUnusedForKeptResourceAccess(): void
    {
        [$u1, $u2, $g1, $r1] = $this->insertFixture_2GroupUsers_1SharedResource_TaggedWith2Tags();
        PermissionFactory::make()->acoResource($r1)->aroUser($u2)->persist();
        // Hard delete u2 from the group g1
        $this->groupsUsersTable->delete($g1->groups_users[1]);

        $this->service->handle($g1->groups_users[1]);

        $this->assertEquals(2, TagFactory::count());
        $this->assertEquals(2, ResourcesTagFactory::count());
        $this->assertPersonalResourceTagExistsFor($r1->id, $r1->get('resources_tags')[0]->tag->id, $u1->id);
        $this->assertPersonalResourceTagExistsFor($r1->id, $r1->get('resources_tags')[1]->tag->id, $u2->id);
        $this->assertTagExists($r1->get('resources_tags')[0]->tag->id);
        $this->assertTagExists($r1->get('resources_tags')[1]->tag->id);
    }

    /* ******************************************
     * Fixtures
     ****************************************** */

    protected function insertFixture_2GroupUsers_1SharedResource_TaggedWith2Tags(): array
    {
        [$user1, $user2] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user1, $user2])->persist();

        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->with(
                'ResourcesTags',
                ResourcesTagFactory::make()->with('Users', $user1)->with('Tags')
            )
            ->with(
                'ResourcesTags',
                ResourcesTagFactory::make()->with('Users', $user2)->with('Tags')
            )
            ->persist();

        return [$user1, $user2, $group, $resource];
    }
}
