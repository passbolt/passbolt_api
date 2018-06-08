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

use App\Error\Exception\ValidationRuleException;
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
use Passbolt\AccountSettings\Model\Entity\AccountSetting;

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

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Passbolt/AccountSettings.Users'
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
            ->maxLength('value', 256)
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        // Theme validation
        $validator
            ->add('value', ['isValidTheme' => [
                'on' => function ($context) {
                    return (isset($context['data']['property']) && $context['data']['property'] === 'theme');
                },
                'rule' => [$this, 'isValidTheme'],
                'message' => __('This theme is not supported.')
            ]]);

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
     * Custom validation rule to validate account setting property name
     *
     * @param string $value fingerprint
     * @param array $context not in use
     * @return bool
     */
    public function isValidTheme(string $value, array $context = null)
    {
        return in_array($value, Hash::extract($this->findAllThemes(), "{n}.name"));
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
    public function findIndex($userId)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id must be a valid uuid.'));
        }

        return $this->find()
            ->where(['user_id' => $userId])
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
    public function getFirstPropertyOrFail($userId, $property)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id must be a valid uuid.'));
        }

        $settingNamespace = AccountSetting::UUID_NAMESPACE . $property;

        return $this->find()
            ->where(['user_id' => $userId, 'property_id' => UuidFactory::uuid($settingNamespace)])
            ->firstOrFail();
    }

    /**
     * Get the theme to apply
     *
     * @param string $userId uuid
     * @return string value of the theme setting or default
     */
    public function getTheme($userId)
    {
        try {
            $theme = $this->getFirstPropertyOrFail($userId, 'theme');
        } catch (RecordNotFoundException $exception) {
            return null;
        }
        $theme = $theme->toArray();

        return $theme['value'];
    }

    /**
     * Create (or update) an account setting
     *
     * @param string $userId uuid
     * @param string $property The property name
     * @param mixed $value The property value
     * @return AccountSetting
     */
    public function createOrUpdateSetting($userId, $property, $value)
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
            throw new ValidationRuleException(__('This is not a valid setting.'), $settingItem->getErrors(), $this);
        }
        if (!$this->save($settingItem)) {
            throw new InternalErrorException(__('Could not save the setting, please try again later.'));
        }

        return $settingItem;
    }

    /**
     * Return the list of valid themes
     *
     * @return array
     */
    public function findAllThemes() {
        $defaultCssFileName = Configure::read('passbolt.plugins.accountSettings.themes.css');
        $themesPath = WWW_ROOT . 'css' . DS . 'themes';

        $dir = new Folder($themesPath);
        $files = $dir->read(true, true);
        if (!isset($files[0])) {
            throw new InternalErrorException(__('No themes installed.'));
        }
        $response = [];
        foreach ($files[0] as $dir) {
            $cssFilePath = $themesPath . DS . $dir . DS . $defaultCssFileName;
            $defaultPreviewImageName = $dir . '.png';
            $imagePreviewFilePath = IMAGES . DS . 'themes' . DS . $defaultPreviewImageName;
            $cssFile = new File($cssFilePath);
            $imagePreviewFile = new File($imagePreviewFilePath);
            if ($cssFile->exists() && $imagePreviewFile->exists()) {
                $response[] = [
                    'id' => UuidFactory::uuid('theme.id.' . $dir),
                    'name' => $dir,
                    'preview' => Router::url('/img/themes/' . $defaultPreviewImageName, true)
                ];
            } else {
                $msg = __('ThemesIndexController: Could not load theme {0}, the main css file or preview image is missing', $dir);
                Log::error($msg);
            }
        }

        return $response;
    }
}
