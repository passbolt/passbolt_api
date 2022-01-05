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
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Passbolt\DirectorySync\Utility\DirectoryFactory;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class LdapConfigurationForm extends Form
{
    public const CONNECTION_TYPE_PLAIN = 'plain';
    public const CONNECTION_TYPE_SSL = 'ssl';
    public const CONNECTION_TYPE_TLS = 'tls';
    public const SUPPORTED_DIRECTORY_TYPE = ['ad', 'openldap'];

    /**
     * @var string[]
     */
    public static $connectionTypes = [
        self::CONNECTION_TYPE_PLAIN,
        self::CONNECTION_TYPE_SSL,
        self::CONNECTION_TYPE_TLS,
    ];

    /**
     * Mapping of the object properties with the configuration paths.
     * Note that the connection_type property is mapped manually.
     *
     * @var array
     */
    private static $configurationMapping = [
        'source' => 'source',
        'directory_type' => 'ldap.domains.org_domain.ldap_type',
        'domain_name' => 'ldap.domains.org_domain.domain_name',
        'username' => 'ldap.domains.org_domain.username',
        'password' => 'ldap.domains.org_domain.password',
        'base_dn' => 'ldap.domains.org_domain.base_dn',
        'server' => 'ldap.domains.org_domain.servers.0',
        'port' => 'ldap.domains.org_domain.port',
        'group_object_class' => 'groupObjectClass',
        'user_object_class' => 'userObjectClass',
        'group_path' => 'groupPath',
        'user_path' => 'userPath',
        'use_email_prefix_suffix' => 'useEmailPrefixSuffix',
        'email_prefix' => 'emailPrefix',
        'email_suffix' => 'emailSuffix',
        'default_user' => 'defaultUser',
        'default_group_admin_user' => 'defaultGroupAdminUser',
        'users_parent_group' => 'usersParentGroup',
        'groups_parent_group' => 'groupsParentGroup',
        'enabled_users_only' => 'enabledUsersOnly',
        'sync_users_create' => 'jobs.users.create',
        'sync_users_delete' => 'jobs.users.delete',
        'sync_groups_create' => 'jobs.groups.create',
        'sync_groups_delete' => 'jobs.groups.delete',
        'sync_groups_update' => 'jobs.groups.update',
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
            ->addField('directory_type', ['type' => 'string'])
            ->addField('domain_name', 'string')
            ->addField('username', ['type' => 'string'])
            ->addField('password', ['type' => 'string'])
            ->addField('base_dn', ['type' => 'string'])
            ->addField('server', ['type' => 'string'])
            ->addField('port', ['type' => 'string'])
            ->addField('connection_type', ['type' => 'string'])
            ->addField('group_object_class', 'string')
            ->addField('user_object_class', 'string')
            ->addField('group_path', 'string')
            ->addField('user_path', 'string')
            ->addField('use_email_prefix_suffix', 'boolean')
            ->addField('email_prefix', 'string')
            ->addField('email_suffix', 'string')
            ->addField('default_user', 'string')
            ->addField('default_group_admin_user', 'string')
            ->addField('users_parent_group', 'string')
            ->addField('groups_parent_group', 'string')
            ->addField('enabled_users_only', 'boolean')
            ->addField('sync_users_create', 'boolean')
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
            ->requirePresence('server', 'create', __('A server is required.'))
            ->notEmptyString('server', __('The server should not be empty.'))
            ->utf8('server', __('The server should be a valid BMP-UTF8 string.'));

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
     * Transform form data into the expected org settings format
     *
     * @param array $data The form data
     * @return array $settings The org settings data
     */
    public static function formatFormDataToOrgSettings(?array $data = [])
    {
        $settings = [];
        if (count($data) === 0) {
            return $data;
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
        $settings['ldap.domains.org_domain.use_ssl'] = $data['connection_type'] === 'ssl' ? 1 : 0;
        $settings['ldap.domains.org_domain.use_tls'] = $data['connection_type'] === 'tls' ? 1 : 0;
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

        foreach (self::$configurationMapping as $prop => $propVal) {
            if (isset($settings[$propVal])) {
                $data[$prop] = $settings[$propVal];
            }
        }

        $data['connection_type'] = self::CONNECTION_TYPE_PLAIN;
        $isSsl = !empty($settings['ldap.domains.org_domain.use_ssl']);
        $isTls = !empty($settings['ldap.domains.org_domain.use_tls']);
        if ($isSsl) {
            $data['connection_type'] = self::CONNECTION_TYPE_SSL;
        } elseif ($isTls) {
            $data['connection_type'] = self::CONNECTION_TYPE_TLS;
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
        $directorySettings = new DirectoryOrgSettings(self::formatFormDataToOrgSettings($data));
        $ldapDirectory = DirectoryFactory::get($directorySettings);

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
