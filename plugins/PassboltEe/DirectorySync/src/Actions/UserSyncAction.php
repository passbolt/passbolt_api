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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Actions\Reports\ActionReport;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncError;

class UserSyncAction extends SyncAction
{
    /**
     * @inheritDoc
     */
    protected function getEntityType(): string
    {
        return Alias::MODEL_USERS;
    }

    /**
     * @inheritDoc
     */
    protected function getEntityName(Entity $entity): string
    {
        return $entity->get('username');
    }

    /**
     * @inheritDoc
     */
    protected function getNameFromData(array $data): string
    {
        return $data['user']['username'] ?? 'undefined';
    }

    /**
     * @inheritDoc
     */
    protected function getEntityFromData(array $data): ?Entity
    {
        return $this->getUserFromData($data['user']['username'] ?? '');
    }

    /**
     * Get user from data.
     *
     * @param string $username username
     * @return ?\App\Model\Entity\User
     */
    private function getUserFromData(string $username): ?User
    {
        /** @var \App\Model\Entity\User|null $existingUser */
        $existingUser = $this->Users
            ->findByUsernameCaseAware($username)
            ->select(['id', 'username', 'active', 'deleted', 'created', 'modified'])
            ->order(['Users.modified' => 'DESC'])
            ->first();

        return $existingUser;
    }

    /**
     * @inheritDoc
     */
    protected function createEntity(array $data, DirectoryEntry $entry): Entity
    {
        $accessControl = new UserAccessControl(Role::ADMIN, $this->defaultAdmin->get('id'));
        $entity = $this->Users->register($data['user'], $accessControl);
        $this->DirectoryEntries->updateForeignKey($entry, $entity->id);

        return $entity;
    }

    /**
     * @param array $data data
     * @param \App\Model\Entity\User $existingEntity existing entity
     * @return void
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    protected function handleUpdate(array $data, Entity $existingEntity): void
    {
        /** @var \App\Model\Entity\User $existingUser */
        $existingUser = $this->Users->get($existingEntity->id, ['contain' => ['Profiles']]);
        $firstName = $data['user']['profile']['first_name'] ?? null;
        $lastName = $data['user']['profile']['last_name'] ?? null;
        if (
            !$firstName || !$lastName ||
            (mb_strtolower($firstName) === mb_strtolower($existingUser->profile->first_name) &&
                mb_strtolower($lastName) === mb_strtolower($existingUser->profile->last_name))
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
    private function updateUser(User $existingUser, array $data): void
    {
        try {
            $user = $this->Users->editEntity($existingUser, $data, new UserAccessControl(Role::ADMIN));
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

    /**
     * @inheritDoc
     */
    protected function getTable(): Table
    {
        return TableRegistry::getTableLocator()->get('Users');
    }
}
