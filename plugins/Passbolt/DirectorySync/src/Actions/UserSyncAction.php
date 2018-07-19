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
namespace Passbolt\DirectorySync\Actions;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;

class UserSyncAction extends SyncAction
{
    /**
     * @var \Cake\ORM\Table
     */
    public $Users;

    /**
     * UserSyncAction constructor.
     *
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->DirectoryEntries = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryEntries');
    }

    /**
     *
     */
    public function execute() {
        $directoryEntries = $this->directory->getUsers();

        if (!isset($directoryEntries)) {
            // Directory is empty nothing to do
            return;
        }

        // Find all the entities
        $syncedEntries = $this->DirectoryEntries->find()
            ->select()
            ->where(['DirectoryEntries.id IN' => Hash::extract($directoryEntries, '{n}.id')])
            ->contain('Users')
            ->all()
            ->toArray();
        $ignoredEntries = Hash::extract($syncedEntries, '{n}[status='. DirectoryEntry::STATUS_IGNORE.'].id');

        foreach($directoryEntries as $i => $data) {
            if (in_array($data['id'], $ignoredEntries)) {
                // entry is marked as to be ignored
                continue;
            }
            if (in_array($data['id'], $syncedEntries)) {
                // entry is already synced
                // TODO edit user
            }

            try {
                $u = $this->Users->register($data['user'], new UserAccessControl(Role::ADMIN));
                $result['success'] = $u;
            } catch(ValidationException $exception) {
                $error = $exception->getErrors();
                if (isset($error['username']['uniqueUsername'])) {
                    // The user is already there
                } else {
                    // The user is does not validate
                }
            } catch (\Exception $exception) {
                pr($exception->getMessage());
            }
        }
    }
}