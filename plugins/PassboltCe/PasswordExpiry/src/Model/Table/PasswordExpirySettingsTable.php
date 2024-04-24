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
 * @since         4.5.0
 */
namespace Passbolt\PasswordExpiry\Model\Table;

use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UuidFactory;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\Validation\Validator;

/**
 * PasswordExpirySettings Model
 *
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting newEmptyEntity()
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting newEntity(array $data, array $options = [])
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting[] newEntities(array $data, array $options = [])
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting get($primaryKey, $options = [])
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PasswordExpirySettingsTable extends OrganizationSettingsTable
{
    /**
     * @var string
     */
    public const PROPERTY_NAME = 'passwordExpiry';

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
        $validator->array('value', __('The value should be an array.'));

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
        return self::PROPERTY_NAME;
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
