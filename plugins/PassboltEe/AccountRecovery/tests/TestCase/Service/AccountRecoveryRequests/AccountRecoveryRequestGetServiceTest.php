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

namespace Passbolt\AccountRecovery\Test\TestCase\Service\AccountRecoveryRequests;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\AccountRecovery\AccountRecoveryPlugin;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryRequestGetService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryRequestScenario;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryRequestGetServiceTest extends AccountRecoveryTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        (new AccountRecoveryPlugin())->addAssociationsToUsersTable();
    }

    public function testAccountRecoveryRequestGetService_Success_PendingStatus()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioPending();

        $service = new AccountRecoveryRequestGetService();
        $request = $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
        $data = $service->decorateResults($request);

        $this->assertSame($request->id, $data['id']);
        $this->assertSame($request->user_id, $data['user_id']);
        $this->assertEquals($request->created, $data['created']);
        $this->assertSame($request->created_by, $data['created_by']);
        $this->assertEquals($request->modified, $data['modified']);
        $this->assertSame($request->modified_by, $data['modified_by']);
        $this->assertNull($request->account_recovery_private_key);
        $this->assertNull($request->account_recovery_responses);
    }

    public function testAccountRecoveryRequestGetService_Success_RejectedStatus()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioRejected();

        $service = new AccountRecoveryRequestGetService();
        $request = $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
        $data = $service->decorateResults($request);

        $this->assertSame($request->id, $data['id']);
        $this->assertSame($request->user_id, $data['user_id']);
        $this->assertEquals($request->created, $data['created']);
        $this->assertSame($request->created_by, $data['created_by']);
        $this->assertEquals($request->modified, $data['modified']);
        $this->assertSame($request->modified_by, $data['modified_by']);
        $this->assertNull($request->account_recovery_private_key);
        $this->assertNull($request->account_recovery_responses);
    }

    public function testAccountRecoveryRequestGetService_Success_Approved()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioApproved();

        $service = new AccountRecoveryRequestGetService();
        $request = $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
        $data = $service->decorateResults($request);

        $this->assertSame($request->id, $data['id']);
        $this->assertSame($request->user_id, $data['user_id']);
        $this->assertEquals($request->created, $data['created']);
        $this->assertSame($request->created_by, $data['created_by']);
        $this->assertEquals($request->modified, $data['modified']);
        $this->assertSame($request->modified_by, $data['modified_by']);
        $this->assertTextContains('-----BEGIN PGP MESSAGE-----', $data['account_recovery_private_key']['data']);
        $this->assertSame('AccountRecoveryOrganizationKey', $data['account_recovery_responses'][0]['responder_foreign_model']);
        $this->assertTextContains('-----BEGIN PGP MESSAGE-----', $data['account_recovery_responses'][0]['data']);
    }

    // Org policy errors

    public function testAccountRecoveryRequestGetService_Error_OrgPolicyNotActivated()
    {
        AccountRecoveryOrganizationPolicyFactory::make()->disabled()->persist();

        $this->expectExceptionMessage('Account recovery is disabled.');
        $requestId = $userId = $token = UuidFactory::uuid();
        (new AccountRecoveryRequestGetService())->getNotCompletedOrFail($requestId, $userId, $token);
    }

    public function testAccountRecoveryRequestGetService_Error_OrgPolicyHasNoPublicKey()
    {
        AccountRecoveryOrganizationPolicyFactory::make()->mandatory()->persist();

        $this->expectExceptionMessage('The account recovery organization public key is not set.');
        $requestId = $userId = $token = UuidFactory::uuid();
        (new AccountRecoveryRequestGetService())->getNotCompletedOrFail($requestId, $userId, $token);
    }

    // User errors

    public function testAccountRecoveryRequestGetService_Error_UserNotActive()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = UserFactory::make()->inactive()->user()->persist();

        $token = AccountRecoveryRequestScenario::startToken($user);

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(BadRequestException::class);
        $service->getNotCompletedOrFail(UuidFactory::uuid(), $user->id, $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_UserIdInvalid()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioApproved();

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(NotFoundException::class);
        $service->getNotCompletedOrFail($request->id, '00000000-0000-0000-0000-000000000000', $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_UserDeleted()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = AccountRecoveryRequestScenario::startUserDeleted();
        $token = AccountRecoveryRequestScenario::startToken($user);
        $request = AccountRecoveryRequestScenario::startRequestApproved($user, $token);

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(BadRequestException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_UserDoesNotExist()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = AccountRecoveryRequestScenario::startUserDeleted();
        $token = AccountRecoveryRequestScenario::startToken($user);
        $request = AccountRecoveryRequestScenario::startRequestApproved($user, $token);

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(NotFoundException::class);
        $service->getNotCompletedOrFail($request->id, UuidFactory::uuid(), $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_UserNotEnrolled()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = UserFactory::make()
            ->active()
            ->user()
            ->with('Gpgkeys')
            ->persist();

        $token = AccountRecoveryRequestScenario::startToken($user);
        $request = AccountRecoveryRequestScenario::startRequestApproved($user, $token);

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(NotFoundException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
    }

    // Token errors

    public function testAccountRecoveryRequestGetService_Error_TokenIdInvalid()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioApproved();

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(BadRequestException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, 'nope');
    }

    public function testAccountRecoveryRequestGetService_Error_TokenDoesNotExist()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioApproved();

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(NotFoundException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, UuidFactory::uuid());
    }

    public function testAccountRecoveryRequestGetService_Error_TokenExpired()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = AccountRecoveryRequestScenario::startUser();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->expired()
            ->persist();
        $request = AccountRecoveryRequestScenario::startRequestApproved($user, $token);

        $service = new AccountRecoveryRequestGetService();
        $exceptionThrown = false;
        try {
            $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
        } catch (CustomValidationException $e) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
        $this->assertTrue(AccountRecoveryRequestFactory::get($request->id)->isRejected());
        $this->assertTrue(AuthenticationTokenFactory::get($token->id)->isNotActive());
    }

    public function testAccountRecoveryRequestGetService_Error_TokenNotActive()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = AccountRecoveryRequestScenario::startUser();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->inactive()
            ->persist();
        $request = AccountRecoveryRequestScenario::startRequestApproved($user, $token);

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(CustomValidationException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_TokenNotForUser()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = AccountRecoveryRequestScenario::startUser();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId(UuidFactory::uuid())
            ->active()
            ->persist();
        $request = AccountRecoveryRequestScenario::startRequestApproved($user, $token);

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(NotFoundException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_TokenNotRecoveryType()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = AccountRecoveryRequestScenario::startUser();
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_LOGIN)
            ->userId($user->id)
            ->active()
            ->persist();
        $request = AccountRecoveryRequestScenario::startRequestApproved($user, $token);

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(NotFoundException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
    }

    // Request errors

    public function testAccountRecoveryRequestGetService_Error_RequestIDInvalid()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioApproved();

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(BadRequestException::class);
        $service->getNotCompletedOrFail('nope', $user->id, $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_RequestNotForUser()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = AccountRecoveryRequestScenario::startUser();
        $token = AccountRecoveryRequestScenario::startToken($user);
        $request = AccountRecoveryRequestScenario::startRequestWrongUser($user, $token);

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(NotFoundException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_RequestNotForTokenId()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = AccountRecoveryRequestScenario::startUser();
        $token = AccountRecoveryRequestScenario::startToken($user);
        $request = AccountRecoveryRequestScenario::startRequestWrongToken($user, $token);

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(NotFoundException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_RequestDoesNotExist()
    {
        [$request, $user, $token] = AccountRecoveryRequestScenario::startContinueScenarioApproved();

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(NotFoundException::class);
        $service->getNotCompletedOrFail(UuidFactory::uuid(), $user->id, $token->token);
    }

    public function testAccountRecoveryRequestGetService_Error_RequestAlreadyCompleted()
    {
        AccountRecoveryRequestScenario::startPolicy();
        $user = AccountRecoveryRequestScenario::startUser();
        $token = AccountRecoveryRequestScenario::startToken($user);
        $request = AccountRecoveryRequestFactory::make()
            ->completed()
            ->withUser($user->id)
            ->setField('authentication_token_id', $token->id)
            ->persist();

        $service = new AccountRecoveryRequestGetService();
        $this->expectException(BadRequestException::class);
        $service->getNotCompletedOrFail($request->id, $user->id, $token->token);
    }
}
