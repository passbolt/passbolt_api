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
namespace Passbolt\Tags\Test\TestCase\Model\Table\Tags;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

/**
 * @covers \Passbolt\Tags\Model\Table\TagsTable
 */
class TagsTableTest extends TagTestCase
{
    /**
     * Test subject
     *
     * @var \Passbolt\Tags\Model\Table\TagsTable
     */
    public $Tags;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Tags);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testTagsTableBuildEntitiesOrFailError()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $errorThrown = false;
        try {
            $tags = [['test']];
            $this->Tags->buildEntitiesOrFail($user->id, $tags);
        } catch (CustomValidationException $e) {
            $this->assertSame('Could not validate tags data.', $e->getMessage());
            $errorThrown = true;
            $errors = [
                [
                    'slug' => [
                        '_empty' => 'The tag should not be empty.',
                    ],
                    'is_shared' => [
                        '_required' => 'A shared status is required.',
                    ],
                ],
            ];
            $this->assertSame($errors, $e->getErrors());
        } finally {
            $this->assertTrue($errorThrown);
        }
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testTagsTableBeforeMarshall()
    {
        $userId = UserFactory::make()->persist();
        $tag = $this->Tags->newEntity([
            'slug' => 'test',
            'is_shared' => true,
            'user_id' => $userId,
        ]);
        $this->assertEmpty($tag->toArray());
        $tag = $this->Tags->newEntity([
            'slug' => 'test',
            'is_shared' => true,
            'user_id' => $userId,
        ], [
            'accessibleFields' => [
                'id' => true,
                'user_id' => true,
                'slug' => true,
                'is_shared' => true,
            ],
        ]);
        $this->assertEmpty($tag->id);
        $this->assertNotEquals($tag->id, UuidFactory::uuid('tag.id.test'));
        $this->assertFalse($tag->is_shared);
    }

    public function testTagsDeleteAllUnusedTags()
    {
        TagFactory::make(3)->persist();
        TagFactory::make(3)->isShared()->persist();
        TagFactory::make()->isPersonalFor(
            ResourceFactory::make()->persist(),
            UserFactory::make()->persist()
        )->persist();
        TagFactory::make()->isSharedFor(ResourceFactory::make()->persist())->persist();
        // unused and #unused
        $r = $this->Tags->deleteAllUnusedTags();
        $this->assertEquals($r, 6);

        // there should not be any left
        $r = $this->Tags->deleteAllUnusedTags();
        $this->assertEquals($r, 0);

        $this->assertSame(2, TagFactory::count());
        $this->assertSame(2, ResourcesTagFactory::count());
    }

    public function testTagsTable_findAllBySlugs()
    {
        [$user1, $user2] = UserFactory::make(2)->persist();
        $resource = ResourceFactory::make()->persist();
        $uac1 = $this->makeUac($user1);
        $uac2 = $this->makeUac($user2);
        [$tag1, $tag2] = TagFactory::make(5)->isPersonalFor($resource, $user1)->persist();
        $tags1 = $this->Tags->findAllBySlugsOrIds($uac1, [$tag1->slug]);
        $tags2 = $this->Tags->findAllBySlugsOrIds($uac1, [$tag2->slug]);

        $tags = $tags1->union($tags2);

        $this->assertSame(2, $tags->all()->count());
        $findBySlugForUserWithoutTags = $this->Tags->findAllBySlugsOrIds($uac2, [$tag1->slug]);
        $this->assertSame(0, $findBySlugForUserWithoutTags->all()->count());
    }

    public function testTagsTable_findAllBySlugs_Include_Shared()
    {
        [$user1, $user2] = UserFactory::make(2)->persist();
        $resource = ResourceFactory::make()->persist();
        $uac1 = $this->makeUac($user1);
        [$tag1, $tag2] = TagFactory::make(2)->isPersonalFor($resource, $user1)->persist();
        $tag3 = TagFactory::make()->isPersonalFor($resource, $user2)->persist();
        $tag4 = TagFactory::make()->isSharedFor($resource)->persist();

        $tagsAccessibleToUser1 = $this->Tags->findAllBySlugsOrIds($uac1, [
            $tag1->get('slug'),
            $tag2->get('slug'),
            $tag3->get('slug'),
            $tag4->get('slug'),
        ]);

        $this->assertSame(3, $tagsAccessibleToUser1->all()->count());
        $tagIdsAccessibleToUser1 = Hash::extract($tagsAccessibleToUser1->toArray(), '{n}.id');
        $this->assertTrue(in_array($tag1->get('id'), $tagIdsAccessibleToUser1));
        $this->assertTrue(in_array($tag2->get('id'), $tagIdsAccessibleToUser1));
        $this->assertFalse(in_array($tag3->get('id'), $tagIdsAccessibleToUser1));
        $this->assertTrue(in_array($tag4->get('id'), $tagIdsAccessibleToUser1));
    }

    public function testTagsTable_findAllBySlugs_Tag_On_Multiple_Resources_Should_Be_Returned_Once()
    {
        $user = UserFactory::make()->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)->persist();
        $uac = $this->makeUac($user);

        $tag = TagFactory::make()
            ->isPersonalFor($resource1, $user)
            ->isPersonalFor($resource2, $user)
            ->persist();

        $tagsAccessibleToUser = $this->Tags->findAllBySlugsOrIds($uac, [
            $tag->get('slug'),
        ]);

        $this->assertSame(1, $tagsAccessibleToUser->all()->count());
    }

    public function testTagsTable_findAllBySlugs_FindById_Tag_On_Multiple_Resources_Should_Be_Returned_Once()
    {
        $user = UserFactory::make()->persist();
        [$resource1, $resource2] = ResourceFactory::make(2)->persist();
        $uac = $this->makeUac($user);

        $tag = TagFactory::make()
            ->isPersonalFor($resource1, $user)
            ->isPersonalFor($resource2, $user)
            ->persist();

        $tagsAccessibleToUser = $this->Tags->findAllBySlugsOrIds($uac, [], [
            $tag->get('id'),
        ]);

        $this->assertSame(1, $tagsAccessibleToUser->all()->count());
    }

    public function hydrateQueryProvider(): array
    {
        return [[true], [false]];
    }

    /**
     * @dataProvider hydrateQueryProvider
     */
    public function testTagsTable_decorateForeignFind_All_Tags(bool $hydrateQuery)
    {
        $user = UserFactory::make()->persist();
        $userId = $user->get('id');
        $resource = ResourceFactory::make()->persist();
        [$tag] = TagFactory::make(3)->persist();

        ResourcesTagFactory::make([
            'tag_id' => $tag->get('id'),
            'resource_id' => $resource->get('id'),
            'user_id' => $userId,
        ])->persist();

        $query = TableRegistry::getTableLocator()
            ->get('Resources')
            ->find()
            ->where(['Resources.id' => $resource->get('id')]);
        if (!$hydrateQuery) {
            $query->disableHydration();
        }
        $options['contain']['all_tags'] = true;
        $options['contain']['tag'] = true;
        $options['filter']['has-tag'] = $tag->get('slug');

        $this->Tags->decorateForeignFind($query, $options, $userId);

        $retrievedTag = $query->toArray()[0]['tags'][0];
        $this->assertSame($tag['id'], $retrievedTag['id']);
        $this->assertArrayHasKey('_joinData', $retrievedTag);
    }

    /**
     * @dataProvider hydrateQueryProvider
     */
    public function testTagsTable_decorateForeignFind_Contain_Tags(bool $hydrateQuery)
    {
        $user = UserFactory::make()->persist();
        $userId = $user->get('id');
        $resource = ResourceFactory::make()->persist();
        [$tag] = TagFactory::make(3)->persist();

        ResourcesTagFactory::make([
            'tag_id' => $tag->get('id'),
            'resource_id' => $resource->get('id'),
            'user_id' => $userId,
        ])->persist();

        $query = TableRegistry::getTableLocator()
            ->get('Resources')
            ->find()
            ->where(['Resources.id' => $resource->get('id')]);
        if (!$hydrateQuery) {
            $query->disableHydration();
        }
        $options['contain']['tag'] = true;
        $options['filter']['has-tag'] = $tag->get('slug');

        $this->Tags->decorateForeignFind($query, $options, $userId);

        $retrievedTag = $query->toArray()[0]['tags'][0];
        $this->assertSame($tag['id'], $retrievedTag['id']);
        $this->assertArrayNotHasKey('_joinData', $retrievedTag);
    }
}
