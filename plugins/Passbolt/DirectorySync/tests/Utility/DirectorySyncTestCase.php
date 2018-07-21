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
namespace Passbolt\DirectorySync\Test\Utility;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\I18n\FrozenTime;

class DirectorySyncTestCase extends TestCase
{
    private $originalConfig;

    /**
     * @var \Passbolt\DirectorySync\Actions\SyncAction
     */
    protected $action;

    public function setUp()
    {
        $this->originalConfig = Configure::read('passbolt.plugin.directorySync');
        Configure::write('passbolt.plugin.directorySync.test', true);
        parent::setUp();
    }

    public function tearDown()
    {
        if ($this->originalConfig !== null) {
            Configure::write('passbolt.plugin.directorySync', $this->originalConfig);
        }
        parent::tearDown();
    }

    protected function mockDirectoryUserData($fname = null, $lname = null, $username = null, $created = null, $modified = null)
    {
        if (!isset($created)) {
            $created = '2018-07-09 03:56:42.000000';
        }
        if (!isset($modified)) {
            $modified = '2018-07-09 03:56:42.000000';
        }
        $id = 'ldap.user.id.' . strtolower($fname);
        $name = 'CN=' . ucfirst($fname) . ' ' . ucfirst($lname) . ',OU=PassboltUsers,DC=passbolt,DC=local';
        $user = [
            'id' => UuidFactory::uuid($id),
            'directory_name' => $name,
            'directory_created' => new FrozenTime($modified),
            'directory_modified' => new FrozenTime($created),
            'user' => [
                'username' => strtolower($username),
                'profile' => [
                    'first_name' => ucfirst($fname),
                    'last_name' => ucfirst($lname)
                ]
            ]
        ];
        $this->saveMockDirectoryUserData($user);
        return $user;
    }

    protected function mockDirectoryIgnore($id, $model)
    {
        if (!isset($created)) {
            $created = '2018-07-20 06:31:57';
        }
        $entry = [
            'id' => $id,
            'foreign_model' => ucfirst($model),
            'created' => $created
        ];
        $this->saveMockSyncIgnore($entry);
        return $entry;
    }

    protected function mockDirectoryEntryUser($fname, $lastname, $status, $dirCreated = null, $dirModified = null, $created = null, $modified = null)
    {
        if (!isset($dirCreated)) {
            $dirCreated = '2018-07-20 06:31:57';
        }
        if (!isset($dirModified)) {
            $dirModified = '2018-07-20 06:31:57';
        }
        if (!isset($created)) {
            $created = '2018-07-20 06:31:57';
        }
        if (!isset($modified)) {
            $modified = '2018-07-20 06:31:57';
        }
        $entry = [
            'id' => UuidFactory::uuid('ldap.user.id.' . $fname),
            'foreign_model' => 'Users',
            'foreign_key' => UuidFactory::uuid('user.id.' . $fname),
            'directory_name' => 'CN='. ucfirst($fname) . ' ' . ucfirst($lastname) . ',OU=PassboltUsers,DC=passbolt,DC=local',
            'directory_created' => $dirCreated,
            'directory_modified' => $dirModified,
            'status' => $status,
            'created' => $created,
            'modified' => $modified
        ];
        $this->saveMockDirectoryEntry($entry);
        return $entry;
    }

    private function saveMockDirectoryEntry($data)
    {
        $entry = $this->action->DirectoryEntries->newEntity($data, [
            'validate' => false,
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true,
                'foreign_key' => true,
                'directory_name' => true,
                'directory_created' => true,
                'directory_modified' => true,
                'status' => true,
                'created' => true,
                'modified' => true
            ]
        ]);
        $this->action->DirectoryEntries->save($entry, ['checkRules' => false]);
    }

    private function saveMockDirectoryUserData($user)
    {
        $users = $this->action->getDirectory()->getUsers();
        $users[] = $user;
        $this->action->getDirectory()->setUsers($users);
    }

    private function saveMockSyncIgnore($data)
    {
        $ignore = $this->action->DirectoryEntries->DirectoryIgnore->newEntity($data, ['validate' => false]);
        $this->action->DirectoryEntries->DirectoryIgnore->save($ignore, ['checkRules' => false]);
    }

    public function assertDirectoryEntryEmpty()
    {
        $de = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEmpty($de, __('Directory Entries should be empty, {0} found', count($de)));
    }

    public function assertDirectoryIgnoreEmpty()
    {
        $di = $this->action->DirectoryEntries->DirectoryIgnore->find()->all()->toArray();
        $this->assertEmpty($di, __('Directory ignore list should be empty, {0} found', count($di)));
    }

    public function assertUserExist($id, $where = null)
    {
        $where['id'] = $id;
        $Users = TableRegistry::getTableLocator()->get('Users');
        $this->assertEquals(count($Users->find()->where($where)->all()->toArray()), 1);
    }
}