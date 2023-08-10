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
 * @since         3.10.0
 */
namespace Passbolt\MfaPolicies\Model\Table;

use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UuidFactory;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;

/**
 * MfaPoliciesSettings Model
 *
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting newEmptyEntity()
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting newEntity(array $data, array $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[] newEntities(array $data, array $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting get($primaryKey, $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MfaPoliciesSettingsTable extends OrganizationSettingsTable
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->getSchema()->setColumnType('value', 'json');
    }

    /**
     * {@inheritDoc}
     *
     * **Note:** Overridden parent method because validation for `value` field was related to string/text.
     * But here we are using JsonType for `value` field, so we have to pass array.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator = parent::validationDefault($validator);

        $validator->remove('value');

        $validator->requirePresence('value', true, __('A value is required.'));
        $validator->isArray('value', __('The value should be an array.'));

        return $validator;
    }

    /**
     * Filter organization settings by property.
     *
     * @param \Cake\Event\Event $event Model.beforeFind event.
     * @param  \Cake\ORM\Query $query Any query performed on the present table.
     * @return \Cake\ORM\Query
     */
    public function beforeFind(Event $event, Query $query): Query
    {
        return $query->where([
            $this->aliasField('property_id') => $this->getPropertyId(),
        ]);
    }

    /**
     * Fields property and property_id are fixed.
     *
     * @param \Cake\Event\Event $event the event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $data['property'] = $this->getProperty();
        $data['property_id'] = $this->getPropertyId();
    }

    /**
     * Returns current property name.
     *
     * @return string
     */
    public function getProperty(): string
    {
        return MfaPoliciesSetting::PROPERTY_NAME;
    }

    /**
     * Generates property ID from property name.
     *
     * @return string
     */
    public function getPropertyId(): string
    {
        return UuidFactory::uuid($this->getProperty());
    }

    /**
     * Returns unique property id.
     *
     * @param string $property property name
     * @return string (uuid) property id
     */
    protected function _getSettingPropertyId(string $property): string
    {
        return $this->getPropertyId();
    }
}
