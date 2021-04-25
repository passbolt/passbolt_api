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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;
use Exception;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersViewController extends AppController
{
    /**
     * User View action
     *
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a uuid or 'me'
     * @throws \Cake\Http\Exception\NotFoundException if the user does not exist
     * @param string $id uuid|me
     * @return void
     */
    public function view($id)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            if ($id === 'me') {
                $id = $this->User->id(); // me returns the currently logged-in user
            } else {
                throw new BadRequestException(__('The user identifier should be a valid UUID or "me".'));
            }
        }

        // Retrieve the user
        $this->loadModel('Users');
        try {
            $user = $this->Users->findView($id, $this->User->role())->first();
        } catch (Exception $exception) {
            throw new NotFoundException(__('The user does not exist.'));
        }
        if (empty($user)) {
            throw new NotFoundException(__('The user does not exist.'));
        }
        $this->success(__('The operation was successful.'), $user);
    }
}
