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
namespace Passbolt\DirectorySync\Utility;

use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use App\Model\Entity\Role;

/**
 * Directory factory class
 * @package App\Utility
 */
class SyncAction
{
    protected $started;
    protected $ended;

    protected $defaultAdmin;
    protected $directory;

    /**
     * @var array|mixed
     */
    public $directoryData;

    /**
     * @var \Cake\ORM\Table
     */
    public $Users;

    /**
     * @var \Cake\ORM\Table
     */
    public $DirectoryEntries;

    /**
     * @var array|mixed
     */
    public $entriesToIgnore;

    /**
     * @var \Cake\ORM\Table
     */
    public $DirectoryIgnore;
    public $DirectoryRelations;

    /**
     * @var ActionReportCollection
     */
    protected $summary;

    /**
     * SyncAction constructor.
     * @throws \Exception if no directory configuration is present
     */
    public function __construct()
    {
        $this->directory = DirectoryFactory::get();
        $this->DirectoryEntries = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryEntries');
        $this->DirectoryIgnore = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryIgnore');
        $this->DirectoryRelations = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryRelations');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->summary = new ActionReportCollection();
        $this->defaultAdmin = $this->getDefaultAdmin();
        if (empty($this->defaultAdmin)) {
            throw new \Exception('Configuration issue. A default admin user cannot be found.');
        }
    }

    /**
     * @return void
     */
    public function beforeExecute()
    {
        $this->started = FrozenTime::now();
    }

    /**
     * @return void
     */
    public function afterExecute()
    {
        $this->ended = FrozenTime::now();
    }

    /**
     * @return \Passbolt\DirectorySync\Utility\DirectoryInterface
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Report back on a sync action
     *
     * @param ActionReport $report
     */
    public function addReport(ActionReport $report)
    {
        $this->summary->add($report);
    }

    /**
     * Get the summary of all reports
     *
     * @return ActionReportCollection
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Get default admin.
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    public function getDefaultAdmin() {
        $defaultUser = Configure::read('passbolt.plugins.directorySync.defaultUser');
        if (!empty($defaultUser)) {
            // Get default user from database.
            $defaultUser = $this->Users->find()
                ->where([
                    'Users.deleted' => false,
                    'Users.active' => true,
                    'Users.username' => $defaultUser,
                    'Users.role_id' => $this->Users->Roles->getIdByName(Role::ADMIN),
                ])
                ->first();
            if (!empty($defaultUser)) {
                return $defaultUser;
            }
        }

        // If can't find corresponding config user, return first admin.
        return $this->Users->findFirstAdmin();
    }
}