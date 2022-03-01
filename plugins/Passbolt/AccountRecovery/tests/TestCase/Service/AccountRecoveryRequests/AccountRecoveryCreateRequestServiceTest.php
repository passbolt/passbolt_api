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
        $user = $token->user;

        // Add random recovery requests for this user
        AccountRecoveryRequestFactory::make(2)->withUser($user->id)->persist();

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', 'EB85BB5FA33A75E15E944E63F231550C4F47E38E')
            ->withData('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'));

        $result = (new AccountRecoveryCreateRequestService($serverRequest))->create();
        $this->assertInstanceOf(AccountRecoveryRequest::class, $result);
        $this->assertFalse($result->hasErrors());

        // Assert that all the other requests are deactivated
        $this->assertSame(1, AccountRecoveryRequestFactory::count());
        $this->assertSame($token->id, AccountRecoveryRequestFactory::find()->first()->get('authentication_token_id'));
    }

    /**
     * The user should be activated
     */
    public function testAccountRecoveryRequestsService_InactiveUser()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->inactive())
            ->persist();
        $user = $token->user;

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', 'EB85BB5FA33A75E15E944E63F231550C4F47E38E')
            ->withData('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'));

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The user does not exist or is not active or has been deleted.');
        (new AccountRecoveryCreateRequestService($serverRequest))->create();
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

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', 'EB85BB5FA33A75E15E944E63F231550C4F47E38E')
            ->withData('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'));

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The user does not exist or is not active or has been deleted.');
        (new AccountRecoveryCreateRequestService($serverRequest))->create();
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

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', 'EB85BB5FA33A75E15E944E63F231550C4F47E38E')
            ->withData('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'));

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The authentication token is not valid or has expired.');
        (new AccountRecoveryCreateRequestService($serverRequest))->create();
    }

    /**
     * The user should be activated
     */
    public function testAccountRecoveryRequestsService_InvalidToken()
    {
        $token = $this->authenticationTokenFactory->inactive()->persist();
        $user = $token->user;

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', 'EB85BB5FA33A75E15E944E63F231550C4F47E38E')
            ->withData('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'));

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The authentication token is not valid or has expired.');
        (new AccountRecoveryCreateRequestService($serverRequest))->create();
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

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The armored key should be a valid ASCII-armored OpenPGP key.');
        (new AccountRecoveryCreateRequestService($serverRequest))->create();
    }

    public function testAccountRecoveryRequestsService_InvalidFingerPrint()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->active())
            ->persist();
        $user = $token->user;

        $serverRequest = (new ServerRequest())
            ->withData('user_id', $user->id)
            ->withData('authentication_token.token', $token->token)
            ->withData('fingerprint', 'Foo')
            ->withData('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'));

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The fingerprint should be a string of 40 hexadecimal characters.');
        (new AccountRecoveryCreateRequestService($serverRequest))->create();
    }
}
