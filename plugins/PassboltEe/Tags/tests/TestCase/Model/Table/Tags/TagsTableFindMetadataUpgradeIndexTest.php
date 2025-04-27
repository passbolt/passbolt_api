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
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTagPersonal2 */
        $resourceTagPersonal2 = ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', $personalTag)
            ->persist();

        // V5 tag to be ignored
        TagFactory::make()->v5Fields(['metadata' => 'foo'])->persist();

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $result = $Tags->findMetadataUpgradeIndex([])->toArray();

        $userIdsRetrieved = Hash::extract($result, '{n}.user_id');
        $this->assertSame(2, count($result));
        foreach ($result as $tag) {
            $this->assertSame($personalTag->id, $tag['id']);
        }
        $this->assertTrue(in_array($resourceTagPersonal->user_id, $userIdsRetrieved));
        $this->assertTrue(in_array($resourceTagPersonal2->user_id, $userIdsRetrieved));
    }
}
