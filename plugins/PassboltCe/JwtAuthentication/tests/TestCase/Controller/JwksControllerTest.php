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
namespace Passbolt\JwtAuthentication\Test\TestCase\Controller;

use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthenticationIntegrationTestCase;

class JwksControllerTest extends JwtAuthenticationIntegrationTestCase
{
    public function testAuthJwksControllerRsaSuccess()
    {
        $this->getJson('/auth/jwt/rsa.json');
        $this->assertResponseOk();
        $responseKeys = $this->_responseJsonBody->keydata;
        $this->assertTextContains('-----BEGIN PUBLIC KEY-----', $responseKeys);
    }

    public function testAuthJwksControllerJwksSuccess()
    {
        $this->get('/auth/jwt/jwks.json');
        $this->_responseJsonBody = json_decode($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertCount(1, $this->_responseJsonBody->keys);
        $responseKey = $this->_responseJsonBody->keys[0];
        $this->assertSame('RSA', $responseKey->kty);
        $this->assertSame('RS256', $responseKey->alg);
    }

    public function testAuthJwksControllerJwksRedirect()
    {
        $this->get('/.well-known/jwks.json');
        $this->assertResponseCode(301);
    }

    public function testAuthJwksControllerJwks_Key_Missing()
    {
        $this->mockService(JwksGetService::class, function () {
            return (new JwksGetService())->setKeyPath('foo');
        });
        $this->getJson('/auth/jwt/jwks.json');
        $this->assertInternalError('The key pair for JWT Authentication is not complete.');
        $this->assertSame('The key pair for JWT Authentication is not complete.', $this->_responseJsonHeader->message);
    }

    public function testAuthJwksControllerRsa_Key_Missing()
    {
        $this->mockService(JwksGetService::class, function () {
            return (new JwksGetService())->setKeyPath('foo');
        });
        $this->getJson('/auth/jwt/rsa.json');
        $this->assertInternalError('The key pair for JWT Authentication is not complete.');
        $this->assertSame('The key pair for JWT Authentication is not complete.', $this->_responseJsonHeader->message);
    }
}
