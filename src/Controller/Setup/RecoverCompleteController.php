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
namespace App\Controller\Setup;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Service\Setup\RecoverCompleteServiceInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;

class RecoverCompleteController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['complete']);

        return parent::beforeFilter($event);
    }

    /**
     * Recovery completion
     * Check if the public key matches the currently stored fingerprint
     * Unlike setup completion we do not update anything
     *
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user was deleted, is already active or does not exist
     * @throws \Cake\Http\Exception\BadRequestException if no authentication token was provided
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is expired or invalid
     * @throws \Cake\Http\Exception\BadRequestException if the OpenPGP key is not provided or not a valid OpenPGP key
     * @throws \Cake\Http\Exception\BadRequestException if the OpenPGP key does not belong to the user
     * @throws \Cake\Http\Exception\InternalErrorException if something went wrong when updating the data
     * @param \App\Service\Setup\RecoverCompleteServiceInterface $recoverCompleteService Setup complete service
     * @param string $userId uuid of the user
     * @return void
     */
    public function complete(RecoverCompleteServiceInterface $recoverCompleteService, string $userId)
    {
        $this->assertJson();

        // Do not allow logged in user to complete setup
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guests are allowed to complete setup.'));
        }

        $recoverCompleteService->complete($userId);

        $this->success(__('The recovery was completed successfully.'));
    }
}
