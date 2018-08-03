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
namespace Passbolt\DirectorySync\Actions\Traits;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Group;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\ErrorReport;

trait GroupSyncAddTrait {

    /**
     * Handle add when entry or group should be ignored.
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param Group|null $existingGroup
     */
    public function handleAddIgnore(array $data, DirectoryEntry $entry = null, Group $existingGroup = null) {
        if (isset($entry)) {
            if ($entry->status == self::SUCCESS && isset($existingGroup) && !$this->isGroupIgnored($existingGroup)) {
                $this->DirectoryEntries->updateStatusOrCreate($data, self::ERROR, self::GROUPS, null, $entry);
            }
            else {
                $this->DirectoryEntries->delete($entry);
                $this->DirectoryIgnore->create(['id' => $data['id'], 'foreign_model' => self::DIRECTORY_ENTRY]);
            }
        }
        if (isset($existingGroup) && !$this->isGroupIgnored($existingGroup)) {
            $this->DirectoryIgnore->create(['id' => $existingGroup->id, 'foreign_model' => self::GROUPS]);
        }

        // Conditions for reporting
        if (isset($existingGroup) && $existingGroup->deleted || isset($entry) && $entry->status == self::ERROR) {
            $this->addReport(new ActionReport(self::GROUPS, self::CREATE, self::IGNORE, isset($existingGroup) ? $existingGroup : $data));
        }
        elseif (!isset($existingGroup) && !isset($entry)) {
            $this->addReport(new ActionReport(self::GROUPS, self::CREATE, self::IGNORE, $data));
        }
        elseif (!isset($existingGroup) && isset($entry)) {
            $this->addReport(new ActionReport(self::GROUPS, self::CREATE, self::IGNORE, $entry));
        }
    }

    /**
     * Handle add in the context of existing group being deleted.
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param Group|null $existingGroup
     */
    public function handleAddDeleted(array $data, DirectoryEntry $entry  = null, Group $existingGroup = null) {
        // Check if ignored.
        if ($data['directory_created']->lt($existingGroup->modified)) {
            $this->handleAddIgnore($data, $entry, $existingGroup);
        } else {
            $this->handleAdd($data, $entry);
        }
    }

    /**
     * Handle add in a normal context.
     * @param array $data
     * @param DirectoryEntry|null $entry
     */
    public function handleAdd(array $data, DirectoryEntry $entry = null) {
        if ($this->isDirectoryEntryIgnored($data['id'])) {
            if (isset($entry)) { $this->DirectoryEntries->delete($entry); }
            $this->addReport(new ActionReport(self::GROUPS, self::CREATE, SELF::IGNORE, $data));
            return;
        }

        $g = $this->createGroup($data['group']);
        if ($g)  {
            $this->DirectoryEntries->updateStatusOrCreate($data, self::SUCCESS, self::GROUPS, $g, $entry);
        } else {
            $this->DirectoryEntries->updateStatusOrCreate($data, self::ERROR, self::GROUPS, null, $entry);
        }
    }

    /**
     * Create a group and log report.
     * @param $group
     *
     * @return bool
     */
    public function createGroup(array $group) {
        $group = $this->manageGroupUsers($group);
        try {
            $g = $this->Groups->create($group, new UserAccessControl(Role::ADMIN, $this->defaultAdmin->id));
            $this->addReport(new ActionReport(self::GROUPS, self::CREATE, self::SUCCESS, $g));
            return $g;
        } catch(ValidationException $exception) {
            $this->addReport(new ErrorReport(self::GROUPS, self::CREATE, $exception));
            return false;
        } catch (\Exception $exception) {
            $this->addReport(new ErrorReport(self::GROUPS, self::CREATE, $exception));
            return false;
        }
    }

    /**
     * Get default group administrator
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    public function getDefaultGroupAdmin() {
        $groupAdmin = Configure::read('passbolt.plugins.directorySync.defaultGroupAdminUser');
        if (!empty($groupAdmin)) {
            // Get groupAdmin from database.
            $groupAdmin =
                $this->Users->find()
                            ->where([
                                'Users.deleted' => false,
                                'Users.active' => true,
                                'Users.username' => $groupAdmin
                            ])
                            ->first();
            if (!empty($groupAdmin)) {
                return $groupAdmin;
            }
        }

        // If can't find corresponding config user, return first admin.
        return $this->Users->findFirstAdmin();
    }

    /**
     * Get default admin.
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    public function getDefaultAdmin() {
        $defaultUser = Configure::read('passbolt.plugins.directorySync.defaultUser');
        if (!empty($defaultUser)) {
            // Get default user from database.
            $defaultUser =
                $this->Users->find()
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

    // Dummy function for now. Will be improved later.
    public function manageGroupUsers(array $group) {
        $group['groups_users'][] = [
            'user_id' => $this->defaultGroupAdmin->id,
            'is_admin' => true,
        ];
        return $group;
    }
}