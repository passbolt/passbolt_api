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
 * @since         5.1.0
 */
namespace Passbolt\Tags\Test\TestCase\Model\Table\Tags;

use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Model\Table\TagsTable
 */
class TagsTableFindMetadataUpgradeIndexTest extends TestCase
{
    use TruncateDirtyTables;

    public function testTagsTableFindMetadataUpgradeIndex_No_Filter()
    {
        $resourceTagShared = ResourcesTagFactory::make()
            ->with('Tags', TagFactory::make()->isShared())
            ->persist();
        $resourceTagPersonal = ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', TagFactory::make())
            ->persist();

        $sharedTag = $resourceTagShared->get('tag');
        $personalTag = $resourceTagPersonal->get('tag');
        $personalTagOwner = $resourceTagPersonal->get('user');

        // V5 tag to be ignored
        TagFactory::make()->v5Fields(['metadata' => 'foo'])->persist();

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $result = $Tags->findMetadataUpgradeIndex([])->toArray();

        $tagIdsRetrieved = Hash::extract($result, '{n}.id');
        $this->assertSame(2, count($result));
        $this->assertTrue(in_array($sharedTag->id, $tagIdsRetrieved));
        $this->assertTrue(in_array($personalTag->id, $tagIdsRetrieved));
        foreach ($result as $tag) {
            if ($tag['id'] === $personalTag->id) {
                $this->assertEquals([
                    'id' => $personalTag->id,
                    'user_id' => $personalTagOwner->id,
                    'slug' => $personalTag->slug,
                    'is_shared' => false,
                ], $tag);
            } else {
                $this->assertEquals([
                    'id' => $sharedTag->id,
                    'user_id' => null,
                    'slug' => $sharedTag->slug,
                    'is_shared' => true,
                ], $tag);
            }
        }
    }

    public function testTagsTableFindMetadataUpgradeIndex_Tag_On_Multiple_Resources_Personal()
    {
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        ResourcesTagFactory::make(2)
            ->with('Users', $user)
            ->with('Tags', $tag)
            ->persist();

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $result = $Tags->findMetadataUpgradeIndex([])->toArray();
        $this->assertSame(1, count($result));
        $this->assertSame([
            'id' => $tag->id,
            'slug' => $tag->slug,
            'is_shared' => false,
            'user_id' => $user->id,
        ], $result[0]);
    }

    public function testTagsTableFindMetadataUpgradeIndex_Tag_On_Multiple_Resources_Shared()
    {
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->isShared()->persist();
        $user = UserFactory::make()->user()->persist();
        ResourcesTagFactory::make(2)
            ->with('Users', $user)
            ->with('Tags', $tag)
            ->persist();

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $result = $Tags->findMetadataUpgradeIndex([])->toArray();
        $this->assertSame(1, count($result));
        $this->assertSame([
            'id' => $tag->id,
            'slug' => $tag->slug,
            'is_shared' => true,
            'user_id' => null,
        ], $result[0]);
    }

    public function testTagsTableFindMetadataUpgradeIndex_Tag_With_No_Associations()
    {
        TagFactory::make()->isShared()->persist();
        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $result = $Tags->findMetadataUpgradeIndex([])->toArray();
        $this->assertSame(0, count($result));
    }

    public function testTagsTableFindMetadataUpgradeIndex_Shared()
    {
        $resourceTagShared = ResourcesTagFactory::make()
            ->with('Tags', TagFactory::make()->isShared())
            ->persist();
        ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', TagFactory::make())
            ->persist();

        $sharedTag = $resourceTagShared->get('tag');

        // V5 tag to be ignored
        TagFactory::make()->v5Fields(['metadata' => 'foo'])->persist();

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $result = $Tags->findMetadataUpgradeIndex(['filter' => ['is-shared' => true]])->toArray();

        $tagIdsRetrieved = Hash::extract($result, '{n}.id');
        $this->assertSame(1, count($result));
        $this->assertTrue(in_array($sharedTag->id, $tagIdsRetrieved));
        foreach ($result as $tag) {
            $this->assertNull($tag['user_id']);
        }
    }

    public function testTagsTableFindMetadataUpgradeIndex_Not_Shared()
    {
        ResourcesTagFactory::make()
            ->with('Tags', TagFactory::make()->isShared())
            ->persist();
        $resourceTagPersonal = ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', TagFactory::make())
            ->persist();

        $personalTag = $resourceTagPersonal->get('tag');
        $personalTagOwner = $resourceTagPersonal->get('user');

        // V5 tag to be ignored
        TagFactory::make()->v5Fields(['metadata' => 'foo'])->persist();

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $result = $Tags->findMetadataUpgradeIndex(['filter' => ['is-shared' => false]])->toArray();

        $tagIdsRetrieved = Hash::extract($result, '{n}.id');
        $this->assertSame(1, count($result));
        $this->assertTrue(in_array($personalTag->id, $tagIdsRetrieved));
        foreach ($result as $tag) {
            $this->assertEquals($personalTagOwner->id, $tag['user_id']);
        }
    }

    public function testTagsTableFindMetadataUpgradeIndex_Duplicated_ResourceTags()
    {
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTagPersonal */
        $resourceTagPersonal = ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', TagFactory::make())
            ->persist();

        /** @var \Passbolt\Tags\Model\Entity\Tag $personalTag */
        $personalTag = $resourceTagPersonal->get('tag');
        $resourceTagPersonal->get('user');
        // The tag is supposed to be personal, but for testing-sake,
        // we introduce here a DB inconsistency to test how the finder reacts
        ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', $personalTag)
            ->persist();

        // V5 tag to be ignored
        TagFactory::make()->v5Fields(['metadata' => 'foo'])->persist();

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $result = $Tags->findMetadataUpgradeIndex([])->toArray();

        $this->assertSame(1, count($result));
        foreach ($result as $tag) {
            $this->assertSame($personalTag->id, $tag['id']);
        }
    }
}
