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
 * @since         3.5.0
 */

namespace Passbolt\AccountRecovery\Service\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Service\Setup\RecoverCompleteService;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;

class AccountRecoveryRecoverCompleteService extends RecoverCompleteService
{
    /**
     * @inheritDoc
     */
    protected function buildAuthenticationTokenEntity(string $userId): AuthenticationToken
    {
        $token = parent::buildAuthenticationTokenEntity($userId);
        $requestId = $this->request->getData('account_recovery_request_id');
        if (isset($requestId)) {
            $this->validateAccountRecoveryRequestId($token, $requestId);
        }

        return $token;
    }

    /**
     * @param \App\Model\Entity\AuthenticationToken $token Token being updated
     * @param string $requestId The request ID
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if the request was not found
     * @throws \Cake\Http\Exception\BadRequestException if the request is not in "approved" status
     */
    protected function validateAccountRecoveryRequestId(
        AuthenticationToken $token,
        string $requestId
    ): AuthenticationToken {
        (new AccountRecoveryOrganizationPolicyGetService())->getOrFail();

        if (!Validation::uuid($requestId)) {
            throw new BadRequestException(__('The account recovery request identifier should be a valid UUID.'));
        }

        $RequestsTable = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryRequests');

        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest $request */
        $request = $RequestsTable->find()
            ->innerJoinWith('AuthenticationTokens', function (Query $q) {
                return $q->where([
                    'AuthenticationTokens.token' => $this->request->getData('authenticationtoken.token'),
                ]);
            })
            ->where([
                'AccountRecoveryRequests.id' => $requestId,
                'AccountRecoveryRequests.user_id' => $token->user_id,
            ])
            ->contain('AccountRecoveryResponses', function (Query $query) {
                return $query
                    ->select([
                    'AccountRecoveryResponses.id',
                    'AccountRecoveryResponses.account_recovery_request_id',
                ]);
            })
            ->firstOrFail();

        if (!$request->isApproved()) {
            throw new BadRequestException(__('The account recovery request status is not approved.'));
        }

        $this->AuthenticationTokens->hasOne('Passbolt/AccountRecovery.AccountRecoveryRequests');
        $request->setAccess([
            'status',
            'modified_by',
            'account_recovery_responses',
        ], true);
        $request->status = AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_COMPLETED;
        $request->modified_by = $token->user_id;

        foreach ($request->account_recovery_responses as $response) {
            $response->setAccess(['data', 'modified_by'], true);
            $response->data = null;
            $response->modified_by = $token->user_id;
        }
        $request->setDirty('account_recovery_responses');
        $token->set('account_recovery_request', $request);

        return $token;
    }
}
