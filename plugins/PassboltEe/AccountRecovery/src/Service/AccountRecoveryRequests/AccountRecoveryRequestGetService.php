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
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use App\Service\Users\UserGetService;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;
use Passbolt\AccountRecovery\Service\AccountRecoveryUserSettings\AccountRecoveryUserSettingsGetService;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UsersTable $Users
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable $AccountRecoveryRequests
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable $AccountRecoveryPrivateKeys
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryResponsesTable $AccountRecoveryResponses
 */
class AccountRecoveryRequestGetService
{
    use LocatorAwareTrait;

    public const ACCOUNT_RECOVERY_REQUEST_GET_BAD_REQUEST = 'Service.AccountRecoveryRequestGetService.notFound';

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable
     */
    protected $AccountRecoveryRequests;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable
     */
    protected $AccountRecoveryPrivateKeys;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryResponsesTable
     */
    protected $AccountRecoveryResponses;

    /**
     * AccountRecoveryRequestGetService constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->AuthenticationTokens = $this->fetchTable('Users');
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryRequests = $this->fetchTable('Passbolt/AccountRecovery.AccountRecoveryRequests');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryPrivateKeys = $this->fetchTable('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryResponses = $this->fetchTable('Passbolt/AccountRecovery.AccountRecoveryResponses');
    }

    /**
     * @param string $userId uuid
     * @param string $token uuid
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest entity
     * @throws \Cake\Http\Exception\BadRequestException if policy is disabled, if token id or request is invalid
     * @throws \Cake\Http\Exception\NotFoundException if user or request or token could not be found
     */
    public function getOrFail(string $userId, string $token): AccountRecoveryRequest
    {
        // Assert policy is not set to disabled
        (new AccountRecoveryOrganizationPolicyGetService())->getOrFail();

        // Assert user exist, is active and not deleted
        $userEntity = (new UserGetService())->getActiveNotDeletedOrFail($userId);

        // Assert token exist and is valid and belong to the user and is of the right type
        $tokenEntity = (new AuthenticationTokenGetService())
            ->getActiveNotExpiredOrFail($token, $userId, AuthenticationToken::TYPE_RECOVER);

        // Assert user is enrolled in the program
        (new AccountRecoveryUserSettingsGetService())->getOrFail($userId);

        // Assert request entity exist and belong to the user
        try {
            $where = [
                'user_id' => $userEntity->id,
                'authentication_token_id' => $tokenEntity->id,
            ];
            /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $requestEntity */
            $requestEntity = $this->AccountRecoveryRequests->find()->where($where)->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The account recovery request could not be found.'));
        }

        return $requestEntity;
    }

    /**
     * @param string $requestId uuid
     * @param string $userId uuid
     * @param string $token uuid
     * @param string|null $clientIp uuid
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest
     * @throws \Cake\Http\Exception\BadRequestException if policy is disabled, if token or request is invalid, request already ompleted etc.
     * @throws \Cake\Http\Exception\NotFoundException if user or request or token could not be found
     */
    public function getNotCompletedOrFail(
        string $requestId,
        string $userId,
        string $token,
        ?string $clientIp = null
    ): AccountRecoveryRequest {
        // Assert policy is not set to disabled
        (new AccountRecoveryOrganizationPolicyGetService())->getOrFail();

        // Assert token exist and is valid and belong to the user and is of the right type
        $tokenService = new AuthenticationTokenGetService();
        $tokenEntity = $tokenService->getActiveOrFail($token, $userId, AuthenticationToken::TYPE_RECOVER);

        // Assert user exist, is active and not deleted
        $userEntity = (new UserGetService())->getActiveNotDeletedOrFail($userId);

        // Assert user is enrolled in the program
        (new AccountRecoveryUserSettingsGetService())->getOrFail($userId);

        // Assert request entity exist and belong to the user
        if (!Validation::uuid($requestId)) {
            throw new BadRequestException(__('The request id is invalid.'));
        }
        try {
            $where = [
                'id' => $requestId,
                'user_id' => $userEntity->id,
                'authentication_token_id' => $tokenEntity->id,
            ];
            /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $requestEntity */
            $requestEntity = $this->AccountRecoveryRequests->find()->where($where)->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            $this->onRequestDoesNotExist($requestId, $userId, $clientIp ?? '0.0.0.0');
            throw new NotFoundException(__('The account recovery request could not be found.'));
        }

        // Assert request is not already completed
        if ($requestEntity->isCompleted()) {
            throw new BadRequestException(__('The request is already completed.'));
        }
        // Assert token is not expired. If so, deactivate the token, reject the request and throw an exception
        if ($tokenEntity->isExpired()) {
            $requestEntity->set('status', AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED);
            $requestEntity->setAccess('status', true);
            $this->AccountRecoveryRequests->saveOrFail($requestEntity);
            $tokenService->getActiveNotExpiredOrFail($token, $userId, AuthenticationToken::TYPE_RECOVER);
        }

        return $requestEntity;
    }

    /**
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $requestEntity entity
     * @return array
     */
    public function decorateResults(AccountRecoveryRequest $requestEntity): array
    {
        $data = [
            'id' => $requestEntity->id,
            'fingerprint' => $requestEntity->fingerprint,
            'user_id' => $requestEntity->user_id,
            'created' => $requestEntity->created,
            'created_by' => $requestEntity->created_by,
            'modified' => $requestEntity->modified,
            'modified_by' => $requestEntity->modified_by,
            'status' => $requestEntity->status,
            // Not needed
            //'armored_key' => $requestEntity->armored_key,
        ];

        if ($requestEntity->isApproved()) {
            $errorMsg = 'Invalid record set. Responses should be set for approved requests.';

            // There should be private key available
            try {
                /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey $privateKeyEntity */
                $privateKeyEntity = $this->AccountRecoveryPrivateKeys->find()
                    ->select('data')
                    ->where(['user_id' => $requestEntity->user_id])
                    ->firstOrFail();
            } catch (RecordNotFoundException $exception) {
                throw new InternalErrorException($errorMsg, 500, $exception);
            }
            $data['account_recovery_private_key']['data'] = $privateKeyEntity->data;

            // There should be at least one response
            $responses = $this->AccountRecoveryResponses->find()
                ->select([
                    'account_recovery_request_id',
                    'status',
                    'responder_foreign_model',
                    'responder_foreign_key',
                    'data',
                ])
                ->where(['account_recovery_request_id' => $requestEntity->id])
                ->all()
                ->toArray();
            if (empty($responses)) {
                throw new InternalErrorException($errorMsg);
            }

            $data['account_recovery_responses'] = $responses;
        }

        return $data;
    }

    /**
     * Trigger an event when the request does not exist
     *
     * @param string $requestId uuid
     * @param string $userId uuuid
     * @param string $clientIp client ip
     * @return void
     */
    protected function onRequestDoesNotExist(string $requestId, string $userId, string $clientIp): void
    {
        EventManager::instance()->dispatch(new Event(
            self::ACCOUNT_RECOVERY_REQUEST_GET_BAD_REQUEST,
            $this,
            [
                'userId' => $userId,
                'requestId' => $requestId,
                'clientIp' => $clientIp,
            ]
        ));
    }
}
