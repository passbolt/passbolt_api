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

namespace Passbolt\AccountRecovery\Service\AccountRecoveryRequests;

use App\Model\Entity\AuthenticationToken;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable $AccountRecoveryRequests
 */
class AccountRecoveryCreateRequestService
{
    use ModelAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $serverRequest;

    /**
     * @param \Cake\Http\ServerRequest $serverRequest Server request
     */
    public function __construct(ServerRequest $serverRequest)
    {
        $this->serverRequest = $serverRequest;
        $this->loadModel('AuthenticationTokens');
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryRequests');
    }

    /**
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest
     */
    public function create(): AccountRecoveryRequest
    {
        $userId = $this->serverRequest->getData('user_id');

        $tokenInPayload = $this->serverRequest->getData('authentication_token.token');
        $token = $this->getAndAssertToken($userId, AuthenticationToken::TYPE_RECOVER);
        if ($token->token !== $tokenInPayload) {
            $tokenId = null;
        } else {
            $tokenId = $token->id;
        }

        $request = $this->AccountRecoveryRequests->newEntity([
            'authentication_token_id' => $tokenId,
            'user_id' => $userId,
            'armored_key' => $this->serverRequest->getData('armored_key'),
            'fingerprint' => $this->serverRequest->getData('fingerprint'),
            'status' => AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_COMPLETED,
            'created_by' => $userId,
            'modified_by' => $userId,
        ]);

        try {
            $this->AccountRecoveryRequests->saveOrFail($request);
        } catch (PersistenceFailedException $e) {
            throw new BadRequestException($e->getMessage());
        }

        return $request;
    }

    /**
     * Return the authentication from data if any
     *
     * @param string $userId the user uuid the token belongs to
     * @param string $tokenType AuthenticationToken::TYPE_*
     * @throws \Cake\Http\Exception\BadRequestException if no authentication token was provided
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is expired or invalid
     * @return \App\Model\Entity\AuthenticationToken
     */
    protected function getAndAssertToken(string $userId, string $tokenType): AuthenticationToken
    {
        $data = $this->serverRequest->getData();
        if (!isset($data['authentication_token']) || !isset($data['authentication_token']['token'])) {
            throw new BadRequestException(__('An authentication token should be provided.'));
        }
        $token = $data['authentication_token']['token'];
        if (!Validation::uuid($token)) {
            throw new BadRequestException(__('The authentication token should be a valid UUID.'));
        }
        if (!$this->AuthenticationTokens->isValid($token, $userId, $tokenType)) {
            throw new BadRequestException(__('The authentication token is not valid or has expired.'));
        }

        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = $this->AuthenticationTokens->find()
            ->where(['token' => $token, 'active' => true, 'type' => $tokenType, 'user_id' => $userId])
            ->orderDesc('created')
            ->firstOrFail();

        // Deactivate all previous active tokens
        $this->AuthenticationTokens->query()
            ->update()
            ->set(['active' => false])
            ->where([
                'id <>' => $token->id,
                'active' => true,
                'type' => $tokenType,
                'user_id' => $userId,
            ])
            ->execute();

        return $token;
    }
}
