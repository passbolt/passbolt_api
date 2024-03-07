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
 * @since         2.0.0
 */

namespace Passbolt\AccountSettings\Model\Table;

use App\Error\Exception\ValidationException;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\AccountSettings\Model\Entity\AccountSetting;
use Passbolt\AccountSettings\Model\Table\Traits\ThemeSettingsTrait;

/**
 * AccountSettings Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting get($primaryKey, $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting newEntity(array $data, array $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting[] newEntities(array $data, array $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting newEmptyEntity()
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\Passbolt\AccountSettings\Model\Entity\AccountSetting>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\AccountSettings\Model\Entity\AccountSetting>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\AccountSettings\Model\Entity\AccountSetting>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\AccountSettings\Model\Entity\AccountSetting>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountSettingsTable extends Table
{
    use ThemeSettingsTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('account_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->inList(
                'property',
                AccountSetting::SUPPORTED_PROPERTIES,
                __(
                    'The setting type should be one of the following: {0}.',
                    implode(', ', AccountSetting::SUPPORTED_PROPERTIES)
                )
            )
            ->requirePresence('property', 'create', __('A setting type is required.'))
            ->notEmptyString('property', __('The setting type should not be empty'));

        $validator
            ->utf8Extended('value', __('The setting value should be a valid UTF8 string.'))
            ->maxLength('value', 10240, __('The setting value length should be maximum {0} characters.', 10240))
            ->requirePresence('value', 'create', __('A value setting is required.'))
            ->notEmptyString('value', __('The setting value should not be empty.'));

        // Theme validation
        $validator = $this->themeValidationDefault($validator);

        return $validator;
    }

    /**
     * Custom validation rule to validate account setting property name
     *
     * @param string $value fingerprint
     * @param array $context not in use
     * @return bool
     */
    public function isValidProperty(string $value, ?array $context = null)
    {
        return in_array($value, AccountSetting::SUPPORTED_PROPERTIES);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * Find all the settings for a given user
     *
     * @param string $userId uuid
     * @param array $whitelist example ['theme']
     * @return \Cake\ORM\Query
     */
    public function findIndex(string $userId, array $whitelist)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }

        $props = [];
        foreach ($whitelist as $i => $item) {
            $props[] = $this->propertyToPropertyId($item);
        }

        return $this->find()->where(['user_id' => $userId, 'property_id IN' => $props]);
    }

    /**
     * Find all the settings for a given user
     *
     * @param string $userId uuid
     * @param string $property The name of the property to get
     * @return \Passbolt\AccountSettings\Model\Entity\AccountSetting The first result from the ResultSet.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no first record.
     * @throws \Cake\Http\Exception\BadRequestException When the user ID is not valid.
     */
    public function getFirstPropertyOrFail(string $userId, string $property): AccountSetting
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }

        /** @var \Passbolt\AccountSettings\Model\Entity\AccountSetting $entity */
        $entity = $this->find('byProperty', compact('property'))
            ->where([$this->aliasField('user_id') => $userId])
            ->firstOrFail();

        return $entity;
    }

    /**
     * Create (or update) an account setting
     *
     * @param string $userId uuid
     * @param string $property The property name
     * @param string $value The property value
     * @throws \Cake\Http\Exception\BadRequestException if userId does not exist
     * @throws \App\Error\Exception\ValidationException if could not save because of validation issues
     * @throws \Cake\Http\Exception\InternalErrorException if save operation saved for another reason
     * @return \Passbolt\AccountSettings\Model\Entity\AccountSetting
     */
    public function createOrUpdateSetting(string $userId, string $property, string $value): AccountSetting
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }

        $settingFinder = ['user_id' => $userId, 'property_id' => $this->propertyToPropertyId($property)];
        $settingValues = ['value' => $value, 'property' => $property];
        /** @var \Passbolt\AccountSettings\Model\Entity\AccountSetting|null $settingItem */
        $settingItem = $this->find()
            ->where($settingFinder)
            ->first();

        if ($settingItem) {
            $this->patchEntity($settingItem, $settingValues);
        } else {
            $settingItem = $this->newEntity(array_merge($settingFinder, $settingValues));
        }
        if ($settingItem->getErrors()) {
            throw new ValidationException(__('This is not a valid setting.'), $settingItem, $this);
        }
        if (!$this->save($settingItem)) {
            if ($settingItem->getErrors()) {
                throw new ValidationException(__('This is not a valid setting.'), $settingItem, $this);
            }
            throw new InternalErrorException('Could not save the setting, please try again later.');
        }

        return $settingItem;
    }

    /**
     * Delete an entry for a given user and property
     *
     * @param string $userId user uuid
     * @param string $property user property
     * @return bool
     */
    public function deleteByProperty(string $userId, string $property)
    {
        $settingItem = $this->getByProperty($userId, $property);
        if ($settingItem !== null) {
            return $this->delete($settingItem);
        }

        return false;
    }

    /**
     * Get an entry for a given user and property
     *
     * @param string $userId user uuid
     * @param string $property user property
     * @return \Cake\Datasource\EntityInterface|array|null
     */
    public function getByProperty(string $userId, string $property)
    {
        return $this->find()
            ->find('byProperty', compact('property'))
            ->where([$this->aliasField('user_id') => $userId])
            ->first();
    }

    /**
     * Convert a property in property_id
     *
     * @param string $property Property to convert
     * @return string
     */
    public function propertyToPropertyId(string $property): string
    {
        return UuidFactory::uuid(AccountSetting::UUID_NAMESPACE . $property);
    }

    /**
     * Find setting per property
     *
     * @param \Cake\ORM\Query $query Query
     * @param array $options Option with property
     * @return \Cake\ORM\Query
     */
    public function findByProperty(Query $query, array $options): Query
    {
        if (!isset($options['property'])) {
            throw new InternalErrorException(__('The parameter {0} is not set.', 'property'));
        }

        $property = $options['property'];

        return $query->where([$this->aliasField('property_id') => $this->propertyToPropertyId($property)]);
    }
}
