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
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * Class UserGetService
 *
 * @package App\Service\Users
 * @property \App\Model\Table\UsersTable $Users
 */
class UserGetService
{
    use ModelAwareTrait;

    /**
     * UserGetService constructor
     */
    public function __construct()
    {
        $this->loadModel('Users');
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
     * @throws \Cake\Http\Exception\BadRequestException if the is not active or deleted
     * @return \App\Model\Entity\User
     */
    public function getNotActiveNotDeletedOrFail(string $userId): User
    {
        $userEntity = $this->getOrFail($userId);
        if ($userEntity->active) {
            throw new BadRequestException(__('The user does not exist or is already active.'));
        }
        if ($userEntity->deleted) {
            throw new BadRequestException(__('The user does not exist or is already active.'));
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
        if (!$userEntity->active) {
            throw new BadRequestException(__('The user does not exist or is not active.'));
        }
        if ($userEntity->deleted) {
            throw new BadRequestException(__('The user does not exist or is not active.'));
        }

        return $userEntity;
    }
}
