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
namespace PassboltWebInstaller\Controller;

use Cake\Controller\Controller;
use Cake\Core\Exception\Exception;
use Cake\Controller\Component\FlashComponent;
use Cake\Datasource\ConnectionManager;

class DatabaseController extends Controller
{
    var $components = ['Flash'];

    const TMP_CONNECTION_NAME = 'test_passbolt_db';

    /**
     * Index
     */
    function index() {
        if(!empty($this->request->getData())) {
            try {
                $this->_setConnection($this->request->getData());
                $this->_testConnection($this->request->getData());
            }
            catch(Exception $e) {
                $this->Flash->error($e->getMessage());
                return $this->render('Pages/database');
            }

            // Database is valid, store information in the session.
            $session = $this->request->getSession();
            $session->write('database', $this->request->getData());
            return $this->redirect('install/gpg_key');
        }

        $this->render('Pages/database');
    }

    /**
     * Test database connection.
     * @param $data
     */
    private function _testConnection($data) {
        $connection = ConnectionManager::get(self::TMP_CONNECTION_NAME);
        try {
            $results = $connection->execute('SHOW TABLES')->fetchAll('assoc');
        } catch(\PDOException $e) {
            throw new Exception(__('A connection could not be established with the credentials provided. Please verify the settings.'));
        }

        if(!empty($results)) {
            throw new Exception(__('The database "{0}" already contains data. Please use an empty database.', [$data['name']]));
        }
    }

    /**
     * Set Connection configuration.
     * @param $data
     */
    private function _setConnection($data) {
        ConnectionManager::setConfig(SELF::TMP_CONNECTION_NAME, [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'password' => $data['password'],
            'database' => $data['name'],
            'encoding' => 'utf8',
            'timezone' => 'UTC',
        ]);
    }

}