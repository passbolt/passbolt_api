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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.1.0
 */
namespace Passbolt\Mobile\Controller\Transfers;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Validation\Validation;
use Passbolt\Mobile\Service\Transfers\TransfersUpdateService;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \Passbolt\Mobile\Model\Table\TransfersTable $Transfers
 */
class TransfersUpdateController extends AppController
{
    /**
     * @var \Passbolt\Mobile\Model\Entity\Transfer $transfer
     */
    protected $transfer;

    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('update');
        $this->loadModel('AuthenticationTokens');
        $this->loadModel('Passbolt/Mobile.Transfers');

        return parent::beforeFilter($event);
    }

    /**
     * Update a transfer without sessions
     *
     * Allow a user on a non configured device to perform update without being logged in
     * using an authentication token provided via another channel, a QR code for example
     *
     * @param string $id uuid
     * @param string $authToken uuid
     * @return void
     */
    public function updateNoSession(string $id, string $authToken): void
    {
        $this->main($id, $authToken);
    }

    /**
     * Update a transfer
     *
     * @param string $id transfer uuid
     * @return void
     */
    public function update(string $id): void
    {
        $this->main($id);
    }

    /**
     * Main update controller method
     *
     * @param string $id transfer uuid
     * @param string|null $authToken token
     * @throws \Cake\Http\Exception\BadRequestException if data is missing or transfer id is not valid
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if transfer does not exist
     * @throws \Cake\Http\Exception\UnauthorizedException if transfer auth token is expired
     * @throws \App\Error\Exception\ValidationException if data do not validate
     * @throws \Cake\Http\Exception\InternalErrorException if saving data is not possible
     * @return void
     */
    protected function main(string $id, ?string $authToken = null): void
    {
        $this->assertRequestData($id);
        if (isset($authToken)) {
            $this->assertAuthToken($authToken);
        } else {
            $this->assertCorrectUser();
        }

        $updateService = new TransfersUpdateService($this->Transfers);
        $updatedTransfer = $updateService->update($this->transfer, $this->request->getData());
        $this->success(__('The operation was successful.', $updatedTransfer));
    }

    /**
     * Check request sanity and set $transfer
     *
     * @param string $id uuid
     * @throws \Cake\Http\Exception\BadRequestException if transfer id is invalid or data is not set
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if transfer does not exist
     * @throws \Cake\Http\Exception\UnauthorizedException if transfer auth token is expired
     * @return void
     */
    protected function assertRequestData(string $id): void
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The transfer id is not valid.'));
        }
        $data = $this->request->getData();
        if (!isset($data) || empty($data) || !is_array($data)) {
            throw new BadRequestException(__('Information about the transfer is required.'));
        }

        // Fetch the transfer
        $this->transfer = $this->Transfers->get($id, ['contain' => ['AuthenticationTokens']]);
        if (
            !isset($this->transfer->authentication_token)
            || $this->AuthenticationTokens->isExpired($this->transfer->authentication_token)
        ) {
            throw new UnauthorizedException(__('The transfer window is closed.'));
        }
    }

    /**
     * Assert auth token
     *
     * @param string $authToken uuid
     * @throws \Cake\Http\Exception\BadRequestException if no authentication token is expired or invalid
     * @return void
     */
    protected function assertAuthToken(string $authToken): void
    {
        if (!Validation::uuid($authToken)) {
            throw new BadRequestException(__('The authentication token should be a valid uuid.'));
        }
        if ($this->transfer->authentication_token->token !== $authToken) {
            throw new BadRequestException(__('The authentication token is invalid.'));
        }
    }

    /**
     * Assert the logged in user is the one defined in the transfer and auth token
     *
     * @return void
     */
    protected function assertCorrectUser(): void
    {
        $userId = $this->User->id();
        if ($this->transfer->authentication_token->user_id !== $userId) {
            throw new UnauthorizedException(__('The authentication token is invalid.'));
        }
        if ($this->transfer->user_id !== $userId) {
            throw new UnauthorizedException(__('The authentication token is invalid.'));
        }
    }
}
