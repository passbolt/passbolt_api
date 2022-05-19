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

namespace Passbolt\AccountRecovery\Service\AccountRecoveryResponses;

use App\Error\Exception\CustomValidationException;
use App\Service\OpenPGP\MessageRecipientValidationService;
use App\Service\OpenPGP\MessageValidationService;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;

/**
 * Class AccountRecoveryResponsesCreateService
 *
 * @package Passbolt\AccountRecovery\Service\AccountRecoveryResponses
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryResponsesTable $AccountRecoveryResponses
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable $AccountRecoveryRequests
 */
class AccountRecoveryResponsesCreateService
{
    use ModelAwareTrait;

    public const RESPONSE_APPROVED_EVENT_NAME = 'Service.AccountRecoveryResponsesCreate.afterApproved';
    public const RESPONSE_REJECTED_EVENT_NAME = 'Service.AccountRecoveryResponsesCreate.afterRejected';

    /**
     * @var array $data user provider data
     */
    protected $data = [];

    /**
     * @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy current policy
     */
    protected $policy;

    /**
     * @var \App\Utility\UserAccessControl current user
     */
    protected $uac;

    /**
     * Email redactor constructor
     */
    public function __construct()
    {
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryRequests');
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryResponses');
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $data user provided data
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse
     */
    public function create(UserAccessControl $uac, array $data): AccountRecoveryResponse
    {
        $this->setData($data, $uac);
        $this->assertPolicyIsEnabled();
        $requestEntity = $this->assertAndGetAssociatedRequest();

        $responseEntity = $this->buildAndValidateResponse($requestEntity);
        $requestEntity = $this->AccountRecoveryRequests->updateStatusAndValidateEntity(
            $this->uac,
            $requestEntity,
            $responseEntity->status
        );
        $responseEntity->account_recovery_request = $requestEntity;
        // Update original request with updated status
        $this->AccountRecoveryResponses->saveOrFail($responseEntity, compact('uac'));

        $this->deactivateTokenIfRequestIsRejectedOrExtendValidityIfApprovedAndExpired($responseEntity);

        // All good, dispatch event for emails
        $eventName = $responseEntity->isApproved()
            ? static::RESPONSE_APPROVED_EVENT_NAME
            : static::RESPONSE_REJECTED_EVENT_NAME;
        $event = new Event($eventName, $responseEntity);
        $this->AccountRecoveryResponses->getEventManager()->dispatch($event);

        return $responseEntity;
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if organization policy is disabled
     * @return void
     */
    public function assertPolicyIsEnabled(): void
    {
        $service = new AccountRecoveryOrganizationPolicyGetService();
        $this->policy = $service->get();
        if ($this->policy->isDisabled()) {
            $msg = __('Recovery response cannot be created when organization policy is disabled.');
            throw new BadRequestException($msg);
        }
    }

    /**
     * @throws \App\Error\Exception\CustomValidationException if the request id is not set, not valid, not found, is not pending
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest
     */
    public function assertAndGetAssociatedRequest(): AccountRecoveryRequest
    {
        $requestId = $this->getData('account_recovery_request_id');
        $msg = __('Could not validate response data.');

        if (!isset($requestId) || empty($requestId) || !is_string($requestId)) {
            throw new CustomValidationException($msg, [
                'account_recovery_request_id' => [
                    '_required' => 'The account recovery request id is required.',
                ],
            ]);
        }

        if (!Validation::uuid($requestId)) {
            throw new CustomValidationException($msg, [
                'account_recovery_request_id' => [
                    'uuid' => 'The account recovery request must be a uuid.',
                ],
            ]);
        }

        try {
            $request = $this->AccountRecoveryRequests->get($requestId);
        } catch (RecordNotFoundException $exception) {
            throw new CustomValidationException($msg, [
                'account_recovery_request_id' => [
                    'exists' => 'The account recovery request could not be found.',
                ],
            ]);
        }

        if (!$request->isPending()) {
            throw new CustomValidationException($msg, [
                'account_recovery_request_id' => [
                    'isRequestPendingRule' => 'The account recovery request must be in pending status.',
                ],
            ]);
        }

        return $request;
    }

    /**
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $requestEntity entity
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse entity
     */
    protected function buildAndValidateResponse(AccountRecoveryRequest $requestEntity): AccountRecoveryResponse
    {
        $msg = __('The account recovery request response is invalid.');

        // Validate response and updated request
        $responseEntity = $this->AccountRecoveryResponses
            ->buildAndValidateEntity($this->uac, $this->getData() ?? []);

        if (isset($responseEntity->data) && $responseEntity->isRejected()) {
            throw new CustomValidationException($msg, [
                'data' => [
                    'notRequiredRule' => __('The account recovery response data is not required.'),
                ],
            ]);
        }
        if (!isset($responseEntity->data) && $responseEntity->isApproved()) {
            throw new CustomValidationException($msg, [
                'data' => [
                    'required' => __('The account recovery response data is required.'),
                ],
            ]);
        }

        // Parse response data if any
        // Check for asymetric package with right temp sub key id as recipient
        if (isset($responseEntity->data)) {
            try {
                $rules = MessageValidationService::getAsymmetricMessageRules();
                $msgInfo = MessageValidationService::parseAndValidateMessage($responseEntity->data, $rules);
            } catch (CustomValidationException $exception) {
                throw new CustomValidationException($msg, [
                    'data' => [
                        'hasAsymmetricPacketRule' => __('The message must contain an asymmetric packet.'),
                    ],
                ]);
            }
            $keyInfo = PublicKeyValidationService::getPublicKeyInfo($requestEntity->armored_key);
            if (!MessageRecipientValidationService::isMessageForRecipient($msgInfo, $keyInfo)) {
                throw new CustomValidationException($msg, [
                    'data' => [
                        'wrongRecipient' => __('The message is not encrypted for the right recipient.'),
                    ],
                ]);
            }
        }

        return $responseEntity;
    }

    /**
     * @param array $data user provided data
     * @param \App\Utility\UserAccessControl $uac current user
     * @return void
     */
    protected function setData(array $data, UserAccessControl $uac): void
    {
        $this->data = $data;
        $this->uac = $uac;
    }

    /**
     * Accessor for request data in a ServerRequest style
     *
     * @param string|null $name Dot separated name of the value to read. Or null to read all data.
     * @return mixed The value being read.
     */
    protected function getData(?string $name = null)
    {
        if ($name === null) {
            return $this->data;
        }

        return Hash::get($this->data, $name);
    }

    /**
     * If an admin rejected the request, the token associated is deactivated.
     * If an admin approved the request and the associated token is expired, reset the creation time of the token
     *
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $response the response
     * @return void
     */
    protected function deactivateTokenIfRequestIsRejectedOrExtendValidityIfApprovedAndExpired(
        AccountRecoveryResponse $response
    ): void {
        $tokenId = $response->account_recovery_request->authentication_token_id;
        $token = $this->AccountRecoveryRequests->AuthenticationTokens->get($tokenId);

        if ($response->isApproved() && $token->isExpired()) {
            $token->setAccess('created', true);
            $token->set('created', FrozenTime::now());
            $this->AccountRecoveryRequests->AuthenticationTokens->saveOrFail($token);
        } elseif ($response->isRejected()) {
            $token->setAccess('active', true);
            $token->set('active', false);
            $this->AccountRecoveryRequests->AuthenticationTokens->saveOrFail($token);
        }
    }
}
