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
 * @since         4.10.0
 */
namespace Passbolt\Tags\Test\TestCase\Model\Table\Tags;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Model\Table\ResourcesTagsTable
 */
class ResourcesTagsTableTest extends AppTestCase
{
    /**
     * @var \Passbolt\Tags\Model\Table\ResourcesTagsTable
     */
    public $ResourcesTags;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->ResourcesTags = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ResourcesTags);
        parent::tearDown();
    }

    public function testResourcesTagsTable_CleanupDuplicatedResourcesTags(): void
    {
        $ada = UserFactory::make()->user()->persist();
        $betty = UserFactory::make()->user()->persist();
        $resource1 = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        $resource2 = ResourceFactory::make()->withPermissionsFor([$betty])->persist();
        TagFactory::make()->isPersonalFor($resource1, $ada)->persist();
        TagFactory::make()->isPersonalFor($resource2, $betty)->persist();
        $tag = TagFactory::make()->isPersonalFor($resource1, $ada)->persist();
        // duplicated tag
        ResourcesTagFactory::make(['tag_id' => $tag->get('id'), 'resource_id' => $resource1->get('id'), 'user_id' => $ada->get('id')])->persist();

        $result = $this->ResourcesTags->cleanupDuplicatedResourcesTags();

        $this->assertSame(1, $result);
        $this->assertSame(3, ResourcesTagFactory::count());
    }

    public function testResourcesTagsTable_CleanupDuplicatedResourcesTags_DryRun(): void
    {
        $ada = UserFactory::make()->user()->persist();
        $betty = UserFactory::make()->user()->persist();
        $resource1 = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        $resource2 = ResourceFactory::make()->withPermissionsFor([$betty])->persist();
        TagFactory::make()->isPersonalFor($resource1, $ada)->persist();
        $tag = TagFactory::make()->isPersonalFor($resource1, $ada)->persist();
        // 2 duplicated tags
        ResourcesTagFactory::make(['tag_id' => $tag->get('id'), 'resource_id' => $resource1->get('id'), 'user_id' => $ada->get('id')])->persist();
        ResourcesTagFactory::make(['tag_id' => $tag->get('id'), 'resource_id' => $resource1->get('id'), 'user_id' => $ada->get('id')])->persist();

        $result = $this->ResourcesTags->cleanupDuplicatedResourcesTags(true);

        $this->assertSame(2, $result);
        $this->assertSame(4, ResourcesTagFactory::count());
    }
}
