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

use Cake\ORM\TableRegistry;

/**
 * Directory factory class
 * @package App\Utility
 */
class SyncAction
{
    protected $directory;
    public $summary;
    public $DirectoryEntries;

    const CREATE = 'create';
    const DELETE = 'delete';
    const UPDATE = 'update';
    const SUCCESS = 'success';
    const ERROR = 'error';
    const IGNORE = 'ignore';
    const GROUPS = 'Groups';
    const USERS = 'Users';
    const MEMBERS = 'GroupsUsers';

    /**
     * SyncAction constructor.
     * @throws \Exception if no directory configuration is present
     */
    public function __construct()
    {
        $this->directory = DirectoryFactory::get();
        $this->DirectoryEntries = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryEntries');
        $this->summary = [];
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
        $this->summary[] = $report;
    }

    /**
     * Get the summary of all action reports
     *
     * @return array
     */
    public function getSummary()
    {
        return $this->summary;
    }
}