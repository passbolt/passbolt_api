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
namespace Passbolt\WebInstaller\Form;

use App\Model\Entity\Role;
use App\Utility\Healthchecks;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

class DatabaseConfigurationForm extends Form
{
    const TMP_CONNECTION_NAME = 'test_passbolt_db';

    /**
     * Database configuration schema.
     * @param Schema $schema shchema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('host', 'string')
            ->addField('port', ['type' => 'string'])
            ->addField('username', ['type' => 'string'])
            ->addField('password', ['type' => 'string'])
            ->addField('database', ['type' => 'string']);
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('host', 'create', __('A host name is required.'))
            ->notEmpty('host', __('A host name is required.'))
            ->utf8('host', __('The host is not a valid utf8 string.'));

        $validator
            ->requirePresence('port', 'create', __('A port number is required.'))
            ->notEmpty('port', __('A port number is required.'))
            ->numeric('port', __('Port number should be numeric'))
            ->range('port', [0, 65535], __('Port should be between 0 and 65535'));

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->notEmpty('username', __('A username is required.'))
            ->utf8('username', __('The username is not a valid utf8 string.'));

        $validator
            ->requirePresence('password', 'create', __('A password is required.'))
            ->notEmpty('password', __('A password is required.'))
            ->utf8('password', __('The host is not a valid utf8 string.'));

        $validator
            ->requirePresence('database', 'create', __('A database is required.'))
            ->notEmpty('database', __('A database is required.'))
            ->utf8('database', __('The database is not a valid utf8 string.'));

        return $validator;
    }

    /**
     * Test database connection.
     * @param array $data form data
     * @throw Exception when a connection cannot be established
     * @return void
     */
    public function testConnection($data)
    {
        $connection = $this->getConnection($data);
        try {
            $connection->execute('SHOW TABLES')->fetchAll('assoc');
        } catch (\PDOException $e) {
            throw new Exception(__('A connection could not be established with the credentials provided. Please verify the settings.'));
        }
    }

    /**
     * Get connection based on connection parameters provided.
     * @param array $data form data
     * @return \Cake\Datasource\ConnectionInterface
     */
    public function getConnection($data)
    {
        try {
            $connection = ConnectionManager::get(self::TMP_CONNECTION_NAME);
        } catch (\Exception $e) {
            $this->_setConnection($data);
            $connection = ConnectionManager::get(self::TMP_CONNECTION_NAME);
        }

        return $connection;
    }

    /**
     * Set Connection configuration.
     * @param array $data form data
     * @return void
     */
    protected function _setConnection($data)
    {
        ConnectionManager::setConfig(self::TMP_CONNECTION_NAME, [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'password' => $data['password'],
            'database' => $data['database'],
            'encoding' => 'utf8',
            'timezone' => 'UTC',
        ]);
    }

    /**
     * Check that the passbolt database has at least one admin user.
     * @param array $data form data
     * @throws Exception when the database schema is not the right one
     * @return int number of admin users
     */
    public function checkDbHasAdmin($data)
    {
        $connection = $this->getConnection($data);

        // Check if database is populated with tables.
        $tables = $connection->execute('SHOW TABLES')->fetchAll();
        $tables = Hash::extract($tables, '{n}.0');

        if (count($tables) == 0) {
            return 0;
        }

        // Database already exist, check whether the schema is valid, and how many admins are there.
        $expected = Healthchecks::getSchemaTables(1);
        foreach ($expected as $expectedTableName) {
            if (!in_array($expectedTableName, $tables)) {
                throw new Exception(__('The database schema does not match the one expected'));
            }
        }

        $roles = TableRegistry::get('Roles');
        $roles->setConnection($connection);

        $users = TableRegistry::get('Users');
        $users->setConnection($connection);

        $roleId = $roles->getIdByName(Role::ADMIN);
        $nbAdmins = $users->find()
            ->where(['role_id' => $roleId])
            ->count();

        return $nbAdmins;
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
