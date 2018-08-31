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

use App\Model\Entity\Role;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Passbolt\DirectorySync\Model\Entity\DirectoryReport;

/**
 * Directory factory class
 * @package App\Utility
 */
class SyncAction
{
    /**
     * @var string DirectoryReport uuid
     */
    protected $parentId;

    /**
     * @var array|\Cake\Datasource\EntityInterface|mixed|null
     */
    protected $defaultAdmin;

    /**
     * @var \Passbolt\DirectorySync\Test\Utility\TestDirectory|LdapDirectory
     */
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
    public $Groups;

    /**
     * @var \Cake\ORM\Table
     */
    public $GroupsUsers;

    /**
     * @var \Cake\ORM\Table
     */
    public $DirectoryEntries;
    public $DirectoryReports;
    public $DirectoryReportsItems;
    public $DirectoryIgnore;
    public $DirectoryRelations;

    /**
     * @var bool
     */
    protected $dryRun = false;

    /**
     * @var ActionReportCollection
     */
    protected $summary;

    protected $report;

    /**
     * SyncAction constructor.
     * @param string $parentId parent id
     * @throws \Exception if no directory configuration is present
     */
    public function __construct($parentId = null)
    {
        $this->directory = DirectoryFactory::get();
        $this->DirectoryEntries = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryEntries');
        $this->DirectoryIgnore = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryIgnore');
        $this->DirectoryRelations = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryRelations');
        $this->DirectoryReports = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryReports');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->summary = new ActionReportCollection();
        $this->defaultAdmin = $this->getDefaultAdmin();
        if (empty($this->defaultAdmin)) {
            throw new \Exception('Configuration issue. A default admin user cannot be found.');
        }
        if (isset($parentId) && !Validation::uuid($parentId)) {
            throw new \Exception('The task parent Id is invalid, it should be a uuid.');
        }
        $this->parentId = $parentId;
    }

    /**
     * Execute sync.
     * - Delete all entities that can be deleted
     * - Create all entities that can be created
     * - Generate report
     *
     * @return \Passbolt\DirectorySync\Utility\ActionReportCollection
     */
    public function execute()
    {
        if ($this->isDryRun()) {
            $conn = $this->Groups->getConnection();
            $conn->begin();
            $conn->transactional(function () {
                $this->_execute();
            });
            $conn->rollback();
        } else {
            $this->_execute();
        }

        return $this->getSummary();
    }

    /**
     * @return void
     */
    public function beforeExecute()
    {
        $this->started = FrozenTime::now();
        $this->report = $this->DirectoryReports->create($this->parentId);
    }

    /**
     * @return void
     */
    public function afterExecute()
    {
        $this->ended = FrozenTime::now();
        $this->report->status = DirectoryReport::STATUS_DONE;
        $this->DirectoryReports->save($this->report);
    }

    /**
     * Get directory.
     * @return \Passbolt\DirectorySync\Utility\DirectoryInterface
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Report back on a sync action
     *
     * @param ActionReport $reportItem report item
     * @return void
     */
    public function addReportItem(ActionReport $reportItem)
    {
        $this->summary->add($reportItem);
        $this->DirectoryReports->DirectoryReportsItems->create($this->report->id, $reportItem);
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
    public function getDefaultAdmin()
    {
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

    /**
     * Set Dryrun
     * @param bool $dryRun dry run value
     * @return void
     */
    public function setDryRun(bool $dryRun)
    {
        $this->dryRun = $dryRun;
    }

    /**
     * Get dryRun
     * @return bool
     */
    public function isDryRun()
    {
        return $this->dryRun;
    }
}
