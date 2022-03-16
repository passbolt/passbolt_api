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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryCreateRequestService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryCreateRequestServiceTest extends AccountRecoveryTestCase
{
    /**
     * @var AuthenticationTokenFactory
     */
    protected $authenticationTokenFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticationTokenFactory = AuthenticationTokenFactory::make()
            ->with('Users', UserFactory::make()->user()->active())
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->active();
    }

    public function tearDown(): void
    {
        unset($this->authenticationTokenFactory);
        parent::tearDown();
    }

    /**
     * Successful path
     */
    public function testAccountRecoveryRequestsService_Successful()
    {
        $token = $this->authenticationTokenFactory->persist();
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();
        $user = $token->user;

        // Add random recovery requests for this user
        AccountRecoveryRequestFactory::make(2)->withUser($user->id)->persist();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', $data->fingerprint)
            ->withData('armored_key', $data->armored_key);

        $result = (new AccountRecoveryCreateRequestService($serverRequest))->create();
        $this->assertInstanceOf(AccountRecoveryRequest::class, $result);
        $this->assertFalse($result->hasErrors());

        // Assert that all the other requests are deactivated
        $this->assertSame(1, AccountRecoveryRequestFactory::count());
        $this->assertSame($token->id, AccountRecoveryRequestFactory::find()->first()->get('authentication_token_id'));

        $this->assertTokenIsUniqueAndInactive($token->id);
    }

    /**
     * The user UUID should be set
     * If not, the token should remain active
     */
    public function testAccountRecoveryRequestsService_UnsetUserID()
    {
        $token = $this->authenticationTokenFactory->persist();

        $serverRequest = (new ServerRequest());

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'The user identifier should be a valid UUID.');
    }

    /**
     * The user UUID should be valid
     * If not, the token should remain active
     */
    public function testAccountRecoveryRequestsService_InvalidUserID()
    {
        $token = $this->authenticationTokenFactory->persist();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', 'aaa00003-c5cd-11e1-a0c5-080027z!6c4c');

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'The user identifier should be a valid UUID.');
    }

    /**
     * The user should be activated
     * If not, the token gets deactivated
     */
    public function testAccountRecoveryRequestsService_InactiveUser()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->inactive())
            ->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', $data->fingerprint)
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenInactive($serverRequest, $token->id, 'The user does not exist or is not active or has been deleted.');
    }

    /**
     * The user should not be deleted
     */
    public function testAccountRecoveryRequestsService_DeletedUser()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->deleted())
            ->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', $data->fingerprint)
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenInactive($serverRequest, $token->id, 'The user does not exist or is not active or has been deleted.');
    }

    /**
     * The token user ID does not match the user ID
     */
    public function testAccountRecoveryRequestsService_UserMismatchInAuthenticationToken()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->active())
            ->persist();
        $user = UserFactory::make()->persist();
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', $data->fingerprint)
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'The authentication token is not valid or has expired.');
    }

    /**
     * The user should be activated
     */
    public function testAccountRecoveryRequestsService_InvalidToken()
    {
        $token = $this->authenticationTokenFactory->inactive()->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', $data->fingerprint)
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenInactive($serverRequest, $token->id, 'The authentication token is not valid or has expired.');
    }

    public function testAccountRecoveryRequestsService_InvalidArmorKey()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->active())
            ->persist();
        $user = $token->user;

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F')
            ->withData('armored_key', 'Foo');

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'A valid OpenPGP key must be provided.');
    }

    public function testAccountRecoveryRequestsService_InvalidFingerPrint()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->active())
            ->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', 'Foo')
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'The fingerprint should be a string of 40 hexadecimal characters.');
    }

    public function testAccountRecoveryRequestsService_FingerPrintIsUsedByAnotherUser()
    {
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();
        $fingerprint = $data->fingerprint;
        UserFactory::make()->with('Gpgkeys', compact('fingerprint'))->persist();

        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->active())
            ->persist();
        $user = $token->user;

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', $fingerprint)
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'You cannot reuse the user keys.');
    }

    /**
     * Armored key is revoked
     */
    public function testAccountRecoveryRequestsService_ArmoredKeyIsRevoked()
    {
        $token = $this->authenticationTokenFactory->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->revokedKey()->getEntity();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', $data->fingerprint)
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'The public key could not be validated.');
    }

    /**
     * Armored key is expired
     */
    public function testAccountRecoveryRequestsService_ArmoredKeyIsExpired()
    {
        $token = $this->authenticationTokenFactory->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->expiredKey()->getEntity();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', $data->fingerprint)
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'The public key could not be validated.');
    }

    /**
     * @Given the fingerprint of the armored key does not match the fingerkey provided
     * @When creating the request
     * @Then an error should be triggered
     */
    public function testAccountRecoveryRequestsService_ArmoredKeyFingerprintMismatch()
    {
        $token = $this->authenticationTokenFactory->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', 'EB85BB5FA33A75E15E944E63F231550C4F47E38F')
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'The fingerprint does not match the one of the armored key.');
    }

    /**
     * @Given the armored key is not strong enough
     * @When creating the request
     * @Then an error should be triggered
     */
    public function testAccountRecoveryRequestsService_ArmoredKeyNotStrongEnough()
    {
        $token = $this->authenticationTokenFactory->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa2048Key()->getEntity();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', $data->fingerprint)
            ->withData('armored_key', $data->armored_key);

        $this->assertExceptionThrownAndTokenActive($serverRequest, $token->id, 'The public key could not be validated.');
    }

    ############ Helping methods #####################

    /**
     * Asserts that a token is inactive, and that no other tokens were created
     *
     * @param string $tokenId Token ID
     * @return void
     */
    protected function assertTokenIsUniqueAndInactive(string $tokenId): void
    {
        $this->assertFalse(AuthenticationTokenFactory::get($tokenId)->isActive());
        $this->assertSame(1, AuthenticationTokenFactory::count());
    }

    /**
     * Asserts that a token is active, and that no other tokens were created
     *
     * @param string $tokenId Token ID
     * @return void
     */
    protected function assertTokenIsUniqueAndActive(string $tokenId): void
    {
        $this->assertTrue(AuthenticationTokenFactory::get($tokenId)->isActive());
        $this->assertSame(1, AuthenticationTokenFactory::count());
    }

    protected function assertExceptionThrownAndTokenActive(ServerRequest $request, string $tokenId, string $errorMessage): void
    {
        $this->expectExceptionMessage($errorMessage);
        try {
            (new AccountRecoveryCreateRequestService($request))->create();
            $this->fail();
        } catch (CustomValidationException | BadRequestException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->fail();
        } finally {
            $this->assertTokenIsUniqueAndActive($tokenId);
            $this->assertSame(0, AccountRecoveryRequestFactory::count());
        }
    }

    protected function assertExceptionThrownAndTokenInactive(ServerRequest $request, string $tokenId, string $errorMessage): void
    {
        $this->expectExceptionMessage($errorMessage);
        try {
            (new AccountRecoveryCreateRequestService($request))->create();
            $this->fail();
        } catch (CustomValidationException | BadRequestException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->fail();
        } finally {
            $this->assertTokenIsUniqueAndInactive($tokenId);
            $this->assertSame(0, AccountRecoveryRequestFactory::count());
        }
    }
}
