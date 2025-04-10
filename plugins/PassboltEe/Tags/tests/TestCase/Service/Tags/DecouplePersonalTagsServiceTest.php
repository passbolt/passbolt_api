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
 * @since         4.12.0
 */

namespace Passbolt\Tags\Test\TestCase\Service\Tags;

use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Tags\Service\Tags\DecouplePersonalTagsService;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

class DecouplePersonalTagsServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private DecouplePersonalTagsService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new DecouplePersonalTagsService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testDecouplePersonalTagsService_No_Shared_Personal_Tags_To_Decouple(): void
    {
        [$user1, $user2] = UserFactory::make(2)->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)->persist();
        // Create personal tags used by one single user on two resources
        /** @var \Passbolt\Tags\Model\Entity\Tag $neverSharedPersonalTag1 */
        TagFactory::make()
            ->setField('slug', 'never-shared')
            ->isPersonalFor($resource1, $user1)
            ->isPersonalFor($resource2, $user1)
            ->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $neverSharedPersonalTag2 */
        TagFactory::make()
            ->setField('slug', 'never-shared2')
            ->isPersonalFor($resource1, $user2)
            ->isPersonalFor($resource2, $user2)
            ->persist();

        $nTagsCreated = $this->service->decouple();

        $this->assertSame(0, $nTagsCreated);
    }

    public function testDecouplePersonalTagsService_Develop_Shared_Personal_Tags_Success(): void
    {
        $users = [$user1, $user2, $user3] = UserFactory::make(3)->persist();
        $resources = [$resource1, $resource2] = ResourceFactory::make(2)->persist();
        // Create a personal tag used by one single user on two resources
        /** @var \Passbolt\Tags\Model\Entity\Tag $neverSharedPersonalTag */
        $neverSharedPersonalTag = TagFactory::make()
            ->setField('slug', 'never-shared')
            ->isPersonalFor($resource1, $user1)
            ->isPersonalFor($resource2, $user1)
            ->persist();
        // Create a personal tag shared by three users as it was possible prior to v12
        /** @var \Passbolt\Tags\Model\Entity\Tag $previouslySharedPersonalTag */
        $previouslySharedPersonalTag = TagFactory::make()
            ->setField('slug', 'formerly-shared')
            ->isPersonalFor($resource2, $user1)
            ->persist();
        ResourcesTagFactory::make()
            ->with('Tags', $previouslySharedPersonalTag)
            ->with('Users', $user2)
            ->persist();
        ResourcesTagFactory::make()
            ->with('Tags', $previouslySharedPersonalTag)
            ->with('Users', $user3)
            ->persist();
        // Create a tag without pivot entries in DB
        TagFactory::make()->setField('slug', 'without-pivot-entries')->persist();

        $nTagsCreated = $this->service->decouple();

        $this->assertSame(2, $nTagsCreated);
        $this->assertSame(4, TagFactory::count());
        $this->assertSame(5, ResourcesTagFactory::count());

        // The previously never shared tag is still pointing on 2 resources, and it is still not shared
        /** @var \Passbolt\Tags\Model\Entity\Tag $neverSharedPersonalTag */
        $neverSharedPersonalTag = TagFactory::firstOrFail([
            'id' => $neverSharedPersonalTag->id,
            'slug' => $neverSharedPersonalTag->slug,
        ]);
        $this->assertSame(
            2,
            ResourcesTagFactory::find()
                ->where([
                    'user_id' => $user1->id,
                    'tag_id' => $neverSharedPersonalTag->id,
                    'resource_id IN' => Hash::extract($resources, '{n}.id'),
                ])
                ->all()->count()
        );

        // There are now 3 tags with the same slug, pointing resp. to $user1, $user2 and $user3
        $duplicateTags = TagFactory::find()->where(['slug' => $previouslySharedPersonalTag->slug]);
        $this->assertSame(count($users), $duplicateTags->all()->count());
        // Assert that the duplicated tag was not deleted, but rather re-used
        TagFactory::get($previouslySharedPersonalTag->id);
        $this->assertSame(0, TagFactory::find()->where(['slug' => 'without-pivot-entries'])->all()->count());
    }

    public function testDecouplePersonalTagsService_Ignore_Shared_Service(): void
    {
        [$user1, $user2] = UserFactory::make(2)->persist();
        // Create a shared tag shared by two users
        /** @var \Passbolt\Tags\Model\Entity\Tag $sharedTag */
        $sharedTag = TagFactory::make()->isShared()->persist();
        ResourcesTagFactory::make()
            ->with('Tags', $sharedTag)
            ->with('Users', $user1)
            ->persist();
        ResourcesTagFactory::make()
            ->with('Tags', $sharedTag)
            ->with('Users', $user2)
            ->persist();

        $nTagsCreated = $this->service->decouple();

        // The previously shared tag still exists
        $this->assertSame(0, $nTagsCreated);
        $sharedTag = TagFactory::get($sharedTag->id);

        $this->assertSame(
            1,
            ResourcesTagFactory::find()
                ->where(['user_id' => $user1->get('id'), 'tag_id' => $sharedTag->id])
                ->all()->count()
        );
        $this->assertSame(
            1,
            ResourcesTagFactory::find()
                ->where(['user_id' => $user2->get('id'), 'tag_id' => $sharedTag->id])
                ->all()->count()
        );

        $this->assertSame(2, ResourcesTagFactory::count());
        $this->assertSame(1, TagFactory::count());
    }

    public function testDecouplePersonalTagsService_Develop_Shared_Personal_Tags_Ignore_v5_Tags(): void
    {
        [$user1] = UserFactory::make(2)->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)->persist();
        // Create a v5 personal tag
        /** @var \Passbolt\Tags\Model\Entity\Tag $neverSharedPersonalTag */
        $neverSharedPersonalTag = TagFactory::make()
            ->v5Fields([])
            ->isPersonalFor($resource1, $user1)
            ->isPersonalFor($resource2, $user1)
            ->persist();

        $nTagsCreated = $this->service->decouple();

        $this->assertSame(0, $nTagsCreated);
        // The previously never shared tag still exists, and it is still not shared
        $this->assertSame(
            2,
            ResourcesTagFactory::find()
                ->where(['user_id' => $user1->get('id'), 'tag_id' => $neverSharedPersonalTag->id])
                ->all()->count()
        );

        $this->assertSame(2, ResourcesTagFactory::count());
        $this->assertSame(1, TagFactory::count());
    }
}
