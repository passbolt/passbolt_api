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
 * @since         3.6.0
 */

namespace App\Service\Users;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;

/**
 * Class UserGetService
 *
 * @package App\Service\Users
 */
class UserGetService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * UserGetService constructor
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * @param string $userId uuid
     * @return \App\Model\Entity\User
     */
    protected function getOrFail(string $userId): User
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }

        try {
            $where = [$this->Users->aliasField('id') => $userId];
            $contain = ['Roles', 'Profiles' => AvatarsTable::addContainAvatar()];
            /** @var \App\Model\Entity\User $userEntity */
            $userEntity = $this->Users->find('locale')->where($where)->contain($contain)->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The user does not exist.'));
        }

        if ($userEntity->role->isGuest()) {
            throw new BadRequestException(__('The user should not be a guest.'));
        }

        return $userEntity;
    }

    /**
     * Get a user by ID or throw relevant HTTP exceptions
     *
     * @param string $userId user id uuid
     * @throws \Cake\Http\Exception\NotFoundException if the user could not be found
     * @throws \Cake\Http\Exception\BadRequestException if the userId is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the is active or deleted or disabled
     * @return \App\Model\Entity\User
     */
    public function getNotActiveNotDeletedNotDisabledOrFail(string $userId): User
    {
        $userEntity = $this->getOrFail($userId);
        if ($userEntity->isActive()) {
            throw new BadRequestException(__('The user does not exist or is already active or is disabled.'));
        }
        if ($userEntity->isDeleted()) {
            throw new BadRequestException(__('The user does not exist or is already active or is disabled.'));
        }
        if ($userEntity->isDisabled()) {
            throw new BadRequestException(__('The user does not exist or is already active or is disabled.'));
        }

        return $userEntity;
    }

    /**
     * Get a user by ID or throw relevant HTTP exceptions
     *
     * @param string $userId user id uuid
     * @throws \Cake\Http\Exception\NotFoundException if the user could not be found
     * @throws \Cake\Http\Exception\BadRequestException if the userId is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the is not active or deleted or disabled
     * @return \App\Model\Entity\User
     */
    public function getActiveNotDeletedNotDisabledOrFail(string $userId): User
    {
        $userEntity = $this->getActiveNotDeletedOrFail($userId);

        if ($userEntity->isDisabled()) {
            throw new BadRequestException(__('The user does not exist or is not active or is disabled.'));
        }

        return $userEntity;
    }

    /**
     * Get a user by ID or throw relevant HTTP exceptions
     *
     * @param string $userId user id uuid
     * @throws \Cake\Http\Exception\NotFoundException if the user could not be found
     * @throws \Cake\Http\Exception\BadRequestException if the userId is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the is not active or deleted
     * @return \App\Model\Entity\User
     */
    public function getActiveNotDeletedOrFail(string $userId): User
    {
        $userEntity = $this->getOrFail($userId);
        $msg = __('The user does not exist or is not active or is disabled.');

        // Keep cases separate for debug trace purposes
        if (!$userEntity->isActive()) {
            throw new BadRequestException($msg);
        }
        if ($userEntity->isDeleted()) {
            throw new BadRequestException($msg);
        }

        return $userEntity;
    }
}
