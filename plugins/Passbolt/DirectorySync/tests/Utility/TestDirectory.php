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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\Utility;

use Cake\Core\Configure;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;
use Passbolt\DirectorySync\Utility\DirectoryInterface;

/**
 * IntegrationFixture
 */
class TestDirectory implements DirectoryInterface
{
    protected $users;
    protected $groups;
    protected $path;
    protected $directoryResults = null;

    /**
     * Constructor
     *
     * @throws \Exception if the scenario cannot be found
     * @return void
     */
    public function __construct()
    {
        $scenario = Configure::read('passbolt.plugins.directorySync.test');
        if (isset($scenario) && is_string($scenario)) {
            $this->path = dirname(__DIR__) . DS . 'IntegrationFixtures' . DS . stripslashes($scenario);
            if (!is_dir($this->path)) {
                throw new \Exception(__('The test scenario could not be found in fixtures at: {0}', $this->path));
            }
        } else {
            // the test should populate them with setters
            $this->users = [];
            $this->groups = [];
        }
        $this->directoryResults = new DirectoryResults([]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getGroups()
    {
        if (!isset($this->groups)) {
            $this->groups = $this->getGroupsFixtures();
        }

        $dr = $this->getFilteredDirectoryResults();

        return $dr->getGroupsAsArray();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getUsers()
    {
        $this->getFilteredDirectoryResults();
        if (!isset($this->users)) {
            $this->users = $this->getUsersFixtures();
        }

        $dr = $this->getFilteredDirectoryResults();

        return $dr->getUsersAsArray();
    }

    /**
     * Get directory results with filtered applied (as per filters defined in the config).
     * @return DirectoryResults directory results
     * @throws \Exception
     */
    public function getFilteredDirectoryResults()
    {
        if (!isset($this->users)) {
            $this->users = $this->getUsersFixtures();
        }
        if (!isset($this->groups)) {
            $this->groups = $this->getGroupsFixtures();
        }

        $this->directoryResults->initializeWithEntries($this->users, $this->groups);

        // TODO: actually do the filtering.

        return $this->directoryResults;
    }

    /**
     * @param $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
        $this->directoryResults->initializeWithEntries($this->users, []);
    }

    /**
     * @param $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
        $this->directoryResults->initializeWithEntries([], $groups);
    }

    /**
     * Get raw users fixtures.
     * @return mixed
     * @throws \Exception
     */
    public function getUsersFixtures() {
        return $this->_read('Users');
    }

    /**
     * Get raw groups fixtures.
     * @return mixed
     * @throws \Exception
     */
    public function getGroupsFixtures() {
        return $this->_read('Groups');
    }

    /**
     * Read a user or group file as php array
     *
     * @param $file
     * @return mixed
     * @throws \Exception
     */
    private function _read($file)
    {
        $path = $this->path . DS . $file . '.php';
        if (!is_file($path) || !is_readable($path)) {
            throw new \Exception(__('The {0} data file can not be found/read: {1}', $file, $path));
        }
        $return = include $path;
        if (!is_array($return)) {
            throw new \Exception(__('The {0} data should be an array: {1}', $file, $path));
        }

        return $return;
    }
}
