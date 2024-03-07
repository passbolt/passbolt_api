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

namespace App\Model\Table;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\OrganizationSetting;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class OrganizationSettingsTable
 *
 * @package App\Model\Table
 * @method \App\Model\Entity\OrganizationSetting newEmptyEntity()
 * @method \App\Model\Entity\OrganizationSetting newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrganizationSetting findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrganizationSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationSetting[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationSetting|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrganizationSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\OrganizationSetting>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\OrganizationSetting>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\OrganizationSetting>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\OrganizationSetting>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrganizationSettingsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
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
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->maxLength('property', 256, __('The property length should be maximum {0} characters.', 256))
            ->requirePresence('property', 'create', __('A property is required.'))
            ->notEmptyString('property', __('The property should not be empty.'))
            ->regex(
                'property',
                '/^[a-zA-Z][a-zA-Z.]+[a-z]$/',
                __('The property should be an alphabetical string and only dot is accepted as special characters.')
            );

        $validator
            ->uuid('property_id', __('The property identifier should be a valid UUID.'))
            ->requirePresence('property_id', 'create', __('A property identifier is required.'))
            ->notEmptyString('property_id', __('The property identifier should not be empty.'));

        $validator
            ->utf8Extended('value', __('The value should be a valid UTF8 string.'))
            ->maxLength('value', 10240, __('The value length should be maximum {0} characters.', 10240))
            ->requirePresence('value', 'create', __('A value is required.'))
            ->notEmptyString('value', __('The value should not be empty.'));

        return $validator;
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
        // Add rule
        $rules->add($rules->isUnique(['property_id']), 'uniquePropertyId', [
            'message' => __('This property id is already in use.'),
        ]);

        return $rules;
    }

    /**
     * Find all the settings for a given property
     *
     * @param string $property The name of the property to get
     * @return \App\Model\Entity\OrganizationSetting The first result from the ResultSet.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no first record.
     */
    public function getFirstSettingOrFail(string $property): OrganizationSetting
    {
        $settingNamespace = OrganizationSetting::UUID_NAMESPACE . $property;
        /** @var \App\Model\Entity\OrganizationSetting $setting */
        $setting = $this->find()
            ->where(['property_id' => UuidFactory::uuid($settingNamespace)])
            ->firstOrFail();

        return $setting;
    }

    /**
     * Get an entry for a given user and property
     *
     * @param string $property user property
     * @return \App\Model\Entity\OrganizationSetting|null The first result from the ResultSet.
     */
    public function getByProperty(string $property): ?OrganizationSetting
    {
        try {
            return $this->getFirstSettingOrFail($property);
        } catch (RecordNotFoundException $e) {
            return null;
        }
    }

    /**
     * Create (or update) an organization setting
     *
     * @param string $property The property name
     * @param string|array $value The property value
     * @param \App\Utility\UserAccessControl $control user access control object
     * @return \App\Model\Entity\OrganizationSetting
     * @throws \Cake\Http\Exception\UnauthorizedException When user role is not admin.
     * @throws \App\Error\Exception\CustomValidationException When there are validation errors.
     * @throws \Cake\Http\Exception\InternalErrorException|\Exception When unable to save the entity.
     */
    public function createOrUpdateSetting(string $property, $value, UserAccessControl $control): OrganizationSetting
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
            $settingValues['modified_by'] = $control->getId();
            $settingItem = $this->patchEntity($settingItem, $settingValues);
        } else {
            $settingValues['created_by'] = $settingValues['modified_by'] = $control->getId();
            $settingItem = $this->newEntity(array_merge($settingFinder, $settingValues));
        }
        if ($settingItem->getErrors()) {
            throw new CustomValidationException(__('This is not a valid setting.'), $settingItem->getErrors(), $this);
        }
        if (!$this->save($settingItem)) {
            throw new InternalErrorException('Could not save the setting, please try again later.');
        }

        return $settingItem;
    }

    /**
     * Delete an organization setting
     *
     * @param string $property The property name
     * @param \App\Utility\UserAccessControl $control user access control object
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
     *
     * @param string $property property name
     * @return string (uuid) property id
     * @throws \Exception if cannot generate a random UUID
     */
    protected function _getSettingPropertyId(string $property): string
    {
        $settingNamespace = OrganizationSetting::UUID_NAMESPACE . $property;

        return UuidFactory::uuid($settingNamespace);
    }
}
