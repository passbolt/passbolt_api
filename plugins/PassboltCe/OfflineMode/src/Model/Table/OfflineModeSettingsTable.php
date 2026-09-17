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
namespace Passbolt\OfflineMode\Model\Table;

use App\Model\Entity\OrganizationSetting;
use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UuidFactory;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Passbolt\OfflineMode\Model\Entity\OfflineModeSetting;

/**
 * OfflineModeSettings Model
 *
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting newEmptyEntity()
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting newEntity(array $data, array $options = [])
 * @method array<\Passbolt\OfflineMode\Model\Entity\OfflineModeSetting> newEntities(array $data, array $options = [])
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, callable|array|null $callback = null, array $options = [])
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Passbolt\OfflineMode\Model\Entity\OfflineModeSetting> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\Passbolt\OfflineMode\Model\Entity\OfflineModeSetting>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\OfflineMode\Model\Entity\OfflineModeSetting> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\OfflineMode\Model\Entity\OfflineModeSetting>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\OfflineMode\Model\Entity\OfflineModeSetting> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OfflineModeSettingsTable extends OrganizationSettingsTable
{
    /**
     * @param array $config Configuration passed by the registry.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setEntityClass(OfflineModeSetting::class);
        $this->getSchema()->setColumnType('value', 'json');
    }

    /**
     * {@inheritDoc}
     *
     * Parent validator types `value` as a UTF-8 string. With the JSON column cast in
     * `initialize()`, `value` reaches the validator as an array — re-type the rule.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator = parent::validationDefault($validator);

        $validator->remove('value');
        $validator->requirePresence('value', true, __('A value is required.'));
        $validator->array('value', __('The value should be an array.'));

        return $validator;
    }

    /**
     * Filter organization settings by property id at every query entry point.
     *
     * @param \Cake\Event\Event $event Model.beforeFind event.
     * @param \Cake\ORM\Query $query Any query performed on the present table.
     * @return void
     */
    public function beforeFind(Event $event, Query $query): void
    {
        $query->where([
            $this->aliasField('property_id') => $this->getPropertyId(),
        ]);
    }

    /**
     * `property` and `property_id` are pinned for every save.
     *
     * @param \Cake\Event\Event $event Cake marshal event.
     * @param \ArrayObject $data Patch / new-entity payload.
     * @param \ArrayObject $options Marshal options.
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options): void
    {
        $data['property'] = $this->getProperty();
        $data['property_id'] = $this->getPropertyId();
    }

    /**
     * @return string the scoped property name.
     */
    public function getProperty(): string
    {
        return OfflineModeSetting::PROPERTY_NAME;
    }

    /**
     * Property-id derivation follows the base convention prescribed by specs.md §7.2 —
     * `OrganizationSetting::UUID_NAMESPACE . 'offlineMode'`. This is a deliberate divergence
     * from `ScimSettingsTable::getPropertyId()`, which uses the bare property name. Do not
     * "harmonise" with the Scim shape without a data migration: every existing row's
     * `property_id` would stop matching what `OrganizationSettingsTable::getFirstSettingOrFail`
     * computes, silently making the row inaccessible through the base helpers.
     *
     * @return string the property id (deterministic uuid).
     */
    public function getPropertyId(): string
    {
        return UuidFactory::uuid(OrganizationSetting::UUID_NAMESPACE . $this->getProperty());
    }

    /**
     * Ensures the inherited `createOrUpdateSetting()` / `deleteSetting()` use the scoped
     * property_id regardless of the `$property` argument they receive.
     *
     * @param string $property Unused — scoping is fixed at the table level.
     * @return string the same property id as `getPropertyId()`.
     */
    protected function _getSettingPropertyId(string $property): string
    {
        return $this->getPropertyId();
    }

    /**
     * Checks if offline mode settings is present in the DB or not.
     * Considers a row present in valid state (JSON) as feature enabled and no row as disabled.
     *
     * @return bool
     */
    public function isOfflineModeFeatureEnabled(): bool
    {
        /** @var \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting|null $entity */
        $entity = $this->find()->first();
        if ($entity === null) {
            return false;
        }

        return is_array($entity->get('value'));
    }
}
