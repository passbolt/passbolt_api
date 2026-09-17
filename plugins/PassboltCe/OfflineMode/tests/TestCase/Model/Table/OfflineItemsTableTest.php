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
namespace Passbolt\OfflineMode\Test\TestCase\Model\Table;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;
use Passbolt\OfflineMode\Model\Entity\OfflineItem;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;

/**
 * @covers \Passbolt\OfflineMode\Model\Table\OfflineItemsTable
 * @covers \Passbolt\OfflineMode\Model\Entity\OfflineItem
 */
class OfflineItemsTableTest extends AppTestCaseV5
{
    private ?OfflineItemsTable $OfflineItems = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->OfflineItems = TableRegistry::getTableLocator()->get('Passbolt/OfflineMode.OfflineItems');
    }

    public function tearDown(): void
    {
        unset($this->OfflineItems);
        parent::tearDown();
    }

    /**
     * Returns a payload that satisfies validationDefault for the given user/resource.
     */
    private function buildPayload(string $userId, string $resourceId): array
    {
        return [
            'user_id' => $userId,
            'foreign_model' => OfflineItemsTable::FOREIGN_MODEL_RESOURCE,
            'foreign_key' => $resourceId,
            'created' => Chronos::now(),
            'created_by' => $userId,
        ];
    }

    private function buildEntity(array $data): OfflineItem
    {
        /** @var \Passbolt\OfflineMode\Model\Entity\OfflineItem $entity */
        $entity = $this->OfflineItems->newEntity($data, [
            'accessibleFields' => [
                'user_id' => true,
                'foreign_model' => true,
                'foreign_key' => true,
                'created' => true,
                'created_by' => true,
            ],
        ]);

        return $entity;
    }

    public function testOfflineItemsTable_Save_PersistsRowWhenAllRulesPass(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();

        $entity = $this->buildEntity($this->buildPayload($user->get('id'), $resource->get('id')));
        $saved = $this->OfflineItems->save($entity);

        $this->assertNotFalse($saved, json_encode($entity->getErrors()));
        $this->assertEmpty($entity->getErrors());
        $this->assertNotEmpty($entity->get('id'));
        $this->assertSame(OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $entity->get('foreign_model'));
        $this->assertSame($resource->get('id'), $entity->get('foreign_key'));
        $this->assertSame($user->get('id'), $entity->get('user_id'));
    }

    public function testOfflineItemsTable_Validation_RejectsUnknownForeignModel(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();

        $data = $this->buildPayload($user->get('id'), $resource->get('id'));
        $data['foreign_model'] = 'group';
        $entity = $this->buildEntity($data);

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertNotEmpty($entity->getErrors()['foreign_model']['inList'] ?? null);
    }

    public function testOfflineItemsTable_Validation_RejectsNonUuidUserId(): void
    {
        $resource = ResourceFactory::make()->persist();
        $data = $this->buildPayload('not-a-uuid', $resource->get('id'));
        $entity = $this->buildEntity($data);

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertNotEmpty($entity->getErrors()['user_id']['uuid'] ?? null);
    }

    public function testOfflineItemsTable_Validation_RejectsNonUuidForeignKey(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $data = $this->buildPayload($user->get('id'), 'not-a-uuid');
        $entity = $this->buildEntity($data);

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertNotEmpty($entity->getErrors()['foreign_key']['uuid'] ?? null);
    }

    public function testOfflineItemsTable_BuildRules_RejectsWhenUserMissing(): void
    {
        $resource = ResourceFactory::make()->persist();
        $entity = $this->buildEntity($this->buildPayload(UuidFactory::uuid(), $resource->get('id')));

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertNotEmpty($entity->getErrors()['user_id']['user_exists'] ?? null);
    }

    public function testOfflineItemsTable_BuildRules_RejectsWhenUserSoftDeleted(): void
    {
        $user = UserFactory::make()->user()->deleted()->persist();
        $resource = ResourceFactory::make()->persist();
        $entity = $this->buildEntity($this->buildPayload($user->get('id'), $resource->get('id')));

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertNotEmpty($entity->getErrors()['user_id']['user_is_active'] ?? null);
    }

    public function testOfflineItemsTable_BuildRules_RejectsWhenResourceMissing(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $entity = $this->buildEntity($this->buildPayload($user->get('id'), UuidFactory::uuid()));

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertNotEmpty($entity->getErrors()['foreign_key']['resource_exists'] ?? null);
    }

    public function testOfflineItemsTable_BuildRules_RejectsWhenResourceSoftDeleted(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->setDeleted()->persist();
        $entity = $this->buildEntity($this->buildPayload($user->get('id'), $resource->get('id')));

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertNotEmpty($entity->getErrors()['foreign_key']['resource_is_not_soft_deleted'] ?? null);
    }

    public function testOfflineItemsTable_BuildRules_RejectsWhenUserHasNoAccessToResource(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->persist(); // no permission for $user
        $entity = $this->buildEntity($this->buildPayload($user->get('id'), $resource->get('id')));

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertNotEmpty($entity->getErrors()['foreign_key']['has_resource_access'] ?? null);
    }

    public function testOfflineItemsTable_BuildRules_RejectsDuplicateComposite(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();

        $entity = $this->buildEntity($this->buildPayload($user->get('id'), $resource->get('id')));

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertNotEmpty($entity->getErrors()['user_id']['offline_item_unique'] ?? null);
    }

    public function testOfflineItemsTable_BuildRules_RejectsWhenUserIsAtMaxItems(): void
    {
        OfflineModeSettingFactory::make()->setMaxItems(2)->persist();
        $user = UserFactory::make()->user()->active()->persist();
        OfflineItemFactory::make(2)->setUser($user)->persist();
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();

        $entity = $this->buildEntity($this->buildPayload($user->get('id'), $resource->get('id')));

        $this->assertFalse($this->OfflineItems->save($entity));
        $this->assertArrayHasKey('max_items', $entity->getErrors());
        $this->assertSame(
            'You have reached the maximum number of offline items (2).',
            $entity->getErrors()['max_items']['max_items'],
        );
        $this->assertSame(2, OfflineItemFactory::count());
    }

    public function testOfflineItemsTable_BuildRules_AcceptsWhenUserIsBelowMaxItems(): void
    {
        OfflineModeSettingFactory::make()->setMaxItems(2)->persist();
        $user = UserFactory::make()->user()->active()->persist();
        OfflineItemFactory::make()->setUser($user)->persist();
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();
        // Other user
        $user2 = UserFactory::make()->user()->active()->persist();
        OfflineItemFactory::make(2)->setUser($user2)->persist();

        $entity = $this->buildEntity($this->buildPayload($user->get('id'), $resource->get('id')));
        $result = $this->OfflineItems->save($entity);

        $this->assertInstanceOf(OfflineItem::class, $result);
        $this->assertEmpty($entity->getErrors());
        $this->assertSame(2, OfflineItemFactory::find()->where(['user_id' => $user->get('id')])->count());
    }

    public function testOfflineItemsTable_BuildRules_MaxItemsLimitNotEnforcedWhenSettingsAreInvalid(): void
    {
        OfflineModeSettingFactory::make()->setField('value', 'not-an-array')->persist();
        $user = UserFactory::make()->user()->active()->persist();
        OfflineItemFactory::make(3)->setUser($user)->persist();
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();

        $entity = $this->buildEntity($this->buildPayload($user->get('id'), $resource->get('id')));
        $result = $this->OfflineItems->save($entity);

        $this->assertInstanceOf(OfflineItem::class, $result);
        $this->assertEmpty($entity->getErrors());
        $this->assertSame(4, OfflineItemFactory::count());
    }

    public function testOfflineItemsTable_Entity_NoFieldIsMassAssignable(): void
    {
        $entity = $this->OfflineItems->newEntity([
            'id' => UuidFactory::uuid('attacker'),
            'user_id' => UuidFactory::uuid('attacker'),
            'foreign_model' => OfflineItemsTable::FOREIGN_MODEL_RESOURCE,
            'foreign_key' => UuidFactory::uuid('legit'),
            'created' => Chronos::now(),
            'created_by' => UuidFactory::uuid('attacker'),
        ]);

        $this->assertNull($entity->get('id'));
        $this->assertNull($entity->get('user_id'));
        $this->assertNull($entity->get('foreign_model'));
        $this->assertNull($entity->get('foreign_key'));
        $this->assertNull($entity->get('created'));
        $this->assertNull($entity->get('created_by'));
    }
}
