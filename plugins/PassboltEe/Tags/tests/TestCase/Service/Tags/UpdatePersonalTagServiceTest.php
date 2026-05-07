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

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Service\Tags\UpdatePersonalTagService;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Service\Tags\UpdatePersonalTagService
 */
class UpdatePersonalTagServiceTest extends AppTestCase
{
    use UserAccessControlTrait;

    /**
     * @var UpdatePersonalTagService|null
     */
    private ?UpdatePersonalTagService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new UpdatePersonalTagService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testUpdatePersonalTagService_Renaming_Into_A_Shared_Slug_Should_Throw_An_Error(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $tag = TagFactory::make()->isPersonalFor($resource, $user)->persist();
        // data to update
        $newSlug = '#tag updated';
        $newData = ['slug' => $newSlug];

        $uac = $this->makeUac($user);
        $tagDto = MetadataTagDto::fromArray($newData);
        $this->expectExceptionMessage('You do not have the permission to change a personal tag into shared tag.');
        $this->expectException(BadRequestException::class);
        $this->service->update($uac, $tagDto, $tag);
    }

    public function testUpdatePersonalTagService_Rename_Tag(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $tag = TagFactory::make()->isPersonalFor($resource, $user)->persist();
        // data to update
        $newSlug = 'tag updated';
        $newData = ['slug' => $newSlug];

        $uac = $this->makeUac($user);
        $tagDto = MetadataTagDto::fromArray($newData);
        $this->service->update($uac, $tagDto, $tag);

        $tagUpdated = TagFactory::firstOrFail();
        $this->assertSame($newSlug, $tagUpdated->slug);

        $this->assertSame(1, TagFactory::count());
        $this->assertSame(1, ResourcesTagFactory::count());
    }

    public function testUpdatePersonalTagService_Rename_Tag_As_An_Existing_One_Should_Merge_The_Two_Tags(): void
    {
        [$user1, $user2] = UserFactory::make(2)->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)->persist();

        $tagToRename = TagFactory::make()->isPersonalFor($resource1, $user1)->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagToMergeOnto */
        $tagToMergeOnto = TagFactory::make()->isPersonalFor($resource2, $user1)->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagOfAnotherUserWithSameSlugToIgnore */
        $tagOfAnotherUserWithSameSlugToIgnore = TagFactory::make(['slug' => $tagToMergeOnto->slug])
            ->isPersonalFor($resource2, $user2)
            ->persist();
        // data to update
        $newSlug = $tagToMergeOnto->slug;
        $newData = ['slug' => $newSlug];

        $uac = $this->makeUac($user1);
        $tagDto = MetadataTagDto::fromArray($newData);
        $this->service->update($uac, $tagDto, $tagToRename);

        $tagUpdated = TagFactory::firstOrFail(['id' => $tagToMergeOnto->id]);
        $this->assertSame($tagToMergeOnto->slug, $tagUpdated->slug);
        $this->assertSame($tagToMergeOnto->id, $tagUpdated->id);

        $this->assertSame(2, TagFactory::count());
        $this->assertSame(3, ResourcesTagFactory::count());
        $this->assertSame(
            2,
            ResourcesTagFactory::find()->where(['user_id' => $user1->id, 'tag_id' => $tagToMergeOnto->id])->all()->count()
        );
        $this->assertSame($newSlug, TagFactory::firstOrFail(['id' => $tagToMergeOnto->id])->slug);
        $this->assertSame($newSlug, TagFactory::firstOrFail(['id' => $tagOfAnotherUserWithSameSlugToIgnore->id])->slug);
    }

    public function testUpdatePersonalTagService_On_Existing_Tag_With_Second_User_With_Tag_With_Same_Slug_On_Same_Resource(): void
    {
        // User 1 will update his tag
        [$user1, $user2] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();

        $originalSlug = 'test';
        $tagOfUser1 = TagFactory::make()->setField('slug', $originalSlug)->isPersonalFor($resource, $user1)->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagOfUser2 */
        $tagOfUser2 = TagFactory::make()->setField('slug', $originalSlug)->isPersonalFor($resource, $user2)->persist();
        // data to update
        $newSlug = 'tag updated';
        $newData = ['slug' => $newSlug];

        $uac = $this->makeUac($user1);
        $tagDto = MetadataTagDto::fromArray($newData);
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagUpdated */
        $tagUpdated = $this->service->update($uac, $tagDto, $tagOfUser1);

        $this->assertSame($newSlug, $tagUpdated->slug);
        $this->assertSame(2, TagFactory::count());
        $this->assertSame(2, ResourcesTagFactory::count());
        $this->assertSame($originalSlug, TagFactory::get($tagOfUser2->id)->slug);
        $this->assertSame($newSlug, TagFactory::get($tagUpdated->id)->slug);
    }
}
