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
namespace Passbolt\UserPassphrasePolicies\Model\Table;

use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UuidFactory;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting;

/**
 * PasswordPoliciesSettings Model
 *
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting newEmptyEntity()
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting newEntity(array $data, array $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[] newEntities(array $data, array $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting get($primaryKey, $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserPassphrasePoliciesSettingsTable extends OrganizationSettingsTable
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
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
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
        return UserPassphrasePoliciesSetting::PROPERTY_NAME;
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
