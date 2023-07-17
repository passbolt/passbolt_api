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
 * @since         4.0.0
 */
namespace Passbolt\DirectorySync\Actions\Traits;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Group;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Service\Groups\GroupsUpdateService;
use App\Utility\UserAccessControl;
use Passbolt\DirectorySync\Actions\Reports\ActionReport;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncError;

trait SyncUpdateTrait
{
    /**
     * Handle update group.
     *
     * @param array $data data
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry|null $entry entry
     * @param \App\Model\Entity\Group $existingGroup Group
     * @return void
     */
    public function handleUpdateGroup(array $data, ?DirectoryEntry $entry, Group $existingGroup): void
    {
        $groupName = $this->getNameFromData($data);
        if ($groupName === 'undefined' || strtolower($groupName) === strtolower($existingGroup->name)) {
            return;
        }
        $this->updateGroup($existingGroup, $data);
    }

    /**
     * Update group
     *
     * @param \App\Model\Entity\Group $existingGroup Group
     * @param array $data data
     * @return void
     */
    public function updateGroup(Group $existingGroup, array $data): void
    {
        $uac = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->get('id'));
        $groupsUpdateService = new GroupsUpdateService();
        $groupName = $this->getNameFromData($data);
        $changes = [
            'name' => $groupName,
        ];
        try {
            $entity = $groupsUpdateService->update($uac, $existingGroup->id, $changes);
            // Send report.
            $this->addReportItem(new ActionReport(
                __('The group {0} has been successfully renamed to {1}.', $existingGroup->name, $groupName),
                Alias::MODEL_GROUPS,
                Alias::ACTION_UPDATE,
                Alias::STATUS_SUCCESS,
                $entity
            ));
        } catch (\Exception $exception) {
            $error = new SyncError($existingGroup, $exception);
            $this->addReportItem(new ActionReport(
                __('The group {0} could not be renamed to {1}.', $existingGroup->name, $groupName),
                Alias::MODEL_GROUPS,
                Alias::ACTION_UPDATE,
                Alias::STATUS_ERROR,
                $error
            ));
        }
    }

    /**
     * Handle update user.
     *
     * @param array $data data
     * @param \Passbolt\DirectorySync\Model\Entity\DirectoryEntry|null $entry entry
     * @param \App\Model\Entity\User $existingUser User
     * @return void
     */
    public function handleUpdateUser(array $data, ?DirectoryEntry $entry, User $existingUser): void
    {
        $existingUser = $this->Users->get($existingUser->id, ['contain' => ['Profiles']]);
        $firstName = $data['user']['profile']['first_name'] ?? null;
        $lastName = $data['user']['profile']['last_name'] ?? null;
        if (
            !$firstName || !$lastName ||
            (strtolower($firstName) === strtolower($existingUser->profile->first_name) &&
                strtolower($lastName) === strtolower($existingUser->profile->last_name))
        ) {
            return;
        }
        //Extracting only first and last name to avoid modifying other fields
        $updatedData = [
            'profile' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ],
        ];
        $this->updateUser($existingUser, $updatedData);
    }

    /**
     * Update user
     *
     * @param \App\Model\Entity\User $existingUser User
     * @param array $data data
     * @return void
     */
    public function updateUser(User $existingUser, array $data): void
    {
        try {
            $user = $this->Users->editEntity($existingUser, $data, Role::ADMIN);
            $result = $this->Users->save($user, ['checkrules' => false]);

            if (!$result) {
                if ($user->hasErrors()) {
                    $msg = __('Could not validate user data.');
                    throw new ValidationException($msg, $user, $this->Users);
                }
                throw new \Exception('User could not be updated.');
            }
            // Send report.
            $this->addReportItem(new ActionReport(
                __(
                    'The user {0} full name has been successfully updated to {1} {2}.',
                    $existingUser->username,
                    $user->profile->first_name,
                    $user->profile->last_name
                ),
                Alias::MODEL_USERS,
                Alias::ACTION_UPDATE,
                Alias::STATUS_SUCCESS,
                $user
            ));
        } catch (\Exception $exception) {
            $error = new SyncError($existingUser, $exception);
            $this->addReportItem(new ActionReport(
                __(
                    'The user {0} full name could not be updated to {1} {2}.',
                    $existingUser->username,
                    $data['profile']['first_name'],
                    $data['profile']['last_name']
                ),
                Alias::MODEL_USERS,
                Alias::ACTION_UPDATE,
                Alias::STATUS_ERROR,
                $error
            ));
        }
    }
}
