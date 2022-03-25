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

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Passbolt\AccountRecovery\Plugin;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryGetRequestService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryGetRequestServiceTest extends AccountRecoveryTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        (new Plugin())->addAssociations();
    }

    public function testAccountRecoveryGetRequestService_PendingStatus_Success()
    {
        $user = UserFactory::make()->active()->persist();
        $request = AccountRecoveryRequestFactory::make()
            ->withUserAndToken($user->id)
            ->pending()
            ->persist();

        $service = $this->getService($request->user_id, $request->authentication_token_id, $request->id);
        $data = $service->getData();

        $this->assertSame($request->id, $data['id']);
        $this->assertSame($request->user_id, $data['user_id']);
        $this->assertEquals($request->created, $data['created']);
        $this->assertSame($request->created_by, $data['created_by']);
        $this->assertEquals($request->modified, $data['modified']);
        $this->assertSame($request->modified_by, $data['modified_by']);
        $this->assertNull($request->account_recovery_private_key);
        $this->assertNull($request->account_recovery_responses);
    }

    public function testAccountRecoveryGetRequestService_RejectedStatus_Success()
    {
        $user = UserFactory::make()->active()->persist();
        $request = AccountRecoveryRequestFactory::make()
            ->withUserAndToken($user->id)
            ->rejected()
            ->persist();

        $service = $this->getService($request->user_id, $request->authentication_token_id, $request->id);
        $data = $service->getData();

        $this->assertSame($request->id, $data['id']);
        $this->assertSame($request->user_id, $data['user_id']);
        $this->assertEquals($request->created, $data['created']);
        $this->assertSame($request->created_by, $data['created_by']);
        $this->assertEquals($request->modified, $data['modified']);
        $this->assertSame($request->modified_by, $data['modified_by']);
        $this->assertNull($request->account_recovery_private_key);
        $this->assertNull($request->account_recovery_responses);
    }

    public function testAccountRecoveryGetRequestService_ApprovedStatus_Success()
    {
        $user = UserFactory::make()->active()->persist();
        $request = AccountRecoveryRequestFactory::make()
            ->withUserAndToken($user->id)
            ->with('AccountRecoveryPrivateKeys')
            ->with('AccountRecoveryResponses')
            ->approved()
            ->persist();

        $service = $this->getService($request->user_id, $request->authentication_token_id, $request->id);
        $data = $service->getData();

        $this->assertSame($request->id, $data['id']);
        $this->assertSame($request->user_id, $data['user_id']);
        $this->assertEquals($request->created, $data['created']);
        $this->assertSame($request->created_by, $data['created_by']);
        $this->assertEquals($request->modified, $data['modified']);
        $this->assertSame($request->modified_by, $data['modified_by']);
        $this->assertSame($request->account_recovery_private_key->data, $data['account_recovery_private_key']['data']);
        $this->assertSame($request->account_recovery_responses[0]->responder_foreign_model, $data['account_recovery_responses'][0]['responder_foreign_model']);
        $this->assertSame($request->account_recovery_responses[0]->data, $data['account_recovery_responses'][0]['data']);
    }

    /**
     * @return array[]
     */
    public function dataForUserIdValidation(): array
    {
        return [
          [null, 'The user identifier should be a valid UUID.'],
          [UuidFactory::uuid(), 'Record not found in table "users"'],
          [UserFactory::make()->inactive(), 'Record not found in table "users"'],
        ];
    }

    /**
     * @dataProvider dataForUserIdValidation
     */
    public function testAccountRecoveryGetRequestService_InvalidUuIds($userId, $msg)
    {
        if ($userId instanceof UserFactory) {
            $userId = $userId->persist()->id;
        }
        $this->expectExceptionMessage($msg);
        $this->getService($userId, null, null);
    }

    public function testAccountRecoveryGetRequestService_ARNotActivated()
    {
        $userId = AccountRecoveryUserSettingFactory::make()
            ->withUser(UserFactory::make()->active())
            ->rejected()
            ->persist()
            ->user_id;
        $this->expectExceptionMessage('The user has not approved the account recovery feature.');
        $this->getService($userId, null, null);
    }

    /**
     * @return array[]
     */
    public function dataForTokenIdValidation(): array
    {
        return [
            [AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_RECOVER)
                ->inactive(), 'Record not found in table "authentication_tokens"'],
            [AuthenticationTokenFactory::make()->active()
                ->type('Foo'), 'Record not found in table "authentication_tokens"'],
            [AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_RECOVER)->active()
                ->expired(), 'Expired token provided.'],
        ];
    }

    /**
     * @dataProvider dataForTokenIdValidation
     */
    public function testAccountRecoveryGetRequestService_TokenNotValid(AuthenticationTokenFactory $tokenFactory, $msg)
    {
        $userId = AccountRecoveryUserSettingFactory::make()
            ->withUser(UserFactory::make()->active())
            ->approved()
            ->persist()
            ->user_id;
        $token = $tokenFactory
            ->userId($userId)
            ->persist();
        $this->expectExceptionMessage($msg);
        $this->getService($userId, $token->id, null);
    }

    public function testAccountRecoveryGetRequestService_RequestCompleted()
    {
        $user = UserFactory::make()->active()->persist();
        $request = AccountRecoveryRequestFactory::make()
            ->withUserAndToken($user->id)
            ->completed()
            ->persist();

        $this->expectExceptionMessage('The request was already completed.');
        $this->getService($request->user_id, $request->authentication_token_id, $request->id);
    }

    /**
     * Helper to conveniently get the service under test
     *
     * @param string|null $userId User ID
     * @param string|null $tokenId Token ID
     * @param string|null $requestId Request ID
     * @return AccountRecoveryGetRequestService
     */
    private function getService(?string $userId, ?string $tokenId, ?string $requestId): AccountRecoveryGetRequestService
    {
        return new AccountRecoveryGetRequestService(compact('userId', 'tokenId', 'requestId'), 'FooIP');
    }
}
