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
namespace Passbolt\DirectorySync\Form;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use LdapTools\Configuration;
use LdapTools\LdapManager;
use Passbolt\DirectorySync\Utility\DirectoryFactory;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class LdapConfigurationForm extends Form
{
    const CONNECTION_TYPE_PLAIN = 'plain';
    const CONNECTION_TYPE_SSL = 'ssl';
    const CONNECTION_TYPE_TLS = 'tls';

    public static $connectionTypes = [
        self::CONNECTION_TYPE_PLAIN,
        self::CONNECTION_TYPE_SSL,
        self::CONNECTION_TYPE_TLS
    ];

    /**
     * Mapping of the object properties with the configuration paths.
     * Note that the connection_type property is mapped manually.
     * @var array
     */
    private static $configurationMapping = [
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
        'sync_groups_update' => 'jobs.groups.update'
    ];

    /**
     * Database configuration schema.
     * @param Schema $schema shchema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
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
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('directory_type', 'create', __('A directory type is required.'))
            ->notEmpty('directory_type', __('A directory type is required.'))
            ->inList('directory_type', ['ad', 'openldap'], __('The directory type is not valid (only ad and openldap are supported).'));

        $validator
            ->requirePresence('domain_name', 'create', __('A domain name is required.'))
            ->notEmpty('domain_name', __('A domain name is required.'))
            ->utf8('domain_name', __('The domain name should be a valid utf8 string.'));

        $validator
            ->allowEmpty('username', __('Username can be empty.'))
            ->utf8('username', __('The username should be a valid utf8 string.'));

        $validator
            ->allowEmpty('password', __('Password can be empty.'))
            ->utf8('password', __('The password should be a valid utf8 string.'));

        $validator
            ->allowEmpty('base_dn', __('Base DN can be empty.'))
            ->utf8('base_dn', __('The base DN should be a valid utf8 string.'));

        $validator
            ->requirePresence('server', 'create', __('A server is required.'))
            ->notEmpty('server', __('A server is required.'))
            ->utf8('server', __('The server should be a valid utf8 string.'));

        $validator
            ->requirePresence('port', 'create', __('A port number is required.'))
            ->notEmpty('port', __('A port number is required.'))
            ->numeric('port', __('Port number should be numeric'))
            ->range('port', [0, 65535], __('Port should be between 0 and 65535'));

        $validator
            ->requirePresence('connection_type', 'create', __('A connection type is required.'))
            ->notEmpty('connection_type', __('A connection type is required.'))
            ->inList('connection_type', self::$connectionTypes, __('The connection type is not valid (only plain, ssl, tls are supported)'));

        $validator
            ->requirePresence('default_user', 'create', __('A default user is required.'))
            ->notEmpty('default_user', __('Default user cannot be empty.'))
            ->uuid(('default_user'), false, __('Default user should be a valid uuid.'))
            ->add('default_user', ['isValidAdmin' => [
                'rule' => [$this, 'isValidAdmin'],
                'message' => __('The admin user provided does not exist.')
            ]]);

        $validator
            ->requirePresence('default_group_admin_user', 'create', __('A default group admin user is required.'))
            ->notEmpty('default_group_admin_user', __('Default group admin user cannot be empty.'))
            ->uuid(('default_group_admin_user'), false, __('Default group admin user should be a valid uuid.'))
            ->add('default_group_admin_user', ['isValidUser' => [
                'rule' => [$this, 'isValidUser'],
                'message' => __('The group admin user provided does not exist.')
            ]]);

        $validator
            ->allowEmpty('group_object_class', __('Group object class cannot be empty.'))
            ->utf8('group_object_class', __('Group object class should be a valid utf8 string.'));

        $validator
            ->allowEmpty('user_object_class', __('User object class cannot be empty.'))
            ->utf8('user_object_class', __('User object class should be a valid utf8 string.'));

        $validator
            ->allowEmpty('group_path')
            ->utf8('group_path', __('Group object class should be a valid utf8 string.'));

        $validator
            ->allowEmpty('user_path')
            ->utf8('user_path', __('User path should be a valid utf8 string.'));

        $validator
            ->allowEmpty('use_email_prefix_suffix')
            ->boolean('use_email_prefix_suffix', __('UseEmailPrefixSuffix should be a boolean.'));

        $validator
            ->allowEmpty('email_prefix')
            ->utf8('email_prefix', __('Email prefix should be a valid utf8 string.'));

        $validator
            ->allowEmpty('email_suffix')
            ->utf8('email_suffix', __('Email suffix should be a valid utf8 string.'));

        $validator
            ->allowEmpty('users_parent_group', __('Users parent group cannot be empty.'))
            ->utf8('users_parent_group', __('Users parent group should be a valid utf8 string.'));

        $validator
            ->allowEmpty('groups_parent_group', __('Groups parent group cannot be empty.'))
            ->utf8('groups_parent_group', __('Groups parent group should be a valid utf8 string.'));

        $validator
            ->allowEmpty('enabled_users_only')
            ->boolean('enabled_users_only', __('Enabled users only should be a boolean.'));

        $validator
            ->allowEmpty('sync_users_create')
            ->boolean('sync_users_create', __('Sync of user when create should be a boolean.'));

        $validator
            ->allowEmpty('sync_users_delete')
            ->boolean('sync_users_delete', __('Sync of user when delete should be a boolean.'));

        $validator
            ->allowEmpty('sync_groups_create')
            ->boolean('sync_groups_create', __('Sync of groups when create should be a boolean.'));

        $validator
            ->allowEmpty('sync_groups_delete')
            ->boolean('sync_groups_delete', __('Sync of groups when delete should be a boolean.'));

        $validator
            ->allowEmpty('sync_groups_update')
            ->boolean('sync_groups_update', __('Sync of groups when yodate should be a boolean.'));

        return $validator;
    }

    /**
     * Check if an admin user exists.
     *
     * @param string $value The user id
     * @param array $context not in use
     * @return bool
     */
    public function isValidAdmin(string $value, array $context = null)
    {
        $User = TableRegistry::getTableLocator()->get('Users');
        $exist = $User->find()->contain(['Roles'])->where(['Users.id' => $value, 'Users.active' => 1, 'Users.deleted' => 0, 'Roles.name' => Role::ADMIN])->count();
        if ($exist) {
            return true;
        }

        return false;
    }

    /**
     * Check if a user exists.
     *
     * @param string $value user id
     * @param array $context not in use
     * @return bool
     */
    public function isValidUser(string $value, array $context = null)
    {
        $User = TableRegistry::getTableLocator()->get('Users');
        $exist = $User->find()->where(['Users.id' => $value, 'Users.active' => 1, 'Users.deleted' => 0])->count();
        if ($exist) {
            return true;
        }

        return false;
    }

    /**
     * Transform form data into the expected org settings format
     *
     * @param array $data The form data
     *
     * @return array $settings The org settings data
     */
    public static function formatFormDataToOrgSettings(array $data = [])
    {
        $settings = [];
        if (empty($data)) {
            return $data;
        }

        $User = TableRegistry::getTableLocator()->get('Users');
        $data['default_user'] = $User->find()->where(['Users.id' => $data['default_user']])->first()->get('username');
        $data['default_group_admin_user'] = $User->find()->where(['Users.id' => $data['default_group_admin_user']])->first()->get('username');

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
     * @param array $settings The organization settings
     *
     * @return array LdapConfigurationForm data
     */
    public static function formatOrgSettingsToFormData(array $settings = [])
    {
        $data = [];
        $settings = Hash::flatten($settings);
        if (empty($settings)) {
            return $data;
        }

        $User = TableRegistry::getTableLocator()->get('Users');
        if (isset($settings['defaultUser'])) {
            $defaultUser = $User->find()->where(['Users.username' => $settings['defaultUser']])->first();
            if (empty($defaultUser)) {
                $settings['defaultUser'] = '';
            } else {
                $settings['defaultUser'] = $defaultUser->get('id');
            }
        }
        if (isset($settings['defaultGroupAdminUser'])) {
            $defaultGroupAdminUser = $User->find()->where(['Users.username' => $settings['defaultGroupAdminUser']])->first();
            if (empty($defaultGroupAdminUser)) {
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
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return $this->testConnection($data);
    }
}
