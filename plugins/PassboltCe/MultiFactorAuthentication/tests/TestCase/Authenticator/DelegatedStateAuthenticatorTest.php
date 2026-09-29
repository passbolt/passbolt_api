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
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Authentication\Authenticator\Result;
use Authentication\Identifier\IdentifierCollection;
use Cake\Http\ServerRequest;
use Passbolt\MultiFactorAuthentication\Authenticator\DelegatedStateAuthenticator;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Authenticator\DelegatedStateAuthenticator
 */
class DelegatedStateAuthenticatorTest extends AppTestCase
{
    private DelegatedStateAuthenticator $authenticator;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticator = new DelegatedStateAuthenticator(new IdentifierCollection([]));
    }

    public function tearDown(): void
    {
        unset($this->authenticator);
        parent::tearDown();
    }

    public function testDelegatedStateAuthenticator_Success_FromPostBody(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_STATE)
            ->userId($user->id)
            ->persist();
        $request = (new ServerRequest())->withParsedBody([DelegatedStateAuthenticator::STATE_PARAM => $token->token]);

        $result = $this->authenticator->authenticate($request);

        $this->assertTrue($result->isValid());
        $this->assertSame(Result::SUCCESS, $result->getStatus());
        $data = $result->getData();
        $this->assertSame($user->id, $data['user']->id);
        $this->assertSame($user->username, $data['user']->username);
    }

    public function testDelegatedStateAuthenticator_Success_FromQueryParam(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_STATE)
            ->userId($user->id)
            ->persist();
        $request = (new ServerRequest())->withQueryParams([DelegatedStateAuthenticator::STATE_PARAM => $token->token]);

        $result = $this->authenticator->authenticate($request);

        $this->assertTrue($result->isValid());
        $this->assertSame($user->id, $result->getData()['user']->id);
    }

    public function testDelegatedStateAuthenticator_Error_MissingState(): void
    {
        $result = $this->authenticator->authenticate(new ServerRequest());

        $this->assertFalse($result->isValid());
        $this->assertSame(Result::FAILURE_CREDENTIALS_MISSING, $result->getStatus());
    }

    public function testDelegatedStateAuthenticator_Error_EmptyState(): void
    {
        $request = (new ServerRequest())->withParsedBody([DelegatedStateAuthenticator::STATE_PARAM => '']);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_MISSING, $result->getStatus());
    }

    public function testDelegatedStateAuthenticator_Error_NonStringState(): void
    {
        $body = [DelegatedStateAuthenticator::STATE_PARAM => ['nested' => 'value']];
        $request = (new ServerRequest())->withParsedBody($body);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_MISSING, $result->getStatus());
    }

    public function testDelegatedStateAuthenticator_Error_UnknownState(): void
    {
        $body = [DelegatedStateAuthenticator::STATE_PARAM => UuidFactory::uuid()];
        $request = (new ServerRequest())->withParsedBody($body);

        $result = $this->authenticator->authenticate($request);

        $this->assertFalse($result->isValid());
        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testDelegatedStateAuthenticator_Error_MalformedState(): void
    {
        $request = (new ServerRequest())->withParsedBody([DelegatedStateAuthenticator::STATE_PARAM => 'not-a-uuid']);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testDelegatedStateAuthenticator_Error_ExpiredState(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->expired()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_STATE)
            ->userId($user->id)
            ->persist();
        $request = (new ServerRequest())->withParsedBody([DelegatedStateAuthenticator::STATE_PARAM => $token->token]);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testDelegatedStateAuthenticator_Error_InactiveState(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->inactive()
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_STATE)
            ->userId($user->id)
            ->persist();
        $request = (new ServerRequest())->withParsedBody([DelegatedStateAuthenticator::STATE_PARAM => $token->token]);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testDelegatedStateAuthenticator_Error_DisabledUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->user()
            ->disabled()
            ->with('AuthenticationTokens', AuthenticationTokenFactory::make()
                ->type(AuthenticationToken::TYPE_MFA_DELEGATED_STATE)
                ->active())
            ->persist();
        $request = (new ServerRequest())
            ->withParsedBody([
                DelegatedStateAuthenticator::STATE_PARAM => $user->authentication_tokens[0]->token,
            ]);

        $result = $this->authenticator->authenticate($request);

        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }
}
