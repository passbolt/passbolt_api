<?php
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

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class GroupsUpdateControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Profiles',
        'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/Avatars',
        'app.Base/Resources', 'app.Base/Favorites',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions', 'app.Alt0/Secrets',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags',
        'app.Base/EmailQueue', 'app.Base/OrganizationSettings',
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

    public function testLostAccessAssociatedDataDeleted()
    {
        // Define actors of this tests
        $groupId = UuidFactory::uuid('group.id.board');
        $userAId = UuidFactory::uuid('user.id.ada');

        // Remove users from the group
        $groupUser = $this->GroupsUsers->find('all')
            ->where(['user_id' => $userAId, 'group_id' => $groupId])
            ->first();
        $changes[] = ['id' => $groupUser->id, 'delete' => true];

        // Update the group users.
        $this->authenticateAs('admin');
        $this->putJson("/groups/$groupId.json?api-version=v2", ['groups_users' => $changes]);
        $this->assertSuccess();

        // Ensure the chai tag for Ada is deleted
        // But the other tags of Ada for the other resources are still in db.
        $resources = $this->ResourcesTags->find()
            ->where(['user_id' => $userAId])
            ->all();
        $resourcesId = Hash::extract($resources->toArray(), '{n}.resource_id');
        $this->assertNotContains(UuidFactory::uuid('resource.id.chai'), $resourcesId);
        $this->assertcontains(UuidFactory::uuid('resource.id.grogle'), $resourcesId);
    }
}
