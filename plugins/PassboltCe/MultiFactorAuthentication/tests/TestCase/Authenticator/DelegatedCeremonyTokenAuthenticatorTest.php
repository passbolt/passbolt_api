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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Authenticator;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Authentication\Authenticator\Result;
use Authentication\Identifier\IdentifierCollection;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Authenticator\DelegatedCeremonyTokenAuthenticator;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Authenticator\DelegatedCeremonyTokenAuthenticator
 */
class DelegatedCeremonyTokenAuthenticatorTest extends TestCase
{
    use TruncateDirtyTables;

    private DelegatedCeremonyTokenAuthenticator $authenticator;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticator = new DelegatedCeremonyTokenAuthenticator(new IdentifierCollection([]));
    }

    public function tearDown(): void
    {
        unset($this->authenticator);
        parent::tearDown();
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_ReturnsUserWhenTokenInBody(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->userId($user->id)
            ->persist();
        $request = (new ServerRequest())
            ->withParsedBody([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => $token->token]);

        $result = $this->authenticator->authenticate($request);

        $this->assertTrue($result->isValid());
        $this->assertSame(Result::SUCCESS, $result->getStatus());
        $data = $result->getData();
        $this->assertSame($user->id, $data['user']->id);
        $this->assertSame($user->username, $data['user']->username);
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_ReturnsUserWhenTokenInQuery(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->userId($user->id)
            ->persist();
        $request = (new ServerRequest())
            ->withQueryParams([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => $token->token]);

        $result = $this->authenticator->authenticate($request);

        $this->assertTrue($result->isValid());
        $this->assertSame($user->id, $result->getData()['user']->id);
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_BodyWinsOverQuery(): void
    {
        /** @var \App\Model\Entity\User $bodyUser */
        $bodyUser = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $bodyToken */
        $bodyToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->userId($bodyUser->id)
            ->persist();
        /** @var \App\Model\Entity\User $queryUser */
        $queryUser = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $queryToken */
        $queryToken = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->userId($queryUser->id)
            ->persist();
        $request = (new ServerRequest())
            ->withParsedBody([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => $bodyToken->token])
            ->withQueryParams([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => $queryToken->token]);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame($bodyUser->id, $result->getData()['user']->id);
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_MissingTokenReturnsCredentialsMissing(): void
    {
        $result = $this->authenticator->authenticate(new ServerRequest());

        $this->assertFalse($result->isValid());
        $this->assertSame(Result::FAILURE_CREDENTIALS_MISSING, $result->getStatus());
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_EmptyTokenReturnsCredentialsMissing(): void
    {
        $request = (new ServerRequest())
            ->withParsedBody([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => '']);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_MISSING, $result->getStatus());
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_NonStringTokenReturnsCredentialsMissing(): void
    {
        $request = (new ServerRequest())
            ->withParsedBody([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => ['nested' => 'value']]);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_MISSING, $result->getStatus());
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_UnknownTokenReturnsCredentialsInvalid(): void
    {
        $request = (new ServerRequest())
            ->withParsedBody([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => UuidFactory::uuid()]);

        $result = $this->authenticator->authenticate($request);

        $this->assertFalse($result->isValid());
        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_MalformedTokenReturnsCredentialsInvalid(): void
    {
        $request = (new ServerRequest())
            ->withParsedBody([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => 'not-a-uuid']);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_ExpiredTokenReturnsCredentialsInvalid(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->expired()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->userId($user->id)
            ->persist();
        $request = (new ServerRequest())
            ->withParsedBody([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => $token->token]);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_InactiveTokenReturnsCredentialsInvalid(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->inactive()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->userId($user->id)
            ->persist();
        $request = (new ServerRequest())
            ->withParsedBody([DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => $token->token]);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testDelegatedCeremonyTokenAuthenticator_Authenticate_DisabledUserReturnsCredentialsInvalid(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->disabled()
            ->with('AuthenticationTokens', AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
                ->active())
            ->persist();
        $request = (new ServerRequest())
            ->withParsedBody([
                DelegatedCeremonyTokenAuthenticator::TOKEN_PARAM => $user->authentication_tokens[0]->token,
            ]);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }
}
