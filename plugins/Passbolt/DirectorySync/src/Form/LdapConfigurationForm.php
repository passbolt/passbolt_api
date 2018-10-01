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
use App\Model\Table\OrganizationSettingsTable;
use App\Utility\Healthchecks;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use App\Utility\Gpg;

class LdapConfigurationForm extends Form
{
    const CONFIG_BASE_KEY = 'passbolt.plugins.directorySync.';

    const CONNECTION_TYPE_PLAIN = 'plain';
    const CONNECTION_TYPE_SSL = 'ssl';
    const CONNECTION_TYPE_TLS = 'tls';

    private $connectionTypes = [
        self::CONNECTION_TYPE_PLAIN,
        self::CONNECTION_TYPE_SSL,
        self::CONNECTION_TYPE_TLS
    ];

    /**
     * Mapping of the object properties with the configuration paths.
     * @var array
     */
    private $configurationMapping = [
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
        'default_user' => 'defaultUser',
        'default_group_admin_user' => 'defaultGroupAdminUser',
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
            ->addField('user_path', 'string');
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
            ->requirePresence('username', 'create', __('A username is required.'))
            ->notEmpty('username', __('A username is required.'))
            ->utf8('username', __('The username should be a valid utf8 string.'));

        $validator
            ->requirePresence('password', 'create', __('A password is required.'))
            ->notEmpty('password', __('A password is required.'))
            ->utf8('password', __('The password should be a valid utf8 string.'));

        $validator
            ->requirePresence('base_dn', 'create', __('A base DN is required.'))
            ->notEmpty('base_dn', __('A base DN is required.'))
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
            ->inList('connection_type', $this->connectionTypes, __('The connection type is not valid (only plain, ssl, tls are supported)'));

        $validator
            ->requirePresence('default_user', 'create', __('A default user is required.'))
            ->notEmpty('default_user', __('Default user cannot be empty.'))
            ->email(('default_user'), false, __('Default user should be an email'))
            ->add('default_user', ['isValidAdmin' => [
                'rule' => [$this, 'isValidAdmin'],
                'message' => __('The admin user provided does not exist.')
            ]]);

        $validator
            ->requirePresence('default_group_admin_user', 'create', __('A default group admin user is required.'))
            ->notEmpty('default_group_admin_user', __('Default group admin user cannot be empty.'))
            ->email(('default_group_admin_user'), false, __('Default group admin user should be an email'))
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
        $User = TableRegistry::get('Users');
        $exist = $User->find()->contain(['Roles'])->where(['username' => $value, 'active' => 1, 'deleted' => 0, 'Roles.name' => Role::ADMIN])->count();
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
        $User = TableRegistry::get('Users');
        $exist = $User->find()->where(['username' => $value, 'active' => 1, 'deleted' => 0])->count();
        if ($exist) {
            return true;
        }

        return false;
    }

    /**
     * Save configuration from from data.
     *
     * @param array $data form data
     * @param UserAccessControl $control access control
     *
     * @return array settings as they have been saved
     */
    public function saveConfiguration(array $data, UserAccessControl $control)
    {
        $validate = $this->validate($data);
        if (!$validate) {
            throw new CustomValidationException(
                __('Could not validate ldap configuration data'),
                $this->errors()
            );
        }

        $conf = $this->dataToConfig($data);
        $conf = Hash::insert([], trim(self::CONFIG_BASE_KEY, '.'), $conf);
        $OrganizationSettings = TableRegistry::get('OrganizationSettings');
        $savedSettings = $OrganizationSettings->saveOrganizationSettings($conf, $control);

        return $savedSettings;
    }

    /**
     * Read ldap configuration from organization settings and return them as form data.
     *
     * @return array form data
     */
    public function readConfiguration() {
        $OrganizationSettings = TableRegistry::get('OrganizationSettings');
        $settings = $OrganizationSettings->getOrganizationSettings();
        $settings = Hash::extract($settings, trim(self::CONFIG_BASE_KEY, '.'));
        $data = $this->configToData($settings);

        return $data;
    }

    /**
     * Transorm configuration data into a configuration array (without base key prefix).
     *
     * @param array $data ldap configuration data
     *
     * @return array configuration
     */
    public function dataToConfig(array $data) {
        $conf = [];
        foreach($data as $prop => $propVal) {
            if (!empty($propVal) && isset($this->configurationMapping[$prop])) {
                $conf[$this->configurationMapping[$prop]] = $propVal;
            }
        }
        $conf = array_merge($conf, $this->_connectionTypeToConfig($data['connection_type']));
        if (isset($conf['ldap.domains.org_domain.password'])) {
            $conf['ldap.domains.org_domain.password'] = OrganizationSettingsTable::encryptData(
                $conf['ldap.domains.org_domain.password']
            );
        }
        $conf = Hash::expand($conf);

        return $conf;
    }

    /**
     * Transform a configuration array into ldap configuration form data
     *
     * @param array $directoryConfig configuration array without the base key.
     *
     * @return array LdapConfigurationForm data
     */
    public function configToData(array $directoryConfig) {
        $data = [];
        $config = Hash::flatten($directoryConfig);
        foreach($this->configurationMapping as $prop => $propVal) {
            if (isset($config[$propVal])) {
                $data[$prop] = $config[$propVal];
            }
        }
        $data['connection_type'] = $this->_configToConnectionType($config);
        if (isset($data['password'])) {
            $data['password'] = OrganizationSettingsTable::decryptData($data['password']);
        }

        return $data;
    }

    /**
     * Transform a connection type to config element.
     *
     * A connection type can be ssl, tls or plain. But in the configuration it does not reflect like this.
     * For example, for ssl it will be use_ssl => 1
     * for tls it will be use_tls => 1
     * We need to adapt the configuration manually.
     *
     * @param string $connectionType connection type (ssl, tls, plain).
     *
     * @return array configuration
     */
    protected function _connectionTypeToConfig(string $connectionType) {
        $conf = [
            'ldap.domains.org_domain.use_ssl' => 0,
            'ldap.domains.org_domain.use_tls' => 0,
        ];
        if ($connectionType === 'ssl') {
            $conf['ldap.domains.org_domain.use_ssl'] = 1;
        } elseif($connectionType === 'tls') {
            $conf['ldap.domains.org_domain.use_tls'] = 1;
        }

        return $conf;
    }

    /**
     * Read the connection type from a flattened configuration.
     *
     * @param array $flattenedConfig flattened configuration.
     *
     * @return string connection type (ssl, tls, plain).
     */
    protected function _configToConnectionType(array $flattenedConfig) {
        $connectionType = self::CONNECTION_TYPE_PLAIN;
        if (isset($flattenedConfig['ldap.domains.org_domain.use_tls'])
            && $flattenedConfig['ldap.domains.org_domain.use_tls'] == true) {
            $connectionType = self::CONNECTION_TYPE_TLS;
        } elseif (isset($flattenedConfig['ldap.domains.org_domain.use_ssl'])
              && $flattenedConfig['ldap.domains.org_domain.use_ssl'] == true) {
            $connectionType = self::CONNECTION_TYPE_SSL;
        }

        return $connectionType;
    }

    /**
     * Test database connection.
     * @param array $data form data
     * @throw Exception when a connection cannot be established
     * @return void
     */
    public function testConnection(array $data)
    {
        // TODO
    }


    /**
     * Set Connection configuration.
     * @param array $data form data
     * @return void
     */
    protected function _setConnection($data)
    {

    }


    /**
     * Execute implementation.
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }
}
