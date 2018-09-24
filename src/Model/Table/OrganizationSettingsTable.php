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

namespace Passbolt\OrganizationSettings\Model\Table;

use App\Error\Exception\CustomValidationException;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Log\Log;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\OrganizationSettings\Model\Entity\OrganizationSetting;

/**
 * OrganizationSettings Model
 *
 * @method \Passbolt\OrganizationSettings\Model\Entity\OrganizationSetting get($primaryKey, $options = [])
 * @method \Passbolt\OrganizationSettings\Model\Entity\OrganizationSetting newEntity($data = null, array $options = [])
 * @method \Passbolt\OrganizationSettings\Model\Entity\OrganizationSetting[] newEntities(array $data, array $options = [])
 * @method \Passbolt\OrganizationSettings\Model\Entity\OrganizationSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\OrganizationSettings\Model\Entity\OrganizationSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\OrganizationSettings\Model\Entity\OrganizationSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \Passbolt\OrganizationSettings\Model\Entity\OrganizationSetting findOrCreate($search, callable $callback = null, $options = [])
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
            // TODO
//            ->add('property', ['isValidProperty' => [
//                'rule' => [$this, 'isValidProperty'],
//                'message' => __('This setting is not supported.')
//            ]])
            ;

        $validator
            ->utf8Extended('value')
            ->maxLength('property', 10240)
            ->requirePresence('value', ['create', 'update'])
            ->notEmpty('value')
            ->add('property', ['isValidValue' => [
                'rule' => [$this, 'isValidValue'],
                'message' => __('This property value is not supported.')
            ]]);

        return $validator;
    }

    /**
     * Find all the settings for a given property
     *
     * @param string $property The name of the property to get
     * @return \Cake\Datasource\EntityInterface|array The first result from the ResultSet.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no first record.
     */
    public function getFirstPropertyOrFail($property)
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
     * @return OrganizationSetting
     */
    public function createOrUpdateSetting($property, $value)
    {
        $settingId = $this->_getSettingPropertyId($property);
        $settingFinder = ['property_id' => $settingId];
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
            throw new CustomValidationException(__('This is not a valid setting.'), $settingItem->getErrors(), $this);
        }
        if (!$this->save($settingItem)) {
            throw new InternalErrorException(__('Could not save the setting, please try again later.'));
        }

        return $settingItem;
    }

    /**
     * Get settings property id
     * @param string $property property name
     *
     * @return string (uuid) property id
     */
    protected function _getSettingPropertyId(string $property) {
        $settingNamespace = OrganizationSetting::UUID_NAMESPACE . $property;

        return UuidFactory::uuid($settingNamespace);
    }
}
