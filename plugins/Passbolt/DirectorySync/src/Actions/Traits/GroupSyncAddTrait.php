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
use Cake\Network\Exception\InternalErrorException;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\ActionReport;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncError;

trait GroupSyncAddTrait {

    /**
     * Handle add when entry or group should be ignored.
     *
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param Group|null $existingGroup
     */
    public function handleAddIgnore(array $data, DirectoryEntry $entry = null, Group $existingGroup = null, bool $ignoreGroup) {
        // do not overly report ignored record when there is nothing to do
        if ((isset($existingGroup) && isset($entry->group) && !$existingGroup->deleted)) {
            return;
        }
        if ($ignoreGroup) {
            $msg = __('The group {0} was not synced because the passbolt group is marked to as be ignored.',
                $existingGroup->name);
            $reportData = $this->DirectoryIgnore->get($existingGroup->id);
        } else {
            $msg = __('The group {0} was not synced because the directory group is marked to as be ignored.',
                $data['group']['name']);
            $reportData = $this->DirectoryIgnore->get($entry->id);
        }
        $this->addReportItem(new ActionReport($msg,Alias::MODEL_GROUPS, Alias::ACTION_CREATE, Alias::STATUS_IGNORE, $reportData));
    }

    /**
     * Handle add in a normal context.
     * @param array $data
     * @param DirectoryEntry|null $entry
     */
    public function handleAddNew(array $data, DirectoryEntry $entry = null)
    {
        $status = Alias::STATUS_ERROR;
        $reportData = null;
        try {
            $accessControl = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->id);
            $group = $this->beforeCreateGroup($data['group']);
            $reportData = $group = $this->Groups->create($group, $accessControl);
            $this->DirectoryEntries->updateForeignKey($entry, $group->id);
            $this->handleGroupUsersAfterGroupCreate($data, $group);
            $status = Alias::STATUS_SUCCESS;
            $msg = __('The group {0} was successfully added to passbolt.', $data['group']['name']);
        } catch(ValidationException $exception) {
            $reportData = new SyncError($entry, $exception);
            $status = Alias::STATUS_ERROR;
            $name = isset($data['group']['name']) ? $data['group']['name'] : 'undefined';
            $msg = __('The group {0} could not be added because of data validation issues.', $name);
        } catch (InternalErrorException $exception) {
            $reportData = new SyncError($entry, $exception);
            $msg = __('The group {0} could not be added because of an internal error. Please try again later.',
                $data['group']['name']);
        }
        $this->addReportItem(new ActionReport($msg, Alias::MODEL_GROUPS, Alias::ACTION_CREATE, $status, $reportData));
    }

    /**
     * Handle add in the context of existing group being deleted.
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param Group|null $existingGroup
     */
    public function handleAddDeleted(array $data, DirectoryEntry $entry  = null, Group $existingGroup = null) {
        // if the group was created in ldap and then deleted in passbolt
        // do not try to recreate
        $status = Alias::STATUS_ERROR;
        $reportData = null;
        if ($data['directory_created']->lt($existingGroup->modified)) {
            $reportData = new SyncError($entry, null);
            $msg = __('The previously deleted group {0} was not re-added to passbolt.', $existingGroup->name);
        } else {
            // if the group was delete in passbolt and then created in ldap
            // try to recreate
            try {
                $accessControl = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->id);
                $group = $this->beforeCreateGroup($data['group']);
                $reportData = $group = $this->Groups->create($group, $accessControl);
                $this->DirectoryEntries->updateForeignKey($entry, $group->id);
                $this->handleGroupUsersAfterGroupCreate($data, $group);
                $status = Alias::STATUS_SUCCESS;
                $msg = __('The previously deleted group {0} was re-added to passbolt.', $existingGroup->name);
            } catch(ValidationException $exception) {
                $reportData = new SyncError($entry, $exception);
                $msg = __('The deleted group {0} could not be re-added to passbolt because of validation errors.', $existingGroup->name);
            } catch (InternalErrorException $exception) {
                $reportData = new SyncError($entry, $exception);
                $msg = __('The deleted group {0} could not be re-added to passbolt because of an internal error.', $existingGroup->name);
            }
        }
        $this->addReportItem(new ActionReport($msg, Alias::MODEL_GROUPS, Alias::ACTION_CREATE, $status, $reportData));
    }

    /**
     * @param array $data
     * @param DirectoryEntry|null $entry
     * @param Group $existingGroup
     */
    function handleAddExist(array $data, DirectoryEntry $entry = null, Group $existingGroup)
    {
        // do not overly report already successfully synced groups
        if (isset($entry) && !isset($entry->foreign_key)) {
            $this->DirectoryEntries->updateForeignKey($entry, $existingGroup->id);
            $this->addReportItem(new ActionReport(
                __('The group {0} was mapped with an existing group in passbolt.', $existingGroup->name),
                Alias::MODEL_GROUPS, Alias::ACTION_CREATE, Alias::STATUS_SYNC, $existingGroup));
        }
        $this->handleGroupUsersEdit($data, $entry, $existingGroup);
    }

    /**
     * Before creating a group add admin as default group manager
     *
     * @param array $group
     * @return array
     */
    public function beforeCreateGroup(array $group) {
        //
        $group['groups_users'][] = [
            'user_id' => $this->defaultGroupAdmin->id,
            'is_admin' => true,
        ];

        return $group;
    }
}