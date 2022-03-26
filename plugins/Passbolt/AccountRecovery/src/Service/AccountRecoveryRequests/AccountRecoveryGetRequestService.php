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
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Controller\AccountRecoveryRequests\AccountRecoveryRequestsGetController;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UsersTable $Users
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable $AccountRecoveryRequests
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable $AccountRecoveryPrivateKeys
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryResponsesTable $AccountRecoveryResponses
 */
class AccountRecoveryGetRequestService
{
    use ModelAwareTrait;

    /**
     * @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest
     */
    protected $request;

    /**
     * @var \App\Model\Entity\User
     */
    protected $user;

    /**
     * @var \App\Model\Entity\AuthenticationToken
     */
    protected $token;

    /**
     * @var string
     */
    protected $clientIp;

    /**
     * @param array $params Query parameters
     * @param string $clientIp To report potential attacks
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    public function __construct(array $params, string $clientIp)
    {
        $this->loadModel('AuthenticationTokens');
        $this->loadModel('Users');
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryRequests');
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys');
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryResponses');
        $this->clientIp = $clientIp;
        $this->validateOrgPolicy();
        $this->setUser($params['userId'] ?? null);
        $this->setToken($params['tokenId'] ?? null);
        $this->setRequest($params['requestId'] ?? null);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $request = $this->request;

        $data = [
            'id' => $request->id,
            'user_id' => $request->user_id,
            'created' => $request->created,
            'created_by' => $request->created_by,
            'modified' => $request->modified,
            'modified_by' => $request->modified_by,
            'status' => $request->status,
        ];
        if ($request->isApproved()) {
            /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey $pKey */
            $pKey = $this->AccountRecoveryPrivateKeys->find()
                ->select('data')
                ->where(['user_id' => $this->user->id])
                ->firstOrFail();
            $data['account_recovery_private_key'] = [
                'data' => $pKey->data,
            ];

            /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $request */
            $responses = $this->AccountRecoveryResponses->find()
                ->select(['responder_foreign_model', 'data'])
                ->where(['account_recovery_requests_id' => $this->request->id]);
            $data['account_recovery_responses'] = $responses->toArray();
        }

        return $data;
    }

    /**
     * @param string|null $userId User ID
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    protected function setUser(?string $userId): void
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->find()
            ->contain('AccountRecoveryUserSettings')
            ->where([
                'Users.id' => $userId,
                'Users.deleted' => false, // forbid deleted users to start setup
                'Users.active' => true, // forbid users that have not completed the setup to recover
            ])
            ->firstOrFail();

        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting|null $setting */
        $setting = $user->get('account_recovery_user_setting');
        if (is_null($setting) || !$setting->isApproved()) {
            throw new BadRequestException(__('The user has not approved the account recovery feature.'));
        }

        $this->user = $user;
    }

    /**
     * @param string|null $tokenId Recover token ID
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    protected function setToken(?string $tokenId): void
    {
        if (!Validation::uuid($tokenId)) {
            throw new BadRequestException(__('The token identifier should be a valid UUID.'));
        }

        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = $this->AuthenticationTokens->find()
            ->where([
                'id' => $tokenId,
                'user_id' => $this->user->id,
                'active' => true,
                'type' => AuthenticationToken::TYPE_RECOVER,
            ])
            ->firstOrFail();

        if ($token->isExpired()) {
            throw new BadRequestException(__('Expired token provided.'));
        }

        $this->token = $token;
    }

    /**
     * @param string|null $requestId Request ID
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException
     */
    protected function setRequest(?string $requestId): void
    {
        if (!Validation::uuid($requestId)) {
            throw new BadRequestException(__('The request identifier should be a valid UUID.'));
        }

        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest|null $request */
        $request = $this->AccountRecoveryRequests->find()
            ->where([
                'id' => $requestId,
                'user_id' => $this->user->id,
                'authentication_token_id' => $this->token->id,
            ])
            ->first();

        if (is_null($request)) {
            EventManager::instance()->dispatch(new Event(
                AccountRecoveryRequestsGetController::ACCOUNT_RECOVERY_GET_BAD_REQUEST,
                $this,
                [
                    'userId' => $this->user->id,
                    'requestId' => $requestId,
                    'clientIp' => $this->clientIp,
                ]
            ));

            throw new BadRequestException(__('The request could not be found.'));
        } elseif ($request->isCompleted()) {
            throw new BadRequestException(__('The request was already completed.'));
        }

        $this->request = $request;
    }

    /**
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the feature is not enabled
     * @throws \Cake\Http\Exception\BadRequestException if the public key is empty
     */
    protected function validateOrgPolicy()
    {
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        if ($policy->isDisabled()) {
            throw new BadRequestException(__('Account recovery is disabled.'));
        } elseif (is_null($policy->account_recovery_organization_public_key)) {
            throw new BadRequestException(__('The account recovery organization public key is not set.'));
        }
    }
}
