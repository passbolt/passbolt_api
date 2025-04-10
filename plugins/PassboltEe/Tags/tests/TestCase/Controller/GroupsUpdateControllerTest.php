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

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\Model\ResourcesTagsModelTrait;
use Passbolt\Tags\Test\Lib\Model\TagsModelTrait;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class GroupsUpdateControllerTest extends TagPluginIntegrationTestCase
{
    use GroupsModelTrait;
    use ResourcesModelTrait;
    use ResourcesTagsModelTrait;
    use TagsModelTrait;

    public function testTagsGroupsUpdateControllerSuccess_RemoveTagWhenUserLoseAccess()
    {
        $users = [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsUsersFor($users)->persist();
        $groupUserB = $group->groups_users[1];
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$userA, $group])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagA */
        $tagA = TagFactory::make()->isPersonalFor($resource, $userA)->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagB */
        $tagB = TagFactory::make()->isPersonalFor($resource, $userB)->persist();

        // Remove userB from the group
        $changes[] = ['id' => $groupUserB->id, 'delete' => true];

        // Update the group users.
        $this->logInAsAdmin();
        $this->putJson("/groups/$group->id.json?api-version=v2", ['groups_users' => $changes]);
        $this->assertSuccess();

        // Assert TagA on resource R1
        $this->assertPersonalResourceTagExistsFor($resource->id, $tagA->id, $userA->id);
        $this->assertPersonalResourceTagNotExistFor($resource->id, $tagA->id, $userB->id);
        $this->assertPersonalResourceTagNotExistFor($resource->id, $tagB->id, $userB->id);
    }
}
