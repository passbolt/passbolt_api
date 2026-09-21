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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Test\TestCase\Event;

use App\Model\Table\ResourcesTable;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Event\Event;
use Passbolt\OfflineMode\Event\OfflineItemsResourceDeleteListener;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Event\OfflineItemsResourceDeleteListener
 */
class OfflineItemsResourceDeleteListenerTest extends AppTestCase
{
    private OfflineItemsResourceDeleteListener $listener;

    public function setUp(): void
    {
        parent::setUp();
        $this->listener = new OfflineItemsResourceDeleteListener();
    }

    public function tearDown(): void
    {
        unset($this->listener);
        parent::tearDown();
    }

    public function testOfflineItemsResourceDeleteListener_ImplementedEvents_RegistersTheCorrectKey(): void
    {
        $this->assertSame(
            [ResourcesTable::EVENT_MODEL_RESOURCE_AFTER_SOFT_DELETE => 'handleResourceAfterSoftDelete'],
            $this->listener->implementedEvents(),
        );
    }

    public function testOfflineItemsResourceDeleteListener_Success_DeletesAllOfflineItemsForTheResource(): void
    {
        $userA = UserFactory::make()->user()->active()->persist();
        $userB = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        OfflineItemFactory::make()->setUser($userA)->setResource($resource)->persist();
        OfflineItemFactory::make()->setUser($userB)->setResource($resource)->persist();

        /** @var \Cake\Event\Event<object> $event */
        $event = new Event(ResourcesTable::EVENT_MODEL_RESOURCE_AFTER_SOFT_DELETE, $resource);
        $this->listener->handleResourceAfterSoftDelete($event);

        $this->assertSame(0, OfflineItemFactory::count());
    }

    public function testOfflineItemsResourceDeleteListener_Success_DoesNotTouchOfflineItemsForOtherResources(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resourceA */
        $resourceA = ResourceFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resourceB */
        $resourceB = ResourceFactory::make()->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resourceA)->persist();
        $survivor = OfflineItemFactory::make()->setUser($user)->setResource($resourceB)->persist();

        /** @var \Cake\Event\Event<object> $event */
        $event = new Event(ResourcesTable::EVENT_MODEL_RESOURCE_AFTER_SOFT_DELETE, $resourceA);
        $this->listener->handleResourceAfterSoftDelete($event);

        $this->assertSame(1, OfflineItemFactory::count());
        $this->assertNotNull(
            OfflineItemFactory::find()->where(['id' => $survivor->get('id')])->first(),
        );
    }

    public function testOfflineItemsResourceDeleteListener_NoOp_WhenResourceHasNoOfflineItems(): void
    {
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();

        /** @var \Cake\Event\Event<object> $event */
        $event = new Event(ResourcesTable::EVENT_MODEL_RESOURCE_AFTER_SOFT_DELETE, $resource);
        $this->listener->handleResourceAfterSoftDelete($event);

        $this->assertSame(0, OfflineItemFactory::count());
    }

    public function testOfflineItemsResourceDeleteListener_Integration_ResourcesTableSoftDeleteDispatchesEventAndWipesRows(): void // phpcs:ignore
    {
        $user = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();
        /** @var \App\Model\Table\ResourcesTable $Resources */
        $Resources = $this->fetchTable('Resources');
        $Resources->getEventManager()->on($this->listener);

        $Resources->softDelete($user->get('id'), $resource);

        $this->assertSame(0, OfflineItemFactory::count());
    }
}
