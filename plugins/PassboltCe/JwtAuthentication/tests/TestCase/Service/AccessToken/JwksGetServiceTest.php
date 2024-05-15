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
 * @since         4.8.0
 */

namespace Passbolt\JwtAuthentication\Test\TestCase\Service\AccessToken;

use App\Utility\Filesystem\DirectoryUtility;
use Cake\TestSuite\TestCase;
use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;

class JwksGetServiceTest extends TestCase
{
    protected string $jwtDir;
    protected JwksGetService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->jwtDir = TMP . 'jwt' . rand(1, 10000);
        DirectoryUtility::removeRecursively(TMP . 'jwt');
        mkdir($this->jwtDir);
        $this->service = new JwksGetService();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        DirectoryUtility::removeRecursively($this->jwtDir);
        unset($this->service);
    }

    public function testJwksGetService_Invalid_Public_Key()
    {
        $publicKeyContent = 'foo';
        $fileName = $this->jwtDir . DS . 'jwt.pem';
        $this->service->setKeyPath($fileName);
        file_put_contents($fileName, $publicKeyContent);

        $this->expectException(InvalidJwtKeyPairException::class);
        $this->expectExceptionMessage('The JWT public key could not be extracted.');
        $this->service->getPublicKey();
    }
}
