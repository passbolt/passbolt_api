<?php
declare(strict_types=1);

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
namespace Passbolt\DirectorySync\Form;

use App\Model\Entity\Role;
use Cake\Core\Configure;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\DirectorySync\Utility\DirectoryFactory;
use Passbolt\DirectorySync\Utility\DirectoryInterface;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class LdapConfigurationForm extends Form
{
    public const CONNECTION_TYPE_PLAIN = 'plain';
    public const CONNECTION_TYPE_SSL = 'ssl';
    public const CONNECTION_TYPE_TLS = 'tls';
    public const AUTHENTICATION_TYPE_BASIC = 'basic';
    public const AUTHENTICATION_TYPE_SASL = 'sasl';

    public const SUPPORTED_DIRECTORY_TYPE = [DirectoryInterface::TYPE_AD, DirectoryInterface::TYPE_OPENLDAP];

    /**
     * @var string[]
     */
    public static $connectionTypes = [
        self::CONNECTION_TYPE_PLAIN,
        self::CONNECTION_TYPE_SSL,
        self::CONNECTION_TYPE_TLS,
    ];

    /**
     * @var string[]
     */
    public static $authenticationTypes = [
        self::AUTHENTICATION_TYPE_BASIC,
        self::AUTHENTICATION_TYPE_SASL,
    ];

    /**
     * Mapping of the object properties with the configuration paths.
     * Note that the connection_type property is mapped manually.
     *
     * @var array
     */
    private static $configurationMapping = [
        'enabled' => 'enabled',
        'source' => 'source',
        'directory_type' => 'ldap.domains.{DOMAIN}.ldap_type',
        'domain_name' => 'ldap.domains.{DOMAIN}.domain_name',
        'username' => 'ldap.domains.{DOMAIN}.username',
        'password' => 'ldap.domains.{DOMAIN}.password',
        'base_dn' => 'ldap.domains.{DOMAIN}.base_dn',
        'hosts' => 'ldap.domains.{DOMAIN}.hosts',
        'port' => 'ldap.domains.{DOMAIN}.port',
        'group_object_class' => 'groupObjectClass',
        'user_object_class' => 'userObjectClass',
        'group_path' => 'groupPath',
        'user_path' => 'userPath',
        'group_custom_filters' => 'groupCustomFilters',
        'user_custom_filters' => 'userCustomFilters',
        'use_email_prefix_suffix' => 'useEmailPrefixSuffix',
        'email_prefix' => 'emailPrefix',
        'email_suffix' => 'emailSuffix',
        'default_user' => 'defaultUser',
        'default_group_admin_user' => 'defaultGroupAdminUser',
        'users_parent_group' => 'usersParentGroup',
        'groups_parent_group' => 'groupsParentGroup',
        'enabled_users_only' => 'enabledUsersOnly',
        'sync_users_create' => 'jobs.users.create',
        'sync_users_update' => 'jobs.users.update',
        'sync_users_delete' => 'jobs.users.delete',
        'sync_groups_create' => 'jobs.groups.create',
        'sync_groups_delete' => 'jobs.groups.delete',
        'sync_groups_update' => 'jobs.groups.update',
        'fields_mapping' => 'fieldsMapping',
    ];

    /**
     * Database configuration schema.
     *
     * @param \Cake\Form\Schema $schema shchema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): \Cake\Form\Schema
    {
        return $schema
            ->addField('enabled', 'boolean')
            ->addField('group_object_class', 'string')
            ->addField('user_object_class', 'string')
            ->addField('group_path', 'string')
            ->addField('user_path', 'string')
            ->addField('user_custom_filters', 'string')
            ->addField('group_custom_filters', 'string')
            ->addField('use_email_prefix_suffix', 'boolean')
            ->addField('email_prefix', 'string')
            ->addField('email_suffix', 'string')
            ->addField('default_user', 'string')
            ->addField('default_group_admin_user', 'string')
            ->addField('users_parent_group', 'string')
            ->addField('groups_parent_group', 'string')
            ->addField('enabled_users_only', 'boolean')
            ->addField('sync_users_create', 'boolean')
            ->addField('sync_users_update', 'boolean')
            ->addField('sync_users_delete', 'boolean')
            ->addField('sync_groups_create', 'boolean')
            ->addField('sync_groups_delete', 'boolean')
            ->addField('sync_groups_update', 'boolean');
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->isArray('domains')
            ->hasAtLeast('domains', 1, __('Need at least one domain configuration.'))
            ->addNestedMany('domains', $this->getDomainValidator())
            ->add('domains', 'connection_names', [
                'rule' => function ($value, $context) {
                    $domains = Hash::get($context, 'data.domains', []);
                    foreach ($domains as $name => $data) {
                        if (str_contains($name, '.')) {
                            return __('The connection name `{0}` should not contain dots', [$name]);
                        }
                    }

                    return true;
                },
                'message' => __('Invalid connection name'),
            ]);

        $validator
            ->requirePresence('default_user', 'create', __('The identifier of the default admin user is required.'))
            ->notEmptyString('default_user', __('The identifier of the default admin user should not be empty.'))
            ->uuid('default_user', __('The identifier of the default admin user should be a valid UUID.'))
            ->add('default_user', ['isValidAdmin' => [
                'rule' => [$this, 'isValidAdmin'],
                'message' => __('The admin user does not exist.'),
            ]]);

        $validator
            ->requirePresence(
                'default_group_admin_user',
                'create',
                __('The identifier of the default group admin user is required.')
            )
            ->notEmptyString(
                'default_group_admin_user',
                __('The identifier of the default group admin user should not be empty.')
            )
            ->uuid(
                'default_group_admin_user',
                __('The identifier of the default group admin user should be a valid UUID.')
            )
            ->add('default_group_admin_user', ['isValidUser' => [
                'rule' => [$this, 'isValidUser'],
                'message' => __('The group admin user does not exist.'),
            ]]);

        $validator
            ->allowEmptyString('group_object_class')
            ->utf8('group_object_class', __('The group object class should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('user_object_class')
            ->utf8('user_object_class', __('The user object class should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('group_path')
            ->utf8('group_path', __('The group object class should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('user_path')
            ->utf8('user_path', __('The user path should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('group_custom_filters')
            ->utf8('group_custom_filters', __('The group custom filter should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('user_custom_filters')
            ->utf8('user_custom_filters', __('The user custom filter should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyTime('use_email_prefix_suffix')
            ->boolean('use_email_prefix_suffix', __('The email prefix/suffix setting should be a valid boolean.'));

        $validator
            ->allowEmptyString('email_prefix')
            ->utf8('email_prefix', __('The email prefix should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('email_suffix')
            ->utf8('email_suffix', __('The email suffix should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('users_parent_group')
            ->utf8('users_parent_group', __('The users parent group should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('groups_parent_group')
            ->utf8('groups_parent_group', __('The groups parent group should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('enabled_users_only')
            ->boolean('enabled_users_only', __('The enabled users only setting should be a boolean.'));

        $validator
            ->allowEmptyString('sync_users_create')
            ->boolean('sync_users_create', __('The sync of created users setting should be a boolean.'));

        $validator
            ->allowEmptyString('sync_users_update')
            ->boolean('sync_users_update', __('The sync of updated users setting should be a boolean.'));

        $validator
            ->allowEmptyString('sync_users_delete')
            ->boolean('sync_users_delete', __('The sync of deleted users setting should be a boolean.'));

        $validator
            ->allowEmptyString('sync_groups_create')
            ->boolean('sync_groups_create', __('The sync of created groups setting should be a boolean.'));

        $validator
            ->allowEmptyString('sync_groups_delete')
            ->boolean('sync_groups_delete', __('The sync of deleted groups setting should be a boolean.'));

        $validator
            ->allowEmptyString('sync_groups_update')
            ->boolean('sync_groups_update', __('The sync of updated groups setting should be a boolean.'));

        $defaultSettings = DirectoryOrgSettings::getDefaultSettings();
        $fieldsMappingValidator = new Validator();
        foreach (Hash::get($defaultSettings, 'fieldsMapping', []) as $directoryType => $mappings) {
            $typeValidator = new Validator();
            $this->addFieldsMapValidator($typeValidator, 'user', array_keys($mappings['user']));
            $this->addFieldsMapValidator($typeValidator, 'group', array_keys($mappings['group']));

            $fieldsMappingValidator->addNested($directoryType, $typeValidator);
        }

        $validator
            ->isArray('fields_mapping', __('The fields mapping should be a valid array.'))
            ->addNested('fields_mapping', $fieldsMappingValidator);

        $validator = $this->addForbiddenFieldsValidations($validator);

        return $validator;
    }

    /**
     * Return the validator for the domain data
     *
     * @return \Cake\Validation\Validator
     */
    private function getDomainValidator(): Validator
    {
        $validator = new Validator();
        $validator
            ->requirePresence('directory_type', 'create', __('A directory type is required.'))
            ->notEmptyString('directory_type', __('The directory type should not be empty.'))
            ->inList(
                'directory_type',
                self::SUPPORTED_DIRECTORY_TYPE,
                __(
                    'The directory type should be one of the following: {0}.',
                    implode(', ', self::SUPPORTED_DIRECTORY_TYPE)
                )
            );

        $validator
            ->requirePresence('domain_name', 'create', __('A domain name is required.'))
            ->notEmptyString('domain_name', __('The domain name should not be empty.'))
            ->utf8('domain_name', __('The domain name should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('username')
            ->utf8('username', __('The username should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('password')
            ->utf8('password', __('The password should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('base_dn')
            ->utf8('base_dn', __('The base DN should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('hosts', 'create', __('At least one host is required.'))
            ->hasAtLeast('hosts', 1, __('At least one host is required.'))
            ->isArray('hosts', __('The hosts should be a valid array.'));

        $validator
            ->requirePresence('port', 'create', __('A port number is required.'))
            ->notEmptyString('port', __('The port number should not be empty.'))
            ->numeric('port', __('The port number should be numeric.'))
            ->range('port', [0, 65535], __('The port number should be between 0 and 65535'));

        $validator
            ->requirePresence('connection_type', 'create', __('A connection type is required.'))
            ->notEmptyString('connection_type', __('The connection type should not be empty.'))
            ->inList(
                'connection_type',
                self::$connectionTypes,
                __(
                    'The connection type should be one of the following: {0}.',
                    implode(', ', self::$connectionTypes)
                )
            );
        $validator
            ->requirePresence('authentication_type', 'create', __('An authentication type is required.'))
            ->notEmptyString('authentication_type', __('The authentication type should not be empty.'))
            ->inList(
                'authentication_type',
                self::$authenticationTypes,
                __(
                    'The authentication type should be one of the following: {0}.',
                    implode(', ', self::$authenticationTypes)
                )
            );

        return $validator;
    }

    /**
     * Add the nested validator for a section of a directory type
     *
     * @param \Cake\Validation\Validator $typeValidator parent type validator
     * @param string $section section name (user, group)
     * @param array $fields fields list for the section
     * @return \Cake\Validation\Validator
     */
    private function addFieldsMapValidator(Validator $typeValidator, string $section, array $fields): Validator
    {
        $forbiddenFieldsActive = Configure::read('passbolt.security.directorySync.forbiddenFields.active');

        $sectionValidator = new Validator();
        foreach ($fields as $fieldName) {
            $sectionValidator
                ->requirePresence($fieldName, true, __('The map for this field is required.'))
                ->notEmptyString($fieldName, __('The map value should not be empty.'))
                ->scalar($fieldName)
                ->utf8($fieldName, __('The field name should be a valid BMP-UTF8 string.'))
                ->maxLength($fieldName, 128, __('The map value length should be maximum {0} characters.', 128));

            if ($forbiddenFieldsActive) {
                $sectionValidator->add($fieldName, ['forbiddenField' => [
                    'rule' => [$this, 'isFieldNameAllowed'],
                    'message' => __('The map value should not be from forbidden fields.'),
                ]]);
            }
        }

        $typeValidator
            ->requirePresence($section, true, __('The map configuration for `{0}` fields is required.', [$section]))
            ->isArray($section)
            ->addNested($section, $sectionValidator);

        return $typeValidator;
    }

    /**
     * @param \Cake\Validation\Validator $validator Validator object.
     * @return \Cake\Validation\Validator
     */
    private function addForbiddenFieldsValidations(Validator $validator): Validator
    {
        $forbiddenFieldsActive = Configure::read('passbolt.security.directorySync.forbiddenFields.active');

        if (!$forbiddenFieldsActive) {
            return $validator;
        }

        $validator
            ->maxLength(
                'group_object_class',
                128,
                __('The group object class length should be maximum {0} characters.', 128)
            )
            ->add('group_object_class', ['forbiddenField' => [
                'rule' => [$this, 'isFieldNameAllowed'],
                'message' => __('The group object class should not be from forbidden fields.'),
            ]]);

        $validator
            ->maxLength(
                'user_object_class',
                128,
                __('The user object class length should be maximum {0} characters.', 128)
            )
            ->add('user_object_class', ['forbiddenField' => [
                'rule' => [$this, 'isFieldNameAllowed'],
                'message' => __('The user object class should not be from forbidden fields.'),
            ]]);

        $validator
            ->maxLength(
                'user_custom_filters',
                10000,
                __('The user custom filter length should be maximum {0} characters.', 10000) // 10k limit
            )
            ->add('user_custom_filters', ['containsForbiddenField' => [
                'rule' => [$this, 'isFilterValueAllowed'],
                'message' => __('The user custom filter contains the forbidden field.'),
            ]]);

        $validator
            ->maxLength(
                'group_custom_filters',
                10000,
                __('The group custom filter length should be maximum {0} characters.', 10000) // 10k limit
            )
            ->add('group_custom_filters', ['containsForbiddenField' => [
                'rule' => [$this, 'isFilterValueAllowed'],
                'message' => __('The group custom filter contains the forbidden field.'),
            ]]);

        return $validator;
    }

    /**
     * Check if an admin user exists.
     *
     * @param string $userId The user id
     * @param array $context not in use
     * @return bool
     */
    public function isValidAdmin(string $userId, ?array $context = null): bool
    {
        if (!Validation::uuid($userId)) {
            return false;
        }

        return TableRegistry::getTableLocator()->get('Users')
            ->find()
            ->contain(['Roles'])
            ->where(['Users.id' => $userId, 'Users.active' => 1, 'Users.deleted' => 0, 'Roles.name' => Role::ADMIN])
            ->count() > 0;
    }

    /**
     * Check if a user exists.
     *
     * @param string $userId user id
     * @return bool
     */
    public function isValidUser(string $userId): bool
    {
        if (!Validation::uuid($userId)) {
            return false;
        }

        return TableRegistry::getTableLocator()->get('Users')
            ->find()
            ->where(['Users.id' => $userId, 'Users.active' => 1, 'Users.deleted' => 0])
            ->count() > 0;
    }

    /**
     * Checks if the field name value is allowed to use (not from forbidden field attributes).
     *
     * @param mixed $fieldName The field name.
     * @param array|null $context Context array. Not used.
     * @return bool
     */
    public function isFieldNameAllowed($fieldName, ?array $context = null): bool
    {
        return !in_array($fieldName, Configure::read('passbolt.security.directorySync.forbiddenFields.fieldNames'));
    }

    /**
     * Checks if the filter value contains any sensitive fields.
     *
     * @param mixed $value The value to check.
     * @param array|null $context Context array. Not used.
     * @return bool
     */
    public function isFilterValueAllowed($value, ?array $context = null): bool
    {
        $forbiddenFields = Configure::read('passbolt.security.directorySync.forbiddenFields.fieldNames');

        foreach ($forbiddenFields as $forbiddenField) {
            if (strpos($value, $forbiddenField) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Transform form data into the expected org settings format
     *
     * @param array|null $data The form data
     * @return array $settings The org settings data
     */
    public static function formatFormDataToOrgSettings(?array $data = [])
    {
        $settings = [];
        if ($data === null || count($data) === 0) {
            return $settings;
        }

        $User = TableRegistry::getTableLocator()->get('Users');
        $data['default_user'] = $User->find()->where(['Users.id' => $data['default_user']])->first()->get('username');
        $data['default_group_admin_user'] = $User->find()
            ->where(['Users.id' => $data['default_group_admin_user']])
            ->first()
            ->get('username');

        foreach ($data as $prop => $propVal) {
            if ((!empty($propVal) || $propVal === false) && isset(self::$configurationMapping[$prop])) {
                $settings[self::$configurationMapping[$prop]] = $propVal;
            }
        }
        $domains = Hash::get($data, 'domains', []);
        foreach ($domains as $domain => $properties) {
            foreach ($properties as $prop => $propVal) {
                if (
                    (!empty($propVal) || $propVal === false) && isset(self::$configurationMapping[$prop]) &&
                    strpos(self::$configurationMapping[$prop], '{DOMAIN}') !== false
                ) {
                    $key = str_replace('{DOMAIN}', (string)$domain, self::$configurationMapping[$prop]);
                    $settings[$key] = $propVal;
                }
            }
            $settings["ldap.domains.$domain.use_ssl"] = $properties['connection_type'] === 'ssl' ? 1 : 0;
            $settings["ldap.domains.$domain.use_tls"] = $properties['connection_type'] === 'tls' ? 1 : 0;
            $settings["ldap.domains.$domain.use_sasl"] =
                Hash::get($properties, 'authentication_type') === self::AUTHENTICATION_TYPE_SASL ? 1 : 0;

            if (!isset($settings["ldap.domains.$domain.password"]) && !$settings["ldap.domains.$domain.use_sasl"]) {
                $settings["ldap.domains.$domain.password"] = DirectoryOrgSettings::get()->getPassword($domain);
            }
        }

        $settings = Hash::expand($settings);

        return $settings;
    }

    /**
     * Transform a configuration array into ldap configuration form data
     *
     * @param array|null $settings The organization settings
     * @return array LdapConfigurationForm data
     */
    public static function formatOrgSettingsToFormData(?array $settings = [])
    {
        $data = [];
        $fieldsMapping = Hash::get($settings, 'fieldsMapping', []);
        $domains = Hash::get($settings, 'ldap.domains', []);
        $settings = Hash::flatten($settings);
        if (empty($settings)) {
            return $data;
        }

        $User = TableRegistry::getTableLocator()->get('Users');
        if (isset($settings['defaultUser'])) {
            $defaultUser = $User->find()->where([
                'Users.username' => $settings['defaultUser'],
                'Users.deleted' => false,
                'Users.active' => true,
            ])->first();
            if (empty($defaultUser)) {
                Log::warning("LdapConfigurationForm: Default user ({$settings['defaultUser']}) not found");
                $settings['defaultUser'] = '';
            } else {
                $settings['defaultUser'] = $defaultUser->get('id');
            }
        }
        if (isset($settings['defaultGroupAdminUser'])) {
            $defaultGroupAdminUser = $User->find()
                ->where([
                    'Users.username' => $settings['defaultGroupAdminUser'],
                    'Users.deleted' => false,
                    'Users.active' => true,
                ])->first();
            if (empty($defaultGroupAdminUser)) {
                $u = $settings['defaultGroupAdminUser'];
                Log::warning("LdapConfigurationForm: Default group admin user ({$u}) not found");
                $settings['defaultGroupAdminUser'] = '';
            } else {
                $settings['defaultGroupAdminUser'] = $defaultGroupAdminUser->get('id');
            }
        }

        $settings['fieldsMapping'] = $fieldsMapping;

        foreach (self::$configurationMapping as $prop => $propVal) {
            if (isset($settings[$propVal])) {
                $data[$prop] = $settings[$propVal];
            }
        }

        foreach ($domains as $domain => $properties) {
            $settings["ldap.domains.$domain.hosts"] = $properties['hosts'];
            foreach (self::$configurationMapping as $prop => $propVal) {
                if (strpos($propVal, '{DOMAIN}') === false) {
                    continue;
                }
                $key = str_replace('{DOMAIN}', $domain, $propVal);
                if (isset($settings[$key])) {
                    $data['domains'][$domain][$prop] = $settings[$key];
                }
            }
            $data['domains'][$domain]['connection_type'] = self::CONNECTION_TYPE_PLAIN;
            $isSsl = !empty($properties['use_ssl']);
            $isTls = !empty($properties['use_tls']);
            if ($isSsl) {
                $data['domains'][$domain]['connection_type'] = self::CONNECTION_TYPE_SSL;
            } elseif ($isTls) {
                $data['domains'][$domain]['connection_type'] = self::CONNECTION_TYPE_TLS;
            }
            $data['domains'][$domain]['authentication_type'] = self::AUTHENTICATION_TYPE_BASIC;
            $isSasl = !empty($properties['use_sasl']);
            if ($isSasl) {
                $data['domains'][$domain]['authentication_type'] = self::AUTHENTICATION_TYPE_SASL;
            }
        }

        return $data;
    }

    /**
     * Test the ldap
     *
     * @param array $data The user input
     * @throws \Exception if connection cannot be established
     * @return bool
     */
    protected function testConnection(array $data)
    {
        $settings = self::formatFormDataToOrgSettings($data);
        $domains = Hash::get($settings, 'ldap.domains', []);
        foreach ($domains as $domain => $properties) {
            foreach ($properties['hosts'] as $host) {
                $tmpSettings = $settings;
                $tmpProperties = $properties;
                $tmpProperties['hosts'] = [$host];
                $tmpSettings['domains'] = [
                    $domain => $tmpProperties,
                ];
                $directorySettings = new DirectoryOrgSettings($tmpSettings);
                $ldapDirectory = DirectoryFactory::get($directorySettings);
            }
        }

        return true;
    }

    /**
     * Execute implementation.
     *
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        return $this->testConnection($data);
    }
}
