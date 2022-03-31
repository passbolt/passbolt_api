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
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * Class UserGetActiveService
 *
 * @package App\Service\Users
 * @property \App\Model\Table\AuthenticationTokensTable $Users
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
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }

        try {
            $where = ['id' => $userId];
            /** @var \App\Model\Entity\User $userEntity */
            $userEntity = $this->Users->find()->where($where)->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The user does not exist.'));
        }
        if (!$userEntity->active || $userEntity->deleted) {
            throw new BadRequestException(__('The user is not active or has been deleted.'));
        }

        return $userEntity;
    }
}
