<?php
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

namespace Passbolt\AccountSettings\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Table\UsersTable;
use App\Utility\UuidFactory;
use Cake\Datasource\EntityInterface;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
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
 * @property \Passbolt\AccountSettings\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting get($primaryKey, $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting newEntity($data = null, array $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting[] newEntities(array $data, array $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \Passbolt\AccountSettings\Model\Entity\AccountSetting findOrCreate($search, callable $callback = null, $options = [])
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
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('account_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => UsersTable::class
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('property')
            ->maxLength('property', 256)
            ->requirePresence('property', 'create')
            ->notEmpty('property')
            ->add('property', ['isValidProperty' => [
                'rule' => [$this, 'isValidProperty'],
                'message' => __('This setting is not supported.')
            ]]);

        $validator
            ->utf8Extended('value')
            ->requirePresence('value', 'create')
            ->notEmpty('value');

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
    public function isValidProperty(string $value, array $context = null)
    {
        return (in_array($value, AccountSetting::SUPPORTED_PROPERTIES));
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * Find all the settings for a given user
     * @param string $userId uuid
     * @return Query
     */
    public function findIndex(string $userId, $whitelist)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id must be a valid uuid.'));
        }

        $props = [];
        foreach ($whitelist as $i => $item) {
            $settingNamespace = AccountSetting::UUID_NAMESPACE . $item;
            $props[] = UuidFactory::uuid($settingNamespace);
        }
        return $this->find()
            ->where(['user_id' => $userId, 'property_id IN' => $props])
            ->all();
    }

    /**
     * Find all the settings for a given user
     *
     * @param string $userId uuid
     * @param string $property The name of the property to get
     * @return \Cake\Datasource\EntityInterface|array The first result from the ResultSet.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no first record.
     */
    public function getFirstPropertyOrFail(string $userId, string $property)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id must be a valid uuid.'));
        }

        $settingNamespace = AccountSetting::UUID_NAMESPACE . $property;

        $entity = $this->find()
            ->where(['user_id' => $userId, 'property_id' => UuidFactory::uuid($settingNamespace)])
            ->firstOrFail();

        return $entity;
    }

    /**
     * Create (or update) an account setting
     *
     * @param string $userId uuid
     * @param string $property The property name
     * @param mixed $value The property value
     * @return AccountSetting
     */
    public function createOrUpdateSetting(string $userId, string $property, string $value)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id must be a valid uuid.'));
        }

        $settingNamespace = AccountSetting::UUID_NAMESPACE . $property;
        $settingFinder = ['user_id' => $userId, 'property_id' => UuidFactory::uuid($settingNamespace)];
        $settingValues = ['value' => $value, 'property' => $property];
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
            throw new InternalErrorException(__('Could not save the setting, please try again later.'));
        }

        return $settingItem;
    }

    /**
     * Delete an entry for a given user and property
     *
     * @param string $userId
     * @param string $property
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
     * @param string $userId
     * @param string $property
     * @return EntityInterface|null
     */
    public function getByProperty(string $userId, string $property)
    {
        $settingNamespace = AccountSetting::UUID_NAMESPACE . $property;
        $settingFinder = ['user_id' => $userId, 'property_id' => UuidFactory::uuid($settingNamespace)];
        return $this->find()
            ->where($settingFinder)
            ->first();
    }
}

