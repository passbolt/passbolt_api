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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Test\TestCase\Authenticator;

use App\Middleware\ContainerInjectorMiddleware;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Authentication\Authenticator\Result;
use Authentication\Identifier\TokenIdentifier;
use Cake\Core\Configure;
use Cake\Core\Container;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\JwtAuthentication\Authenticator\GpgJwtAuthenticator;
use Passbolt\JwtAuthentication\Authenticator\JwtArmoredChallengeInterface;
use Passbolt\JwtAuthentication\Authenticator\JwtArmoredChallengeService;
use Passbolt\JwtAuthentication\JwtAuthenticationPlugin;

class GpgJwtAuthenticatorTest extends TestCase
{
    use FeaturePluginAwareTrait;
    use GpgAdaSetupTrait;
    use TruncateDirtyTables;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/Gpgkeys',
    ];

    protected $sut;

    public function setUp(): void
    {
        parent::setUp();
        OpenPGPBackendFactory::reset();
        $this->sut = new GpgJwtAuthenticator(new TokenIdentifier());
        EventManager::instance()->setEventList(new EventList());
        $this->enableFeaturePlugin(JwtAuthenticationPlugin::class);
    }

    public function testGpgJwtAuthenticatorAuthenticateError_NoData()
    {
        $this->gpgSetup();
        $request = new ServerRequest();
        $result = $this->sut->authenticate($request);
        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_InvalidUserId()
    {
        $this->gpgSetup();
        $request = new ServerRequest();
        $request = $request->withData('user_id', 'nope');
        $result = $this->sut->authenticate($request);
        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_NotFoundUser()
    {
        $this->gpgSetup();
        $request = new ServerRequest();
        $request = $request->withData('user_id', UuidFactory::uuid());
        $result = $this->sut->authenticate($request);
        $this->assertEquals(Result::FAILURE_IDENTITY_NOT_FOUND, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_NoChallenge()
    {
        $this->gpgSetup();
        $request = new ServerRequest();
        $request = $request->withData('user_id', UuidFactory::uuid('user.id.ada'));
        $result = $this->sut->authenticate($request);
        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_NotOpenPGPChallenge()
    {
        $this->gpgSetup();
        $request = new ServerRequest();

        $request = $request->withData('user_id', UuidFactory::uuid('user.id.ada'));
        $request = $request->withData('challenge', 'nope');
        $result = $this->sut->authenticate($request);

        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_WrongSignatureChallenge()
    {
        $this->gpgSetup();
        $request = new ServerRequest();

        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $this->gpg->setSignKeyFromFingerprint($this->adaKeyId, '');
        $msg = $this->gpg->encrypt('no sig');

        $request = $request->withData('user_id', UuidFactory::uuid('user.id.ada'));
        $request = $request->withData('challenge', $msg);
        $result = $this->sut->authenticate($request);

        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_WrongChallengeFormat()
    {
        $this->gpgSetup();
        $request = new ServerRequest();

        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $this->gpg->setSignKeyFromFingerprint($this->adaKeyId, '');
        $msg = $this->gpg->encryptSign('wrong format');

        $request = $request->withData('user_id', UuidFactory::uuid('user.id.ada'));
        $request = $request->withData('challenge', $msg);
        $result = $this->sut->authenticate($request);

        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_WrongVersion()
    {
        $this->gpgSetup();
        $request = new ServerRequest();

        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $this->gpg->setSignKeyFromFingerprint($this->adaKeyId, '');
        $challenge = [
            'version' => '2.0.0',
            'domain' => Router::url('/', true),
            'verify_token' => UuidFactory::uuid(),
            'verify_token_expiry' => time() + 60,
        ];
        $msg = $this->gpg->encryptSign(json_encode($challenge));

        $request = $request->withData('user_id', UuidFactory::uuid('user.id.ada'));
        $request = $request->withData('challenge', $msg);
        $result = $this->sut->authenticate($request);

        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_WrongDomain()
    {
        $this->gpgSetup();
        $request = new ServerRequest();

        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $this->gpg->setSignKeyFromFingerprint($this->adaKeyId, '');
        $challenge = [
            'version' => '1.0.0',
            'domain' => 'https://cloud.passbolt.com/test',
            'verify_token' => UuidFactory::uuid(),
            'verify_token_expiry' => time() + 60,
        ];
        $msg = $this->gpg->encryptSign(json_encode($challenge));

        $request = $request->withData('user_id', UuidFactory::uuid('user.id.ada'));
        $request = $request->withData('challenge', $msg);
        $result = $this->sut->authenticate($request);

        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_ExpiredToken()
    {
        $this->gpgSetup();
        $request = new ServerRequest();

        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $this->gpg->setSignKeyFromFingerprint($this->adaKeyId, '');
        $challenge = [
            'version' => '1.0.0',
            'domain' => Router::url('/', true),
            'verify_token' => UuidFactory::uuid(),
            'verify_token_expiry' => time() - 60,
        ];
        $msg = $this->gpg->encryptSign(json_encode($challenge));

        $request = $request->withData('user_id', UuidFactory::uuid('user.id.ada'));
        $request = $request->withData('challenge', $msg);
        $result = $this->sut->authenticate($request);

        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateError_WrongVerifyToken()
    {
        $this->gpgSetup();
        $request = new ServerRequest();

        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $this->gpg->setSignKeyFromFingerprint($this->adaKeyId, '');
        $challenge = [
            'version' => '1.0.0',
            'domain' => Router::url('/', true),
            'verify_token' => 'nope',
            'verify_token_expiry' => time() + 60,
        ];
        $msg = $this->gpg->encryptSign(json_encode($challenge));

        $request = $request->withData('user_id', UuidFactory::uuid('user.id.ada'));
        $request = $request->withData('challenge', $msg);
        $result = $this->sut->authenticate($request);

        $this->assertEquals(Result::FAILURE_CREDENTIALS_INVALID, $result->getStatus());
    }

    public function testGpgJwtAuthenticatorAuthenticateSuccess()
    {
        $this->gpgSetup();
        $request = new ServerRequest();

        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $this->gpg->setSignKeyFromFingerprint($this->adaKeyId, '');
        $userChallenge = [
            'version' => '1.0.0',
            'domain' => Router::url('/', true),
            'verify_token' => UuidFactory::uuid(),
            'verify_token_expiry' => time() + 60,
        ];
        $msg = $this->gpg->encryptSign(json_encode($userChallenge));

        $container = new Container();
        $container->add(JwtArmoredChallengeInterface::class, JwtArmoredChallengeService::class);
        $request = $request
            ->withData('user_id', UuidFactory::uuid('user.id.ada'))
            ->withData('challenge', $msg)
            ->withAttribute(ContainerInjectorMiddleware::CONTAINER_ATTRIBUTE, $container);
        $result = $this->sut->authenticate($request);

        $this->assertEquals(Result::SUCCESS, $result->getStatus());
        $data = $result->getData();
        $this->assertNotEmpty($data['user']);
        $this->assertNotEmpty($data['challenge']);

        // Assert user is part of results
        $user = $data['user'];
        $this->assertEquals(UuidFactory::uuid('user.id.ada'), $user['id']);
        $this->assertEquals('ada@passbolt.com', $user['username']);
        $this->assertEquals('Lovelace', $user['profile']['last_name']);
        $this->assertEquals('03F60E958F4CB29723ACDF761353B5B15D9B054F', $user['gpgkey']['fingerprint']);

        // Assert challenge is signed and can be decrypted
        $serverChallenge = $data['challenge'];
        $this->gpg->clearDecryptKeys();
        $this->gpg->setVerifyKeyFromFingerprint(Configure::read('passbolt.gpg.serverKey.fingerprint'));
        $this->gpg->setDecryptKeyFromFingerprint($user['gpgkey']['fingerprint'], '');
        $decryptedChallenge = $this->gpg->decrypt($serverChallenge, true);

        // Assert challenge content contains required info
        $this->assertNotEmpty($decryptedChallenge);
        $deserializedChallenge = json_decode($decryptedChallenge);
        $this->assertTextEquals(GpgJwtAuthenticator::PROTOCOL_VERSION, $deserializedChallenge->version);
        $this->assertTextEquals(Router::url('/', true), $deserializedChallenge->domain);
        $this->assertEquals($userChallenge['verify_token'], $deserializedChallenge->verify_token);
        $this->assertNotEmpty($deserializedChallenge->access_token);
        $this->assertNotEmpty($deserializedChallenge->refresh_token);
        $this->assertEventFired(GpgJwtAuthenticator::JWT_AUTHENTICATION_AFTER_IDENTIFY);
    }

    // ========================================================================
    // Assert checks
    // ========================================================================
    // Server fingerprint

    public function testGpgJwtAuthenticatorAssertServerFingerprint_EmptyError()
    {
        $this->expectException(InternalErrorException::class);
        $this->sut->assertServerFingerprint(null);
    }

    public function testGpgJwtAuthenticatorAssertServerFingerprint_NotFingerprintError()
    {
        $this->expectException(InternalErrorException::class);
        $this->sut->assertServerFingerprint('nope');
    }

    public function testGpgJwtAuthenticatorAssertServerFingerprint_Success()
    {
        $this->sut->assertServerFingerprint('E8FE388E385841B382B674ADB02DADCD9565E1B8');
        $this->assertTrue(true);
    }

    // Server passphrase

    public function testGpgJwtAuthenticatorAssertServerPassphrase_EmptyError()
    {
        $this->expectException(InternalErrorException::class);
        $this->sut->assertServerPassphrase(null);
    }

    public function testGpgJwtAuthenticatorAssertServerPassphrase_NotStringError()
    {
        $this->expectException(InternalErrorException::class);
        $this->sut->assertServerPassphrase([]);
    }

    public function testGpgJwtAuthenticatorAssertServerPassphrase_Success()
    {
        $this->sut->assertServerPassphrase('cofveve');
        $this->assertTrue(true);
    }

    // User id

    public function testGpgJwtAuthenticatorAssertUserId_EmptyError()
    {
        $this->expectException(BadRequestException::class);
        $this->sut->assertUserId(null);
    }

    public function testGpgJwtAuthenticatorAssertUserId_NotStringError()
    {
        $this->expectException(BadRequestException::class);
        $this->sut->assertUserId([]);
    }

    public function testGpgJwtAuthenticatorAssertServerPassphrase_NotUuid()
    {
        $this->expectException(BadRequestException::class);
        $this->sut->assertUserId('');
    }

    public function testGpgJwtAuthenticatorAssertServerPassphrase_NotUuid2()
    {
        $this->expectException(BadRequestException::class);
        $this->sut->assertUserId('test');
    }

    public function testGpgJwtAuthenticatorAssertUserId_Success()
    {
        $this->sut->assertUserId(UuidFactory::uuid());
        $this->assertTrue(true);
    }

    // Armored challenge

    public function testGpgJwtAuthenticatorAssertArmoredChallenge_EmptyError()
    {
        $this->expectException(BadRequestException::class);
        $this->sut->assertArmoredChallenge(null);
    }

    public function testGpgJwtAuthenticatorAssertArmoredChallenge_NotStringError()
    {
        $this->expectException(BadRequestException::class);
        $this->sut->assertArmoredChallenge([]);
    }

    public function testGpgJwtAuthenticatorAssertArmoredChallenge_NotOpenpgpMessageError()
    {
        $this->expectException(BadRequestException::class);
        $this->sut->setOpenPGPBackend();
        $this->sut->assertArmoredChallenge('test');
    }

    // Protocol version

    public function testGpgJwtAuthenticatorAssertVersion_EmptyError()
    {
        $this->expectException(\Exception::class);
        $this->sut->assertVersion(null);
    }

    public function testGpgJwtAuthenticatorAssertVersion_NotStringError()
    {
        $this->expectException(\Exception::class);
        $this->sut->assertVersion([]);
    }

    public function testGpgJwtAuthenticatorAssertVersion_NotSemverError()
    {
        $this->expectException(\Exception::class);
        $this->sut->assertVersion('test');
    }

    public function testGpgJwtAuthenticatorAssertVersion_Success()
    {
        $this->sut->assertVersion(GpgJwtAuthenticator::PROTOCOL_VERSION);
        $this->assertTrue(true);
    }

    // Domain

    public function testGpgJwtAuthenticatorAssertDomain_EmptyError()
    {
        $this->expectException(\Exception::class);
        $this->sut->assertDomain(null);
    }

    public function testGpgJwtAuthenticatorAssertDomain_NotStringError()
    {
        $this->expectException(\Exception::class);
        $this->sut->assertDomain([]);
    }

    public function testGpgJwtAuthenticatorAssertDomain_NotDomainError()
    {
        $this->expectException(\Exception::class);
        $this->sut->assertDomain('nope');
    }

    public function testGpgJwtAuthenticatorAssertDomain_WrongDomainError()
    {
        $this->expectException(\Exception::class);
        $this->sut->assertDomain('https://www.google.com');
    }

    public function testGpgJwtAuthenticatorAssertDomain_Success()
    {
        $this->sut->assertDomain(Router::url('/', true));
        $this->assertTrue(true);
    }
}
