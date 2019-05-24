<?php
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

namespace App\Model\Table;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\OrganizationSetting;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class OrganizationSettingsTable
 * @package App\Model\Table
 */
class OrganizationSettingsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('organization_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->uuid('property_id')
            ->requirePresence('property_id', 'create')
            ->notEmpty('property_id');

        $validator
            ->utf8Extended('value')
            ->maxLength('property', 10240)
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        return $validator;
    }

    /**
     * Custom validation rule to validate fingerprint
     *
     * @param string $value fingerprint
     * @param array $context not in use
     * @return bool
     */
    public function isValidProperty(string $value, array $context = null)
    {
        // Format should always be camel case, without numbers. Only points are allowed as special characters.
        return (preg_match('/^[a-zA-Z][a-zA-Z.]+[a-z]$/', $value) === 1);
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
        // Add rule
        $rules->add($rules->isUnique(['property_id']), 'uniquePropertyId', [
            'message' => __('This property id is already in use.')
        ]);

        return $rules;
    }

    /**
     * Find all the settings for a given property
     *
     * @param string $property The name of the property to get
     * @return \Cake\Datasource\EntityInterface|array The first result from the ResultSet.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no first record.
     */
    public function getFirstSettingOrFail(string $property)
    {
        $settingNamespace = OrganizationSetting::UUID_NAMESPACE . $property;

        return $this->find()
            ->where(['property_id' => UuidFactory::uuid($settingNamespace)])
            ->firstOrFail();
    }

    /**
     * Create (or update) an organization setting
     *
     * @param string $property The property name
     * @param mixed $value The property value
     * @param UserAccessControl $control user access control object
     * @return OrganizationSetting
     */
    public function createOrUpdateSetting(string $property, string $value, UserAccessControl $control)
    {
        if (!$control->isAdmin()) {
            throw new UnauthorizedException(__('Only admin can create or update organization settings.'));
        }

        $settingId = $this->_getSettingPropertyId($property);
        $settingFinder = ['property_id' => $settingId];
        $settingValues = ['value' => $value, 'property' => $property];
        $settingItem = $this->find()
            ->where($settingFinder)
            ->first();
        if ($settingItem) {
            $settingValues['modified_by'] = $control->userId();
            $settingItem = $this->patchEntity($settingItem, $settingValues);
        } else {
            $settingValues['created_by'] = $settingValues['modified_by'] = $control->userId();
            $settingItem = $this->newEntity(array_merge($settingFinder, $settingValues));
        }
        if ($settingItem->getErrors()) {
            throw new CustomValidationException(__('This is not a valid setting.'), $settingItem->getErrors(), $this);
        }
        if (!$this->save($settingItem)) {
            throw new InternalErrorException(__('Could not save the setting, please try again later.'));
        }

        return $settingItem;
    }

    /**
     * Delete an organization setting
     *
     * @param string $property The property name
     * @param UserAccessControl $control user access control object
     * @return void
     */
    public function deleteSetting(string $property, UserAccessControl $control)
    {
        if (!$control->isAdmin()) {
            throw new UnauthorizedException(__('Only admin can create or update organization settings.'));
        }

        $settingId = $this->_getSettingPropertyId($property);
        $settingFinder = ['property_id' => $settingId];
        $settingItem = $this->find()
            ->where($settingFinder)
            ->first();

        if ($settingItem) {
            $this->delete($settingItem);
        }
    }

    /**
     * Get settings property id
     * @param string $property property name
     *
     * @return string (uuid) property id
     */
    protected function _getSettingPropertyId(string $property)
    {
        $settingNamespace = OrganizationSetting::UUID_NAMESPACE . $property;

        return UuidFactory::uuid($settingNamespace);
    }
}
