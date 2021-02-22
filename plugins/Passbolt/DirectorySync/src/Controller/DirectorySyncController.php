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

namespace Passbolt\DirectorySync\Controller;

use App\Model\Entity\Role;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\DirectorySync\Actions\AllSyncAction;

class DirectorySyncController extends DirectoryController
{
    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event An Event instance
     * @throws \Cake\Http\Exception\ForbiddenException if the controller is accessed by a non admin
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->loadModel('Users');
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException('Only administrators can access directory sync functionalities');
        }

        return parent::beforeFilter($event);
    }

    /**
     * Synchronize entry point
     *
     * @return void
     */
    public function synchronize()
    {
        try {
            $res = $this->_synchronize(false);
        } catch (\Exception $e) {
            throw new InternalErrorException('The synchronization failed. ' . $e->getMessage());
        }
        $this->success(__('The synchronization was done successfully.'), $res);
    }

    /**
     * Synchronization with dry run entry point
     *
     * @return void
     */
    public function dryRun()
    {
        try {
            $res = $this->_synchronize(true);
        } catch (\Exception $e) {
            throw new InternalErrorException('The simulation failed. ' . $e->getMessage());
        }
        $this->success(__('The simulation was done successfully.'), $res);
    }

    /**
     * Main synchronization function
     *
     * @param bool $dryRun whether it should run in dry run mode.
     * @return array reports list in array format
     */
    protected function _synchronize(bool $dryRun = true)
    {
        $res = [];
        $allSyncAction = new AllSyncAction();
        $reports = $allSyncAction->execute($dryRun);
        foreach ($reports as $type => $report) {
            $res[$type] = $report->toFormattedArray();
        }

        return $res;
    }
}
