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
 * @since         4.3.0
 */

namespace Passbolt\Tags\Test\TestCase\Model\Table\Tags;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

/**
 * Passbolt\Tags\Model\Table\Tags::findIndex Test Case
 */
class TagsTableFindIndexTest extends TagTestCase
{
    /**
     * Test subject
     *
     * @var \Passbolt\Tags\Model\Table\TagsTable
     */
    public $tagsTable;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
    }

    public function testTagsTableFindIndex_EmptyDatabase()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();

        $tags = $this->tagsTable->findIndex($user->id)->all()->toArray();
        $this->assertEmpty($tags);
    }

    public function testTagsTableFindIndex_SharedResourceWithNoTag()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        ResourceFactory::make()->withCreatorAndPermission($user)->persist();

        $tags = $this->tagsTable->findIndex($user->id)->all()->toArray();
        $this->assertEmpty($tags);
    }

    public function testTagsTableFindIndex_SharedResourceWithTagOwnedByOtherUser()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User $anotherUser */
        $otherUser = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user, $otherUser])->persist();
        TagFactory::make()->isPersonalFor($resource, $otherUser)->persist();

        $tags = $this->tagsTable->findIndex($user->id)->all()->toArray();
        $this->assertEquals(1, TagFactory::count());
        $this->assertEmpty($tags);
    }

    public function testTagsTableFindIndex_NotSharedResourceWithTagOwnedByOtherUser()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User $anotherUser */
        $otherUser = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$otherUser])->persist();
        TagFactory::make()->isPersonalFor($resource, $otherUser)->persist();

        $tags = $this->tagsTable->findIndex($user->id)->all()->toArray();
        $this->assertEmpty($tags);
    }

    public function testTagsTableFindIndex_ResourceWithOwnedTag()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->isPersonalFor($resource, $user)->persist();

        $result = $this->tagsTable->findIndex($user->id)->all()->toArray();
        $this->assertCount(1, $result);
        $this->assertEquals($tag->id, $result[0]->id);
    }

    public function testTagsTableFindIndex_ResourceWithSharedTag()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->isSharedFor($resource)->persist();

        $result = $this->tagsTable->findIndex($user->id)->all()->toArray();
        $this->assertCount(1, $result);
        $this->assertEquals($tag->id, $result[0]->id);
    }

    public function testTagsTableFindIndex_ResourceWithSharedTagAndOwnedTag()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $sharedTag */
        $sharedTag = TagFactory::make()->isSharedFor($resource)->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $personalTag */
        $personalTag = TagFactory::make()->isPersonalFor($resource, $user)->persist();

        $result = $this->tagsTable->findIndex($user->id)->all()->toArray();
        $this->assertCount(2, $result);
        $resultTagsIds = Hash::extract($result, '{n}.id');
        $this->assertContains($personalTag->id, $resultTagsIds);
        $this->assertContains($sharedTag->id, $resultTagsIds);
    }

    public function testTagsTableFindIndex_ResourceWithSharedTagAndOwnedTagAndOtherUserOwnedTag()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User $anotherUser */
        $otherUser = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $sharedTag */
        $sharedTag = TagFactory::make()->isSharedFor($resource)->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $personalTag */
        $personalTag = TagFactory::make()->isPersonalFor($resource, $user)->persist();
        TagFactory::make()->isPersonalFor($resource, $otherUser)->persist();

        $result = $this->tagsTable->findIndex($user->id)->all()->toArray();
        $this->assertCount(2, $result);
        $resultsTagsIds = Hash::extract($result, '{n}.id');
        $this->assertContains($personalTag->id, $resultsTagsIds);
        $this->assertContains($sharedTag->id, $resultsTagsIds);
    }
}
