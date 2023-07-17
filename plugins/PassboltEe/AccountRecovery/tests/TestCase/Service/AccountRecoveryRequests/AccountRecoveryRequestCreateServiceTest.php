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
use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use Cake\Http\Exception\BadRequestException;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Service\AccountRecoveryRequests\AccountRecoveryRequestCreateService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryRequestCreateServiceTest extends AccountRecoveryTestCase
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
    public function testAccountRecoveryRequestCreateService_Successful()
    {
        $token = $this->authenticationTokenFactory->persist();
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();
        $user = $token->user;

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        // Add random recovery requests for this user
        AccountRecoveryRequestFactory::make(2)->withUser($user->id)->persist();

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];

        $request = (new AccountRecoveryRequestCreateService())->create($data);
        $this->assertInstanceOf(AccountRecoveryRequest::class, $request);
        $this->assertFalse($request->hasErrors());
        $this->assertTrue($request->isPending());

        // Assert that all the other requests are deactivated
        $this->assertSame(3, AccountRecoveryRequestFactory::count());
        /** @var AccountRecoveryRequest[] $requests */
        $requests = AccountRecoveryRequestFactory::find()->toArray();
        foreach ($requests as $r) {
            if ($r->id === $request->id) {
                $this->assertTrue($r->isPending());
            } else {
                $this->assertTrue($r->isRejected());
                $this->assertTrue($r->modified->isToday());
                $this->assertEquals($user->id, $r->modified_by);
            }
        }
        $this->assertSame($token->id, AccountRecoveryRequestFactory::get($request->id)->authentication_token_id);

        $this->assertTokenIsUniqueAndActive($token->id);

        // If we create a second request, the first pending request gets erased
        $token = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->active()
            ->persist();
        $data['authentication_token'] = ['token' => $token->token];
        $secondRequest = (new AccountRecoveryRequestCreateService())->create($data);
        $this->assertSame(4, AccountRecoveryRequestFactory::count());
        /** @var AccountRecoveryRequest[] $requests */
        $requests = AccountRecoveryRequestFactory::find()->toArray();
        foreach ($requests as $r) {
            if ($r->id === $secondRequest->id) {
                $this->assertTrue($r->isPending());
            } else {
                $this->assertTrue($r->isRejected());
            }
        }
        $this->assertSame($token->id, AccountRecoveryRequestFactory::get($secondRequest->id)->authentication_token_id);
    }

    /**
     * The request creation should trigger an error if policy is set to disabled
     */
    public function testAccountRecoveryRequestCreateService_ErrorPolicyDisabled()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $token = $this->authenticationTokenFactory->persist();
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();
        $user = $token->user;

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];

        try {
            (new AccountRecoveryRequestCreateService())->create($data);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('disabled', $exception->getMessage());
        } catch (\Exception $exception) {
            $this->fail();
        }
    }

    /**
     * The request creation should trigger an error if policy is set to enabled but user not enrolled
     */
    public function testAccountRecoveryRequestCreateService_ErrorUserNotEnrolled()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $token = $this->authenticationTokenFactory->persist();
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();
        $user = $token->user;

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];

        try {
            (new AccountRecoveryRequestCreateService())->create($data);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('not enrolled', $exception->getMessage());
        } catch (\Exception $exception) {
            $this->fail();
        }
    }

    /**
     * The user UUID should be set
     * If not, the token should remain active
     */
    public function testAccountRecoveryRequestCreateService_UnsetUserID()
    {
        $this->setupValidPolicy();

        $token = $this->authenticationTokenFactory->persist();

        $this->assertExceptionThrownAndTokenActive([], $token->id, 'The user identifier should be a valid UUID.');
    }

    /**
     * The user UUID should be valid
     * If not, the token should remain active
     */
    public function testAccountRecoveryRequestCreateService_InvalidUserID()
    {
        $this->setupValidPolicy();

        $token = $this->authenticationTokenFactory->persist();

        $data = [
            'user_id' => 'aaa00003-c5cd-11e1-a0c5-080027z!6c4c',
        ];

        $this->assertExceptionThrownAndTokenActive($data, $token->id, 'The user identifier should be a valid UUID.');
    }

    /**
     * The user should be activated
     * If not, the token gets deactivated
     */
    public function testAccountRecoveryRequestCreateService_InactiveUser()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->inactive())
            ->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];
        $this->assertExceptionThrownAndTokenInactive($data, $token->id, 'The user does not exist or is not active or has been deleted.');
    }

    /**
     * The user should not be deleted
     */
    public function testAccountRecoveryRequestCreateService_DeletedUser()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->deleted())
            ->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];
        $this->assertExceptionThrownAndTokenInactive($data, $token->id, 'The user does not exist or is not active or has been deleted.');
    }

    /**
     * The token user ID does not match the user ID
     */
    public function testAccountRecoveryRequestCreateService_UserMismatchInAuthenticationToken()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->active())
            ->persist();
        $user = UserFactory::make()->persist();
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];

        try {
            (new AccountRecoveryRequestCreateService())->create($data);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains($exception->getMessage(), 'The authentication token is not valid or has expired.');
        } catch (\Exception $exception) {
            $this->fail();
        }
    }

    /**
     * The user should be activated
     */
    public function testAccountRecoveryRequestCreateService_InvalidToken()
    {
        $token = $this->authenticationTokenFactory->inactive()->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];
        $this->assertExceptionThrownAndTokenInactive($data, $token->id, 'The authentication token is not valid.');
    }

    public function testAccountRecoveryRequestCreateService_InvalidArmorKey()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->active())
            ->persist();
        $user = $token->user;

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F',
            'armored_key' => 'Foo',
        ];

        try {
            (new AccountRecoveryRequestCreateService())->create($data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['armored_key']['invalidArmoredKey']));
        } catch (\Exception $exception) {
            $this->fail();
        }
    }

    public function testAccountRecoveryRequestCreateService_InvalidFingerPrint()
    {
        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->active())
            ->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => 'Foo',
            'armored_key' => $data->armored_key,
        ];

        try {
            (new AccountRecoveryRequestCreateService())->create($data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['fingerprint']));
        } catch (\Exception $exception) {
            $this->fail();
        }
    }

    public function testAccountRecoveryRequestCreateService_FingerPrintIsUsedByAnotherUser()
    {
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();
        $fingerprint = $data->fingerprint;
        UserFactory::make()->with('Gpgkeys', compact('fingerprint'))->persist();

        $token = $this->authenticationTokenFactory
            ->with('Users', UserFactory::make()->user()->active())
            ->persist();
        $user = $token->user;

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $fingerprint,
            'armored_key' => $data->armored_key,
        ];
        $this->assertExceptionThrownAndTokenActive($data, $token->id, 'You cannot reuse the user keys.');
    }

    /**
     * Armored key is revoked
     */
    public function testAccountRecoveryRequestCreateService_ArmoredKeyIsRevoked()
    {
        $token = $this->authenticationTokenFactory->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->revokedKey()->getEntity();

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];
        $this->assertExceptionThrownAndTokenActive($data, $token->id, 'The public key could not be validated.');
    }

    /**
     * Armored key is expired
     */
    public function testAccountRecoveryRequestCreateService_ArmoredKeyIsExpired()
    {
        $token = $this->authenticationTokenFactory->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->expiredKey()->getEntity();

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];
        $this->assertExceptionThrownAndTokenActive($data, $token->id, 'The public key could not be validated.');
    }

    /**
     * @Given the fingerprint of the armored key does not match the fingerkey provided
     * @When creating the request
     * @Then an error should be triggered
     */
    public function testAccountRecoveryRequestCreateService_ArmoredKeyFingerprintMismatch()
    {
        $token = $this->authenticationTokenFactory->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa4096Key()->getEntity();

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => 'EB85BB5FA33A75E15E944E63F231550C4F47E38F',
            'armored_key' => $data->armored_key,
        ];

        try {
            (new AccountRecoveryRequestCreateService())->create($data);
            $this->fail();
        } catch (CustomValidationException | ValidationException $exception) {
            $error = $exception->getErrors();
            $this->assertTrue(isset($error['fingerprint']));
        } catch (\Exception $exception) {
            $this->fail();
        }

        $this->assertTokenIsUniqueAndActive($token->id);
        $this->assertSame(0, AccountRecoveryRequestFactory::count());
    }

    /**
     * @Given the armored key is not strong enough
     * @When creating the request
     * @Then an error should be triggered
     */
    public function testAccountRecoveryRequestCreateService_ArmoredKeyNotStrongEnough()
    {
        $token = $this->authenticationTokenFactory->persist();
        $user = $token->user;
        $data = AccountRecoveryRequestFactory::make()->rsa2048Key()->getEntity();

        $this->setupValidPolicy();
        $this->setupValidUserSettings($user->id);

        $data = [
            'user_id' => $user->id,
            'authentication_token' => ['token' => $token->token],
            'fingerprint' => $data->fingerprint,
            'armored_key' => $data->armored_key,
        ];
        $this->assertExceptionThrownAndTokenActive($data, $token->id, 'The public key could not be validated.');
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

    protected function assertExceptionThrownAndTokenActive(array $data, string $tokenId, string $errorMessage): void
    {
        $this->expectExceptionMessage($errorMessage);
        try {
            (new AccountRecoveryRequestCreateService())->create($data);
            $this->fail();
        } catch (CustomValidationException | BadRequestException | ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        } finally {
            $this->assertTokenIsUniqueAndActive($tokenId);
            $this->assertSame(0, AccountRecoveryRequestFactory::count());
        }
    }

    protected function assertExceptionThrownAndTokenInactive(array $data, string $tokenId, string $errorMessage): void
    {
        $this->expectExceptionMessage($errorMessage);
        try {
            (new AccountRecoveryRequestCreateService())->create($data);
            $this->fail();
        } catch (CustomValidationException | BadRequestException | ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->fail();
        } finally {
            $this->assertTokenIsUniqueAndInactive($tokenId);
            $this->assertSame(0, AccountRecoveryRequestFactory::count());
        }
    }

    protected function setupValidPolicy(): void
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();
    }

    protected function setupValidUserSettings($userId): void
    {
        AccountRecoveryUserSettingFactory::make()
            ->setField('user_id', $userId)
            ->approved()
            ->persist();
    }
}
