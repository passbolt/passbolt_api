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
use App\Service\Setup\SetupCompleteServiceInterface;
use Cake\Event\EventInterface;

class SetupCompleteController extends AppController
{
    public const COMPLETE_SUCCESS_EVENT_NAME = 'SetupCompleteController.complete.success';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['complete']);

        return parent::beforeFilter($event);
    }

    /**
     * Setup completion
     * Save the user gpg public key and set the account to active
     *
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user was deleted, is already active or does not exist
     * @throws \Cake\Http\Exception\BadRequestException if no authentication token was provided
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is expired or invalid
     * @throws \Cake\Http\Exception\BadRequestException if the OpenPGP key is not provided or not a valid OpenPGP key
     * @throws \Cake\Http\Exception\InternalErrorException if something went wrong when updating the data
     * @param \App\Service\Setup\SetupCompleteServiceInterface $setupCompleteService Setup complete service
     * @param string $userId uuid of the user
     * @return void
     */
    public function complete(SetupCompleteServiceInterface $setupCompleteService, string $userId): void
    {
        $setupCompleteService->complete($userId);

        $this->success(__('The setup was completed successfully.'));
    }
}
