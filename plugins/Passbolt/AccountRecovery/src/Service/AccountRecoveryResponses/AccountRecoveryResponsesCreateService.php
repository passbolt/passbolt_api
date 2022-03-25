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
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Exception\BadRequestException;
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
    public const RESPONSE_CREATED_EVENT_NAME = 'Service.AccountRecoveryResponsesCreate.afterCreate';

    use ModelAwareTrait;

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

        $data = array_merge($data, [
            'created_by' => $uac->getId(),
            'modified_by' => $uac->getId(),
        ]);
        $responseEntity = $this->AccountRecoveryResponses->newEntity($data, [
            'accessibleFields' => [
                'account_recovery_request_id' => true,
                'responder_foreign_key' => true,
                'responder_foreign_model' => true,
                'data' => true,
                'status' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);

        if ($responseEntity->getErrors()) {
            $msg = __('The account recovery request response is invalid.');
            throw new CustomValidationException($msg, $responseEntity->getErrors());
        }

        $requestEntity = $this->AccountRecoveryRequests->patchEntity($requestEntity, [
            'status' => $responseEntity->status,
            'modified_by' => $uac->getId(),
        ], ['accessibleFields' => [
            'status' => true,
            'modified_by' => true,
        ]]);

        // Update original request with updated status
        $this->AccountRecoveryRequests->getConnection()->transactional(function () use ($requestEntity, &$responseEntity) {
            $this->AccountRecoveryRequests->saveOrFail($requestEntity);
            $responseEntity = $this->AccountRecoveryResponses->saveOrFail($responseEntity);
        });

//        if ($newEntity->getErrors()) {
//            $msg = __('The account recovery request response is invalid.');
//            throw new CustomValidationException($msg ,$newEntity->getErrors());
//        }
//
//        // All good, dispatch event for emails
//        $event = new Event(static::RESPONSE_CREATED_EVENT_NAME, $responseEntity);
//        $this->AccountRecoveryResponses->getEventManager()->dispatch($event);

        return $responseEntity;
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if organization policy is disabled
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
}
