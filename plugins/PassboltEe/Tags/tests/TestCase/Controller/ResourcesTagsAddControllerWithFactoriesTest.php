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
 * @since         3.7.1
 */
namespace Passbolt\Tags\Test\TestCase\Controller;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourcesTagsAddControllerWithFactoriesTest extends TagPluginIntegrationTestCase
{
    public function testResourcesTagsAddController_Add_Two_Unshared_Tags_With_Identical_Name()
    {
        $users = [$user1, $user2] = UserFactory::make(2)->persist();
        GroupFactory::make()->withGroupsUsersFor($users)->persist();
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user1])
            ->withPermissionsFor([$user2], Permission::READ)
            ->persist();

        $tag = 'Foo';
        $data = ['tags' => [$tag]];

        $this->logInAs($user1);
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();
        $tag1Id = $this->_responseJsonBody[0]->id;

        $this->logInAs($user2);
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();
        $tag2Id = $this->_responseJsonBody[0]->id;

        // Only one Tag should be in the DB
        $this->assertSame(1, TagFactory::count());
        $this->assertSame($tag1Id, $tag2Id);
        // Two pivot table entries should be saved with each the respective user_id
        $this->assertSame(2, ResourcesTagFactory::count());
        $conditions = ['resource_id' => $resource->id, 'tag_id' => $tag1Id,];
        foreach ($users as $user) {
            $conditions['user_id'] = $user->id;
            $this->assertSame(1, ResourcesTagFactory::find()->where($conditions)->count());
        }
    }
}
