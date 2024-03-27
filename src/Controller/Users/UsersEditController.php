<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Controller\Users;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
use App\Service\Resources\ResourcesExpireResourcesServiceInterface;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;
use Exception;
use League\Flysystem\FilesystemAdapter;

/**
 * UsersEditController Class
 */
class UsersEditController extends AppController
{
    protected UsersTable $Users;

    public const EVENT_USER_WAS_DISABLED = 'Controller.UsersEditController.userWasDisabled';
    public const EVENT_ADMIN_WAS_DISABLED = 'Controller.UsersEditController.adminWasDisabled';

    public const EVENT_USER_AFTER_UPDATE = 'Controller.UsersEditController.afterUpdate';

    /**
     * User edit action
     * Allow editing firstname / lastname and role only for admin
     *
     * @param string $id user uuid
     * @param \League\Flysystem\FilesystemAdapter $filesystemAdapter file system adapter to write the avatar in cache if saved
     * @param \App\Service\Resources\ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService Service to expire resources that were consumed by users who lost access to them.
     * @return void
     */
    public function editPost(
        string $id,
        FilesystemAdapter $filesystemAdapter,
        ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService
    ) {
        $this->assertJson();

        $data = $this->_validateRequestData($id);

        // Try to find the user and validate changes it
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        $this->Users = $usersTable;
        try {
            /** @var \App\Model\Entity\User $user */
            $user = $this->Users->findView($id, $this->User->role())->first();
        } catch (Exception $exception) {
            throw new BadRequestException(__('The user does not exist or has been deleted.'));
        }
        if (empty($user)) {
            throw new BadRequestException(__('The user does not exist or has been deleted.'));
        }
        $wasDisabledNull = is_null($user->disabled);

        // Patch
        $user = $this->Users->editEntity($user, $data, $this->User->getAccessControl());
        if ($user->getErrors()) {
            throw new ValidationException(__('Could not validate user data.'), $user, $this->Users);
        }
        $this->Users->checkRules($user);
        if (!empty($user->getErrors())) {
            throw new ValidationException(__('Could not validate user data.'), $user, $this->Users);
        }
        $isBeingDisabled = $wasDisabledNull && !is_null($user->disabled);

        // Used when sending after update event
        // We need entity's dirty state to know which column values has been changed.
        $userEntityWithDirtyState = clone $user;

        // Save
        $saveOptions = [
            'checkrules' => false,
            AvatarsTable::FILESYSTEM_ADAPTER_OPTION => $filesystemAdapter,
        ];
        if (!$this->Users->save($user, $saveOptions)) {
            throw new InternalErrorException('Could not save the user data. Please try again later.');
        }

        if ($isBeingDisabled) {
            /** @var \App\Model\Table\SecretsTable $secretsTable */
            $secretsTable = $this->fetchTable('Secrets');
            $secretToExpire = $secretsTable->findByUserId($id)
                ->select(['id', 'user_id', 'resource_id'])->all()->toArray();
            $resourcesExpireResourcesService->expireResourcesForSecrets($secretToExpire);
        }

        // Get the updated version (ex. Role needs to be fetched again if role_id changed)
        try {
            /** @var \App\Model\Entity\User $user */
            $user = $this->Users->findView($id, $this->User->role())->firstOrFail();
        } catch (Exception $exception) {
            $msg = __('Could not find the user data after save. Maybe it has been deleted in the meantime.');
            throw new InternalErrorException($msg, 500, $exception);
        }

        if ($isBeingDisabled) {
            $this->sendEmailOnUserDisable($user);
        }

        $this->sendAfterUpdateEvent($userEntityWithDirtyState);

        $this->success(__('The user has been updated successfully.'), $user);
    }

    /**
     * Assert request sanity and return the sanitized data
     *
     * @param string $id user uuid
     * @return array
     * @throws \Cake\Http\Exception\BadRequestException if gpgkey is sent (v2 only)
     * @throws \Cake\Http\Exception\BadRequestException if groups data is sent (v2 only)
     * @throws \Cake\Http\Exception\BadRequestException if role data is sent (v2 only)
     * @throws \Cake\Http\Exception\ForbiddenException if the user is not admin or not editing themselves
     * @throws \Cake\Http\Exception\BadRequestException if the user id is invalid, if data is not provided or invalid
     */
    protected function _validateRequestData(string $id): array
    {
        // Admin can edit all users, other users can only edit themselves
        if ($this->User->role() !== Role::ADMIN && $id !== $this->User->id()) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }

        // Baseline validation
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }
        $data = $this->request->getData();
        $data['id'] = $id;
        if (empty($data) || count($data) < 2) {
            throw new BadRequestException(__('Some user data should be provided.'));
        }

        if (isset($data['gpgkey'])) {
            throw new BadRequestException(__('Updating the OpenPGP key is not allowed.'));
        }
        if (isset($data['groups_user'])) {
            throw new BadRequestException(__('Updating the groups is not allowed.'));
        }
        if ($this->User->role() !== Role::ADMIN && (isset($data['role']) || isset($data['role_id']))) {
            throw new ForbiddenException(__('You are not authorized to edit the role.'));
        }

        // Sanitize data as the marshaller will throw a type error if the payload has integers as fields
        $sanitizedData = [];
        $allowedKeys = [
            'role_id',
            'disabled',
            'profile' => [
                'first_name',
                'last_name',
                'avatar',
            ],
        ];

        foreach ($allowedKeys as $allowedMainKey => $allowedKey) {
            if (!is_array($allowedKey)) {
                if (array_key_exists($allowedKey, $data)) {
                    $sanitizedData[$allowedKey] = $data[$allowedKey];
                }
            } else {
                foreach ($allowedKey as $allowedNestedKey) {
                    if (!array_key_exists($allowedMainKey, $data)) {
                        break;
                    }

                    if (array_key_exists($allowedNestedKey, $data[$allowedMainKey])) {
                        $sanitizedData[$allowedMainKey][$allowedNestedKey] = $data[$allowedMainKey][$allowedNestedKey];
                    }
                }
            }
        }

        return $sanitizedData;
    }

    /**
     * Sends an email to all admins when a user has been disabled
     * Sends an email to the user disabled if that user is an admin
     *
     * @param \App\Model\Entity\User $user User being edited
     * @return void
     */
    protected function sendEmailOnUserDisable(User $user): void
    {
        $operator = $this->User->getAccessControl();
        $emailData = compact('user', 'operator');
        $event = new Event(static::EVENT_USER_WAS_DISABLED, $this, $emailData);
        $this->getEventManager()->dispatch($event);

        if ($user->role->name === Role::ADMIN) {
            $event = new Event(static::EVENT_ADMIN_WAS_DISABLED, $this, $emailData);
            $this->getEventManager()->dispatch($event);
        }
    }

    /**
     * Dispatch a common after update event to hook into several functionality on top.
     *
     * @param \App\Model\Entity\User $user User entity object with dirty state(before save).
     * @return void
     */
    private function sendAfterUpdateEvent(User $user): void
    {
        $operator = $this->User->getExtendAccessControl();

        $emailData = [
            'operator' => $operator,
            'user' => $user,
        ];

        $event = new Event(static::EVENT_USER_AFTER_UPDATE, $this, $emailData);
        $this->getEventManager()->dispatch($event);
    }
}
