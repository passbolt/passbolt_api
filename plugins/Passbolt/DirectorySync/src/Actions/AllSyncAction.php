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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Actions;

use Cake\ORM\TableRegistry;

class AllSyncAction
{
    /**
     * Synchronize users.
     *
     * @return \Passbolt\DirectorySync\Actions\ActionReportCollection reports collection
     */
    public function syncUsers()
    {
        $userSyncAction = new UserSyncAction();
        $reports = $userSyncAction->execute();

        return $reports;
    }

    /**
     * Synchronize groups.
     *
     * @return \Passbolt\DirectorySync\Actions\ActionReportCollection reports collection
     */
    public function syncGroups()
    {
        $groupSyncAction = new GroupSyncAction();
        $reports = $groupSyncAction->execute();

        return $reports;
    }

    /**
     * Synchronize all (users and groups).
     *
     * @return array array reports collection for each item
     */
    public function syncAll()
    {
        $userReports = $this->syncUsers();
        $groupReports = $this->syncGroups();
        $res = [
            'users' => $userReports,
            'groups' => $groupReports,
        ];

        return $res;
    }

    /**
     * Execute.
     *
     * @param bool $dryRun whether to do it in dry run mode.
     * @return array reports collection.
     */
    public function execute(?bool $dryRun = false)
    {
        $Users = TableRegistry::getTableLocator()->get('Users');
        $reports = [];
        if ($dryRun) {
            $conn = $Users->getConnection();
            $conn->begin();
            $conn->transactional(function () use (&$reports) {
                $reports = $this->syncAll();
            });
            $conn->rollback();
        } else {
            $reports = $this->syncAll();
        }

        return $reports;
    }
}
