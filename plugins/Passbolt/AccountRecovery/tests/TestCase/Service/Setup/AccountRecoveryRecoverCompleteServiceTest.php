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

namespace Passbolt\AccountRecovery\Test\TestCase\Service\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\Utility\Hash;
use Passbolt\AccountRecovery\Service\Setup\AccountRecoveryRecoverCompleteService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryRecoverCompleteServiceTest extends AccountRecoveryTestCase
{
    public function testAccountRecoveryRecoverCompleteService_Success()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        $user = UserFactory::make()
            ->active()
            ->withAuthenticationTokens(AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_RECOVER)
                ->active())
            ->with('Gpgkeys', GpgkeyFactory::make()->rsa4096Key())
            ->persist();
        $token = $user->authentication_tokens[0];
        $gpgkey = $user->gpgkey;

        $request = AccountRecoveryRequestFactory::make()
            ->withToken($token->id)
            ->withUser($user->id)
            ->with('AccountRecoveryResponses', 5)
            ->approved()
            ->persist();
        AccountRecoveryResponseFactory::make(4)->persist();

        $serverRequest = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $gpgkey->armored_key)
            ->withData('account_recovery_request_id', $request->id);

        $service = (new AccountRecoveryRecoverCompleteService($serverRequest));
        $service->complete($user->id);

        // Check that token is now inactive
        $this->assertSame(1, AuthenticationTokenFactory::count());
        $this->assertFalse(AuthenticationTokenFactory::get($token->id)->isActive());
        // Check that the request is now marked as completed
        $updatedRequest = AccountRecoveryRequestFactory::get($request->id);
        $this->assertTrue($updatedRequest->isCompleted());
        $this->assertSame($user->id, $updatedRequest->modified_by);
        $this->assertSame(1, AccountRecoveryRequestFactory::count());
        // Check that the data of the responses is set to null
        $responses = AccountRecoveryResponseFactory::find();
        $requestResponsesId = Hash::extract($request->account_recovery_responses, '{n}.id');
        foreach ($responses as $response) {
            /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse $response  */
            if (in_array($response->id, $requestResponsesId)) {
                $this->assertNull($response->data);
                $this->assertSame($user->id, $response->modified_by);
                $this->assertTrue($response->modified->isToday());
            } else {
                $this->assertNotNull($response->data);
                $this->assertNotSame($user->id, $response->modified_by);
                $this->assertFalse($response->modified->isToday());
            }
        }
    }

    /**
     * @Given account recovery is enabled
     * @And the policy is not activated
     * @And not account_request_id is in the payload
     * @When performing a recovery
     * @Then no exception should be thrown
     */
    public function testAccountRecoveryRecoverCompleteService_Success_No_AR()
    {
        $user = UserFactory::make()
            ->active()
            ->withAuthenticationTokens(AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_RECOVER)
                ->active())
            ->with('Gpgkeys', GpgkeyFactory::make()->rsa4096Key())
            ->persist();
        $token = $user->authentication_tokens[0];
        $gpgkey = $user->gpgkey;

        $serverRequest = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $gpgkey->armored_key);

        $service = (new AccountRecoveryRecoverCompleteService($serverRequest));
        $service->complete($user->id);

        // Check that token is now inactive
        $this->assertSame(1, AuthenticationTokenFactory::count());
        $this->assertFalse(AuthenticationTokenFactory::get($token->id)->isActive());
    }

    public function testAccountRecoveryRecoverCompleteService_ARDisabled_Non_Valid_UserId()
    {
        $serverRequest = (new ServerRequest());

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The user identifier should be a valid UUID.');

        $service = (new AccountRecoveryRecoverCompleteService($serverRequest));
        $service->complete('Foo');
    }

    public function testAccountRecoveryRecoverCompleteService_ValidateRequestID()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
        $user = UserFactory::make()
            ->active()
            ->withAuthenticationTokens(AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_RECOVER)
                ->active())
            ->with('Gpgkeys', GpgkeyFactory::make()->rsa4096Key())
            ->persist();
        $token = $user->authentication_tokens[0];
        $gpgkey = $user->gpgkey;

        $serverRequest = (new ServerRequest())
            ->withData('authenticationtoken.token', $token->token)
            ->withData('gpgkey.armored_key', $gpgkey->armored_key)
            ->withData('account_recovery_request_id', 'Foo');

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The account recovery request identifier should be a valid UUID.');
        $service = (new AccountRecoveryRecoverCompleteService($serverRequest));
        $service->complete($user->id);
    }
}
