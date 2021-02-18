<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\Tags\Test\TestCase\Controller;

use App\Model\Entity\Permission;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Test\Lib\Model\ResourcesTagsModelTrait;
use Passbolt\Tags\Test\Lib\Model\TagsModelTrait;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class GroupsUpdateControllerTest extends TagPluginIntegrationTestCase
{
    use GroupsModelTrait;
    use ResourcesModelTrait;
    use ResourcesTagsModelTrait;
    use TagsModelTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles',
        'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/Avatars',
        'app.Base/Resources', 'app.Base/Favorites',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions', 'app.Alt0/Secrets',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags',
    ];

    public $GroupsUsers;
    public $Resources;
    public $ResourcesTags;

    public function setUp()
    {
        parent::setUp();
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->ResourcesTags = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
    }

    public function tearDown()
    {
        unset($this->GroupsUsers);
        unset($this->Resources);
        unset($this->Tags);
        parent::tearDown();
    }

    public function testTagsGroupsUpdateControllerSuccess_RemoveTagWhenUserLoseAccess()
    {
        [$r1, $t1, $g1, $userAId, $userBId] = $this->insertFixture_RemoveTagWhenUserLoseAccess();

        // Remove user Betty from the group
        $groupUserB = $this->GroupsUsers->find('all')
            ->where(['user_id' => $userBId, 'group_id' => $g1->id])
            ->first();
        $changes[] = ['id' => $groupUserB->id, 'delete' => true];

        // Update the group users.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$g1->id.json?api-version=v2", ['groups_users' => $changes]);
        $this->assertSuccess();

        // Assert Tag 1 on resource R1
        $this->assertPersonalResourceTagExistsFor($r1->id, $t1->id, $userAId);
        $this->assertPersonalResourceTagNotExistFor($r1->id, $t1->id, $userBId);
    }

    private function insertFixture_RemoveTagWhenUserLoseAccess()
    {
        // Ada is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Ada has a personal tag T1 on R1
        // Betty has a personal tag T1 on R1
        // ---
        // R1 (Ada:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userBId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $t1 = $this->addResourcePersonalTagFor(['slug' => 'T1'], $r1->id, [$userAId, $userBId]);

        return [$r1, $t1, $g1, $userAId, $userBId];
    }
}
