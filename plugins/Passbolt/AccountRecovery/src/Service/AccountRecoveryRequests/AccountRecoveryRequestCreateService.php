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
use App\Model\Entity\Role;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\UserAccessControl;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;
use Passbolt\AccountRecovery\Service\AccountRecoveryUserSettings\AccountRecoveryUserSettingsGetService;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable $AccountRecoveryRequests
 */
class AccountRecoveryRequestCreateService
{
    use ModelAwareTrait;

    public const REQUEST_CREATED_EVENT_NAME = 'Service.AccountRecoveryRequestCreate.afterCreate';

    /**
     * @var array $data user provider data
     */
    protected $data = [];

    /**
     * @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy current policy
     */
    protected $policy;

    /**
     * AccountRecoveryRequestCreateService constructor.
     */
    public function __construct()
    {
        $this->loadModel('AuthenticationTokens');
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryRequests');
    }

    /**
     * @param array $data user provided data
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest
     */
    public function create(array $data): AccountRecoveryRequest
    {
        $this->setData($data);
        $uac = new UserAccessControl(Role::USER, $this->assertUserId());
        $this->assertPolicyIsEnabled();
        $this->assertUserIsEnrolled();

        $token = $this->getAndAssertToken($uac->getId());
        $request = $this->AccountRecoveryRequests->buildAndValidateEntity($uac, $token->id, $this->getData());

        PublicKeyValidationService::parseAndValidatePublicKey(
            $this->getData('armored_key'),
            PublicKeyValidationService::getStrictRules()
        );
        try {
            $this->AccountRecoveryRequests->saveOrFail($request, compact('uac'));
        } catch (PersistenceFailedException $e) {
            if ($e->getEntity()->getError('user_id')) {
                $this->AuthenticationTokens->setInactive($token->token);
            }
            throw new BadRequestException($e->getMessage());
        }

        $event = new Event(static::REQUEST_CREATED_EVENT_NAME, $request);
        EventManager::instance()->dispatch($event);

        return $request;
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if organization policy is disabled
     * @return void
     */
    public function assertPolicyIsEnabled(): void
    {
        $service = new AccountRecoveryOrganizationPolicyGetService();
        $policy = $service->get();
        if ($policy->isDisabled()) {
            $msg = __('Recovery request cannot be created when organization policy is disabled.');
            throw new BadRequestException($msg);
        }
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if user id is not valid
     * @return string uuid
     */
    public function assertUserId(): string
    {
        $userId = $this->getData('user_id');
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }

        return $userId;
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if organization policy is disabled
     * @return void
     */
    public function assertUserIsEnrolled(): void
    {
        $service = new AccountRecoveryUserSettingsGetService();
        $userSettings = $service->get($this->getData('user_id'));
        if (!isset($userSettings) || $userSettings->isRejected()) {
            $msg = __('Recovery request cannot be created when user is not enrolled.');
            throw new BadRequestException($msg);
        }
    }

    /**
     * Return the authentication from data if any
     *
     * @param string $userId the user uuid the token belongs to
     * @throws \Cake\Http\Exception\BadRequestException if no authentication token was provided
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is expired or invalid
     * @return \App\Model\Entity\AuthenticationToken
     */
    protected function getAndAssertToken(string $userId): AuthenticationToken
    {
        $token = $this->getData('authentication_token.token');
        if (!isset($token)) {
            throw new BadRequestException(__('An authentication token should be provided.'));
        }

        try {
            $tokenEntity = (new AuthenticationTokenGetService())
                ->getActiveNotExpiredOrFail($token, $userId, AuthenticationToken::TYPE_RECOVER);
        } catch (NotFoundException $exception) {
            throw new BadRequestException(__('The authentication token is not valid or has expired.'));
        }

        // Deactivate all previous active tokens
        $this->AuthenticationTokens->query()
            ->update()
            ->set(['active' => false])
            ->where([
                'id <>' => $tokenEntity->id,
                'active' => true,
                'type' => AuthenticationToken::TYPE_RECOVER,
                'user_id' => $userId,
            ])
            ->execute();

        return $tokenEntity;
    }

    /**
     * @param array $data user provided data
     * @return void
     */
    protected function setData(array $data): void
    {
        $this->data = $data;
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
