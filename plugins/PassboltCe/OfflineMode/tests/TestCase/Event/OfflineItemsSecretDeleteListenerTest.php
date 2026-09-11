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

use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Role;
use App\Model\Entity\Secret;
use App\Model\Table\GroupsTable;
use App\Service\GroupsUsers\GroupsUsersDeleteService;
use App\Service\Resources\ResourcesShareService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Passbolt\OfflineMode\Event\OfflineItemsSecretDeleteListener;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Event\OfflineItemsSecretDeleteListener
 */
class OfflineItemsSecretDeleteListenerTest extends AppTestCase
{
    private OfflineItemsSecretDeleteListener $listener;

    public function setUp(): void
    {
        parent::setUp();
        $this->listener = new OfflineItemsSecretDeleteListener();
    }

    public function tearDown(): void
    {
        unset($this->listener);
        parent::tearDown();
    }

    public function testOfflineItemsSecretDeleteListener_ImplementedEvents_RegistersAccessLossEvents(): void
    {
        $this->assertSame(
            [
                ResourcesShareService::SHARE_SUCCESS_EVENT_NAME => 'handleSecretsBatchDeleted',
                GroupsUsersDeleteService::AFTER_GROUP_USER_DELETED_EVENT_NAME => 'handleSecretsBatchDeleted',
                GroupsTable::EVENT_MODEL_GROUP_AFTER_SOFT_DELETE => 'handleSecretsBatchDeleted',
            ],
            $this->listener->implementedEvents(),
        );
    }

    public function testOfflineItemsSecretDeleteListener_HandleSecretsBatchDeleted_DeletesOfflineItemsForEachDeletedSecret(): void // phpcs:ignore
    {
        $userA = UserFactory::make()->user()->active()->persist();
        $userB = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        OfflineItemFactory::make()->setUser($userA)->setResource($resource)->persist();
        OfflineItemFactory::make()->setUser($userB)->setResource($resource)->persist();
        $secretA = new Secret(['user_id' => $userA->get('id'), 'resource_id' => $resource->get('id')]);
        $secretB = new Secret(['user_id' => $userB->get('id'), 'resource_id' => $resource->get('id')]);
        $changes = new EntitiesChangesDto();
        $changes->pushDeletedEntities([$secretA, $secretB]);

        /** @var \Cake\Event\Event<object> $event */
        $event = new Event('irrelevant.for.handler', null, ['entitiesChanges' => $changes]);
        $this->listener->handleSecretsBatchDeleted($event);

        $this->assertSame(0, OfflineItemFactory::count());
    }

    public function testOfflineItemsSecretDeleteListener_HandleSecretsBatchDeleted_LeavesUnrelatedRowsAlone(): void
    {
        $userA = UserFactory::make()->user()->active()->persist();
        $userB = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resourceA */
        $resourceA = ResourceFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resourceB */
        $resourceB = ResourceFactory::make()->persist();
        OfflineItemFactory::make()->setUser($userA)->setResource($resourceA)->persist();
        $survivorSameResource = OfflineItemFactory::make()->setUser($userB)->setResource($resourceA)->persist();
        $survivorSameUser = OfflineItemFactory::make()->setUser($userA)->setResource($resourceB)->persist();
        $changes = new EntitiesChangesDto();
        $changes->pushDeletedEntities([
            new Secret(['user_id' => $userA->get('id'), 'resource_id' => $resourceA->get('id')]),
        ]);

        /** @var \Cake\Event\Event<object> $event */
        $event = new Event('irrelevant.for.handler', null, ['entitiesChanges' => $changes]);
        $this->listener->handleSecretsBatchDeleted($event);

        $this->assertSame(2, OfflineItemFactory::count());
        $this->assertNotNull(
            OfflineItemFactory::find()->where(['id' => $survivorSameResource->get('id')])->first(),
        );
        $this->assertNotNull(
            OfflineItemFactory::find()->where(['id' => $survivorSameUser->get('id')])->first(),
        );
    }

    public function testOfflineItemsSecretDeleteListener_HandleSecretsBatchDeleted_IgnoresEventWithoutEntitiesChanges(): void // phpcs:ignore
    {
        $user = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();

        /** @var \Cake\Event\Event<object> $event */
        $event = new Event('irrelevant.for.handler', null, []);
        $this->listener->handleSecretsBatchDeleted($event);

        $this->assertSame(1, OfflineItemFactory::count());
    }

    public function testOfflineItemsSecretDeleteListener_HandleSecretsBatchDeleted_NoOp_WhenNoDeletedSecrets(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();

        /** @var \Cake\Event\Event<object> $event */
        $event = new Event(
            'irrelevant.for.handler',
            null,
            ['entitiesChanges' => new EntitiesChangesDto()],
        );
        $this->listener->handleSecretsBatchDeleted($event);

        $this->assertSame(1, OfflineItemFactory::count());
    }

    public function testOfflineItemsSecretDeleteListener_Integration_ShareRevoke_DeletesOfflineItemForRevokedUser(): void // phpcs:ignore
    {
        /** @var \App\Model\Entity\User $owner */
        $owner = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $viewer */
        $viewer = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$owner, $viewer])
            ->withSecretsFor([$owner, $viewer])
            ->persist();
        OfflineItemFactory::make()->setUser($owner)->setResource($resource)->persist();
        $viewerOfflineItem = OfflineItemFactory::make()->setUser($viewer)->setResource($resource)->persist();
        /** @var \App\Model\Entity\Secret $viewerSecret */
        $viewerSecret = SecretFactory::find()
            ->where(['user_id' => $viewer->get('id'), 'resource_id' => $resource->get('id')])
            ->firstOrFail();
        $changes = new EntitiesChangesDto();
        $changes->pushDeletedEntities([$viewerSecret]);
        EventManager::instance()->on($this->listener);

        $event = new Event(ResourcesShareService::SHARE_SUCCESS_EVENT_NAME, null, [
            'resource' => $resource,
            'secrets' => [],
            'ownerId' => $owner->get('id'),
            'entitiesChanges' => $changes,
        ]);
        EventManager::instance()->dispatch($event);

        $this->assertSame(1, OfflineItemFactory::count());
        $this->assertNull(
            OfflineItemFactory::find()->where(['id' => $viewerOfflineItem->get('id')])->first(),
        );
    }

    public function testOfflineItemsSecretDeleteListener_Integration_GroupMembershipRemoval_DeletesOfflineItemForRemovedUser(): void // phpcs:ignore
    {
        /** @var \App\Model\Entity\User $manager */
        $manager = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $member */
        $member = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$manager])
            ->withGroupsUsersFor([$member])
            ->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withSecretsFor([$manager, $member])
            ->persist();
        OfflineItemFactory::make()->setUser($manager)->setResource($resource)->persist();
        $memberOfflineItem = OfflineItemFactory::make()->setUser($member)->setResource($resource)->persist();
        EventManager::instance()->on($this->listener);

        $uac = new UserAccessControl(Role::USER, $manager->get('id'));
        (new GroupsUsersDeleteService())->delete($uac, $group->groups_users[1]->get('id'));

        $this->assertSame(1, OfflineItemFactory::count());
        $this->assertNull(
            OfflineItemFactory::find()->where(['id' => $memberOfflineItem->get('id')])->first(),
        );
    }

    public function testOfflineItemsSecretDeleteListener_Integration_GroupSoftDelete_DeletesOfflineItemsForAllMembers(): void // phpcs:ignore
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $userB */
        $userB = UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()->withGroupsManagersFor([$userA, $userB])->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->withSecretsFor([$userA, $userB])
            ->persist();
        OfflineItemFactory::make()->setUser($userA)->setResource($resource)->persist();
        OfflineItemFactory::make()->setUser($userB)->setResource($resource)->persist();
        EventManager::instance()->on($this->listener);
        /** @var \App\Model\Table\GroupsTable $Groups */
        $Groups = $this->fetchTable('Groups');

        $Groups->softDelete($group, ['checkRules' => false]);

        $this->assertSame(0, OfflineItemFactory::count());
    }
}
