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

namespace Passbolt\Tags\Test\TestCase\Service\Tags;

use App\Model\Table\ResourcesTable;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Service\Tags\ResourcesTagsAddService;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

class ResourcesTagsAddServiceTest extends TagTestCase
{
    public ResourcesTagsAddService $service;
    public ResourcesTable $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->service = new ResourcesTagsAddService();
    }

    public function tearDown(): void
    {
        unset($this->Resources);
        unset($this->service);
        parent::tearDown();
    }

    /**
     * Given that two users have a tag of the same slug on the same resource
     * When user1 removes the tag from the resource
     * Then the tag of user1 is deleted and the one of user2 remains
     */
    public function testResourcesTagsAddService_Delete_A_Personal_Tag_Shared_On_A_Shared_Resource()
    {
        [$user1, $user2] = UserFactory::make(2)->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user1])->persist();
        TagFactory::make()->isPersonalFor($resource, $user1)->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagUser2 */
        $tagUser2 = TagFactory::make()->isPersonalFor($resource, $user2)->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = $this->Resources->findView(
            $user1->id,
            $resource->id,
            ['contain' => ['all_tags' => 1, 'permission' => 1]]
        )->first();

        $uac = $this->makeUac($user1);

        $tags = $this->service->add($uac, $resource, []);

        $this->assertEquals([], $tags);
        ResourcesTagFactory::firstOrFail([
            'resource_id' => $resource->id,
            'user_id' => $user2->id,
            'tag_id' => $tagUser2->id,
        ]);
        TagFactory::get($tagUser2->id);
        $this->assertSame(1, TagFactory::count());
        $this->assertSame(1, ResourcesTagFactory::count());
    }

    /**
     * Given that two users have a tag of the same slug on the same resource
     * When user1 removes the tag from the resource
     * Then the tag of user1 is deleted and the one of user2 remains
     */
    public function testResourcesTagsAddService_Add_A_Personal_Tag_Identical_To_Existing_Tag_Slug()
    {
        $users = [$user1, $user2] = UserFactory::make(2)->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor($users)
            ->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $existingTagUser1 */
        $existingTagUser1 = TagFactory::make()->isPersonalFor($resource, $user1)->persist();

        $uac = $this->makeUac($user2);

        $resource = $this->Resources->findView(
            $user2->id,
            $resource->id,
            ['contain' => ['all_tags' => 1, 'permission' => 1]]
        )->first();

        $tag = $this->service->add($uac, $resource, [
            'slug' => $existingTagUser1->slug,
        ])[0];

        $this->assertSame($existingTagUser1->slug, $tag['slug']);
        $this->assertNotEquals($existingTagUser1->id, $tag['id']);
        $this->assertSame(2, TagFactory::count());
        $this->assertSame(2, ResourcesTagFactory::count());
    }

    /**
     * Given that two users have a tag of the same slug on the same resource
     * When user1 removes the tag from the resource
     * Then the tag of user1 is deleted and the one of user2 remains
     */
    public function testResourcesTagsAddService_Edit_A_Personal_Tag_Identical_To_Existing_Tag_Slug()
    {
        [$user1, $user2] = UserFactory::make(2)->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user1, $user2])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagUser1 */
        $tagUser1 = TagFactory::make()->isPersonalFor($resource, $user1)->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagUser2 */
        TagFactory::make()->isPersonalFor($resource, $user2)->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = $this->Resources->findView(
            $user2->id,
            $resource->id,
            ['contain' => ['all_tags' => 1, 'permission' => 1]]
        )->first();

        $uac = $this->makeUac($user2);

        $tag = $this->service->add($uac, $resource, [
            'slug' => $tagUser1->slug,
        ])[0];

        $this->assertSame($tagUser1->slug, $tag['slug']);
        $this->assertNotEquals($tagUser1->id, $tag['id']);
        $this->assertSame(2, TagFactory::count());
        $this->assertSame(2, ResourcesTagFactory::count());
    }
}
