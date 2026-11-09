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

use App\Model\Entity\Role;
use App\Model\Table\UsersTable;
use App\Service\RecordsStrategy\UsersAddRecordsStrategyInterface;
use Cake\Http\Exception\ForbiddenException;

/**
 * UsersAddController Class
 */
class UsersAddController extends UsersRegisterController
{
    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * User add action (admin only)
     *
     * @param \App\Service\RecordsStrategy\UsersAddRecordsStrategyInterface $recordsStrategy Decides if the user is allowed to perform the operation
     * @throws \Cake\Http\Exception\HttpException if the user is not allowed to add users
     * @throws \App\Error\Exception\ValidationException if user data does not validate
     * @throws \Exception
     * @return void
     */
    public function addPost(UsersAddRecordsStrategyInterface $recordsStrategy)
    {
        $this->assertJson();

        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('Only administrators can add new users.'));
        }
        $recordsStrategy->assertAllowed($this->User->getAccessControl());
        $data = $this->request->getData();
        $user = $this->Users->register($data, $this->User->getAccessControl());
        $user = $this->Users->findView($user->id, Role::ADMIN)->first();
        $msg = __('The user was successfully added. This user now need to complete the setup.');
        $this->success($msg, $user);
    }
}
