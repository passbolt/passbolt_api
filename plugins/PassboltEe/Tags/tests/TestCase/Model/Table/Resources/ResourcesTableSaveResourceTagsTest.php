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
use Passbolt\Tags\Model\Table\TagsTable;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

class ResourcesTableSaveResourceTagsTest extends TagTestCase
{
    public ResourcesTable $Resources;
    public TagsTable $Tags;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
    }

    public function tearDown(): void
    {
        unset($this->Resources);
        unset($this->Tags);
        parent::tearDown();
    }

    /**
     * Given that a tag is used by two users as personal on the same resource
     * When a use removes their tag from the resource
     * Then the second user should keep their tag
     */
    public function testResourcesTableSaveResourceTags_Remove_Resources_Tag_Unshared()
    {
        $user = UserFactory::make()->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        TagFactory::make()
            ->isPersonalFor($resource, $user)
            ->persist();

        $resource = $this->Resources->get($resource->id);
        $tags = [];

        $resource->set('tags', $tags);
        $resource->setDirty('tags');
        $resource->setAccess('tags', true);

        $saveOptions = ['associated' => ['Tags._joinData']];
        $this->Resources->saveOrFail($resource, $saveOptions);

        $this->assertSame(1, TagFactory::count());
        $this->assertSame(0, ResourcesTagFactory::count());
    }

    /**
     * Given that a tag is used by two users as personal on the same resource
     * When a use removes their tag from the resource
     * Then the second user should keep their tag
     */
    public function testResourcesTableSaveResourceTags_Remove_Resources_Tag_On_Multiple_Resources_Not_Shared()
    {
        [$user1, $user2,] = UserFactory::make(2)->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)->persist();
        TagFactory::make()->isPersonalFor($resource1, $user1)->persist();
        TagFactory::make()->isPersonalFor($resource2, $user2)->persist();

        $resource1 = $this->Resources->get($resource1->id);
        $tags = [];

        $resource1->set('tags', $tags);
        $resource1->setDirty('tags');
        $resource1->setAccess('tags', true);

        $saveOptions = ['associated' => ['Tags._joinData']];
        $this->Resources->saveOrFail($resource1, $saveOptions);

        ResourcesTagFactory::firstOrFail([
            'resource_id' => $resource2->id,
            'user_id' => $user2->id,
        ]);
        $this->assertSame(2, TagFactory::count());
        $this->assertSame(1, ResourcesTagFactory::count());
    }

    /**
     * Given that a tag is used by two users as personal on the same resource
     * When a use removes their tag from the resource
     * Then the second user should keep their tag
     */
    public function testResourcesTableSaveResourceTags_Remove_Resources_Tag_On_Shared_Resource()
    {
        [$user1, $user2,] = UserFactory::make(2)->persist();

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user1])->persist();
        TagFactory::make()->isPersonalFor($resource, $user1)->persist();
        $user2Tag = TagFactory::make()->isPersonalFor($resource, $user2)->persist();

        $resource = $this->Resources->get($resource->id);
        $resourcesTag = ResourcesTagFactory::firstOrFail(['user_id' => $user2->id]);
        $tags = [
            $user2Tag->set('_joinData', $resourcesTag),
        ];

        $resource->set('tags', $tags);
        $resource->setDirty('tags');
        $resource->setAccess('tags', true);

        $saveOptions = ['associated' => ['Tags._joinData']];
        $result = $this->Resources->saveOrFail($resource, $saveOptions);
        $this->assertSame($user2->id, $result['tags'][0]['_joinData']['user_id']);

        ResourcesTagFactory::firstOrFail([
            'resource_id' => $resource->id,
            'user_id' => $user2->id,
        ]);
        $this->assertSame(2, TagFactory::count());
        $this->assertSame(1, ResourcesTagFactory::count());
    }
}
