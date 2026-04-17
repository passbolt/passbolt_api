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

namespace Passbolt\Tags\Test\TestCase\Model\Table\Resources;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

class SoftDeleteTest extends TagTestCase
{
    public $Resources;
    public $Tags;

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

    public function testTagsResourceSoftDeleteAlsoDeletePersonalTagsSuccess()
    {
        [$ada, $betty] = UserFactory::make(2)->persist();
        [$resourceToDelete, $resourceNotDeleted] = ResourceFactory::make(2)->withPermissionsFor([$ada])->persist();

        /** @var \Passbolt\Tags\Model\Entity\Tag $multiResourcePersonalTag */
        $multiResourcePersonalTag = TagFactory::make()
            ->isPersonalFor($resourceToDelete, $ada)
            ->isPersonalFor($resourceNotDeleted, $betty)
            ->persist();

        /** @var \Passbolt\Tags\Model\Entity\Tag $multiResourceSharedTag */
        $multiResourceSharedTag = TagFactory::make()
            ->isPersonalFor($resourceToDelete, $ada)
            ->isPersonalFor($resourceNotDeleted, $betty)
            ->isShared()
            ->persist();

        /** @var \Passbolt\Tags\Model\Entity\Tag $monoUserPersonalTag */
        $monoUserPersonalTag = TagFactory::make()->isPersonalFor($resourceToDelete, $ada)->persist();

        /** @var \Passbolt\Tags\Model\Entity\Tag $monoUserSharedTag */
        $monoUserSharedTag = TagFactory::make()->isPersonalFor($resourceToDelete, $ada)->isShared()->persist();

        $result = $this->Resources->softDelete($ada->id, $resourceToDelete);

        $this->assertTrue($result);
        $this->assertSame(2, ResourcesTagFactory::count());
        $this->assertTrue($this->Tags->exists(['id' => $multiResourcePersonalTag->id]));
        $this->assertTrue($this->Tags->exists(['id' => $multiResourceSharedTag->id]));
        $this->assertFalse($this->Tags->exists(['id' => $monoUserPersonalTag->id]));
        $this->assertFalse($this->Tags->exists(['id' => $monoUserSharedTag->id]));
    }

    public function testTagsResourceSoftDeleteAlsoDeleteSharedTagsSuccess()
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->persist();
        $resourceToDelete = ResourceFactory::make()->withPermissionsFor([$ada])->persist();

        TagFactory::make()->isSharedFor($resourceToDelete)->persist();

        $this->Resources->softDelete($ada->id, $resourceToDelete);

        $this->assertSame(0, ResourcesTagFactory::count());
        $this->assertSame(0, TagFactory::count());
    }

    public function testTagsResourceSoftDeleteAlsoDeleteSharedTagsViaGroupSuccess()
    {
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()->persist();
        $group = GroupFactory::make()->withGroupsUsersFor([$ada])->persist();
        $resourceToDelete = ResourceFactory::make()->withPermissionsFor([$group])->persist();

        TagFactory::make()->isSharedFor($resourceToDelete)->persist();

        $this->Resources->softDelete($ada->id, $resourceToDelete);

        $this->assertSame(0, ResourcesTagFactory::count());
        $this->assertSame(0, TagFactory::count());
    }
}
