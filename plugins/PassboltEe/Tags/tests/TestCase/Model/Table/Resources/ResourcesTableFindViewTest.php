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
 * @since         4.12.0
 */

namespace Passbolt\Tags\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

class ResourcesTableFindViewTest extends TagTestCase
{
    public ResourcesTable $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function tearDown(): void
    {
        unset($this->Resources);
        parent::tearDown();
    }

    public function testResourcesTableFindView_ViewAll()
    {
        [$user1, $user2,] = UserFactory::make(2)->persist();

        [$resource1, $resource2] = ResourceFactory::make(2)->withPermissionsFor([$user1])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()
            ->isPersonalFor($resource1, $user1)
            ->isPersonalFor($resource2, $user1)
            ->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $otherTagOnThisResource */
        $otherTagOnThisResource = TagFactory::make()->isPersonalFor($resource1, $user2)->persist();
        TagFactory::make()->isPersonalFor($resource2, $user2)->persist();

        $options = ['contain' => ['all_tags' => 1,]];
        /** @var \App\Model\Entity\Resource $result */
        $result = $this->Resources->findView($user1->id, $resource1->id, $options)->first();
        $tags = $result->toArray()['tags'];
        $tagIds = Hash::extract($tags, '{n}.id');
        $this->assertSame(2, count($tags));
        $this->assertContains($tag->id, $tagIds);
        $this->assertContains($otherTagOnThisResource->id, $tagIds);
    }

    public function testResourcesTableFindView_ViewTag()
    {
        [$user1, $user2,] = UserFactory::make(2)->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user1])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()
            ->isPersonalFor($resource, $user1)
            ->isPersonalFor($resource, $user2)
            ->persist();

        $options = ['contain' => ['tag' => 1,]];
        /** @var \App\Model\Entity\Resource $result */
        $result = $this->Resources->findView($user1->id, $resource->id, $options)->first();
        $tags = $result->toArray()['tags'];
        $this->assertSame(1, count($tags));
        foreach ($tags as $t) {
            $this->assertSame($tag->id, $t['id']);
        }
    }
}
