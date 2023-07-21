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

namespace Passbolt\JwtAuthentication\Test\TestCase\Service\AccessToken;

use App\Utility\Filesystem\DirectoryUtility;
use App\Utility\UuidFactory;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;

/**
 * @covers \Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService
 */
class JwtKeyPairServiceTest extends TestCase
{
    /**
     * Temporary jwt directory
     *
     * @var string
     */
    protected $jwtDir;

    /**
     * @var JwtKeyPairService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->jwtDir = TMP . 'jwt' . rand(1, 10000);
        DirectoryUtility::removeRecursively(TMP . 'jwt');
        mkdir($this->jwtDir);
        $this->service = new JwtKeyPairService(
            (new JwtTokenCreateService())->setKeyPath($this->getSecretFilePath()),
            (new JwksGetService())->setKeyPath($this->getPublicFilePath()),
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
        DirectoryUtility::removeRecursively($this->jwtDir);
        unset($this->service);
    }

    public function testJwtKeyPairService_Valid()
    {
        $this->service->createKeyPair();
        $this->assertTrue(file_exists($this->getPublicFilePath()));
        $this->assertTrue(file_exists($this->getSecretFilePath()));
        $uuid = UuidFactory::uuid();
        $token = $this->service->validateKeyPair($uuid);
        $this->assertSame($uuid, $token->sub);
        $this->assertSame(Router::url('/', true), $token->iss);
    }

    public function testJwtKeyPairService_KeyExist()
    {
        $this->service->createKeyPair();
        $publicKey1 = $this->service->readPublicKey();
        $secretKey1 = $this->service->readSecretKey();

        $this->service->createKeyPair(true); // Force: no exceptions
        $publicKey2 = $this->service->readPublicKey();
        $secretKey2 = $this->service->readSecretKey();
        $this->assertTextNotEquals($publicKey1, $publicKey2);
        $this->assertTextNotEquals($secretKey1, $secretKey2);

        $this->service->createKeyPair(); // No force: no exception, keys did not change
        $publicKey3 = $this->service->readPublicKey();
        $secretKey3 = $this->service->readSecretKey();
        $this->assertTextEquals($publicKey2, $publicKey3);
        $this->assertTextEquals($secretKey2, $secretKey3);
    }

    public function testJwtKeyPairService_NotValid()
    {
        $this->service->createKeyPair();

        $secretFileContentCorrupted = 'blah' . file_get_contents($this->getSecretFilePath());
        file_put_contents($this->getSecretFilePath(), $secretFileContentCorrupted);
        $this->assertTrue(file_exists($this->getPublicFilePath()));
        $this->assertTrue(file_exists($this->getSecretFilePath()));
        $this->expectException(InvalidJwtKeyPairException::class);

        // suppress warning
        $errorReportingBackup = error_reporting();
        error_reporting(0);

        $this->service->validateKeyPair();

        // reinstate errors
        error_reporting($errorReportingBackup);
    }

    public function testJwtKeyPairService_Key_Length_Too_Short()
    {
        $this->service
            ->setKeyLength(4095)
            ->createKeyPair(true);

        $this->expectException(InvalidJwtKeyPairException::class);
        $this->expectExceptionMessage(
            'The JWT private key should be at least ' . JwtTokenCreateService::JWT_KEY_LENGTH . ' bytes long.'
        );
        $this->service->validateKeyPair();
    }

    public function testJwtKeyPairService_getCreateJwtKeysCommand()
    {
        $this->assertSame(
            ROOT . DS . 'bin/cake passbolt create_jwt_keys',
            $this->service->getCreateJwtKeysCommand()
        );
    }

    private function getPublicFilePath(): string
    {
        return $this->jwtDir . DS . 'jwt.pem';
    }

    private function getSecretFilePath(): string
    {
        return $this->jwtDir . DS . 'jwt.key';
    }
}
