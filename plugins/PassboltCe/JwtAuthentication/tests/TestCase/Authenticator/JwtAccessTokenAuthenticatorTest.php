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
namespace Passbolt\JwtAuthentication\Test\TestCase\Authenticator;

use App\Test\Factory\UserFactory;
use Authentication\Authenticator\Result;
use Authentication\Identifier\IdentifierCollection;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Firebase\JWT\JWT;
use Passbolt\JwtAuthentication\Authenticator\JwtAccessTokenAuthenticator;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use ReflectionMethod;

/**
 * @covers \Passbolt\JwtAuthentication\Authenticator\JwtAccessTokenAuthenticator
 */
class JwtAccessTokenAuthenticatorTest extends TestCase
{
    use TruncateDirtyTables;

    private JwtAccessTokenAuthenticator $authenticator;

    public function setUp(): void
    {
        parent::setUp();
        $this->authenticator = new JwtAccessTokenAuthenticator(
            new IdentifierCollection([]),
            [
                'secretKey' => (new JwksGetService())->getRawPublicKey(),
                'algorithm' => JwtTokenCreateService::JWT_ALG,
            ],
        );
    }

    public function tearDown(): void
    {
        unset($this->authenticator);
        parent::tearDown();
    }

    public function testJwtAccessTokenAuthenticator_DecodeToken_AcceptsAccessTokenAudience(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $jwt = (new JwtTokenCreateService())->createToken($user->id, '5 minutes');

        $payload = $this->decodeReflected($jwt);

        $this->assertNotNull($payload);
        $this->assertSame(JwtTokenCreateService::ACCESS_TOKEN_AUDIENCE, $payload->aud);
    }

    public function testJwtAccessTokenAuthenticator_DecodeToken_AcceptsMissingAudGracePeriod(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $legacyJwt = JWT::encode(
            [
                'iss' => Router::url('/', true),
                'sub' => $user->id,
                'exp' => time() + 300,
            ],
            (string)file_get_contents(JwtTokenCreateService::JWT_SECRET_KEY_PATH),
            JwtTokenCreateService::JWT_ALG,
        );

        $payload = $this->decodeReflected($legacyJwt);

        $this->assertNotNull($payload);
        $this->assertSame($user->id, $payload->sub);
    }

    public function testJwtAccessTokenAuthenticator_DecodeToken_RejectsForeignAud(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $foreignAudJwt = JWT::encode(
            [
                'iss' => Router::url('/', true),
                'sub' => $user->id,
                'aud' => 'passbolt-mobile-mfa-exchange',
                'exp' => time() + 60,
            ],
            (string)file_get_contents(JwtTokenCreateService::JWT_SECRET_KEY_PATH),
            JwtTokenCreateService::JWT_ALG,
        );

        $payload = $this->decodeReflected($foreignAudJwt);

        $this->assertNull($payload);
    }

    public function testJwtAccessTokenAuthenticator_DecodeToken_RejectsArbitraryAud(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $arbitraryAudJwt = JWT::encode(
            [
                'iss' => Router::url('/', true),
                'sub' => $user->id,
                'aud' => 'some-other-service',
                'exp' => time() + 60,
            ],
            (string)file_get_contents(JwtTokenCreateService::JWT_SECRET_KEY_PATH),
            JwtTokenCreateService::JWT_ALG,
        );

        $payload = $this->decodeReflected($arbitraryAudJwt);

        $this->assertNull($payload);
    }

    public function testJwtAccessTokenAuthenticator_Authenticate_ForeignAudRejected(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $foreignAudJwt = JWT::encode(
            [
                'iss' => Router::url('/', true),
                'sub' => $user->id,
                'aud' => 'passbolt-mobile-mfa-exchange',
                'exp' => time() + 60,
            ],
            (string)file_get_contents(JwtTokenCreateService::JWT_SECRET_KEY_PATH),
            JwtTokenCreateService::JWT_ALG,
        );
        $request = (new ServerRequest())->withHeader('Authorization', 'Bearer ' . $foreignAudJwt);

        $result = $this->authenticator->authenticate($request);

        $this->assertFalse($result->isValid());
        $this->assertSame(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    /**
     * @param string $jwt Token.
     * @return object|null
     */
    private function decodeReflected(string $jwt): ?object
    {
        $method = new ReflectionMethod($this->authenticator, 'decodeToken');

        return $method->invoke($this->authenticator, $jwt);
    }
}
