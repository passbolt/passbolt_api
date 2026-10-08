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
namespace Passbolt\JwtAuthentication\Test\TestCase\Service\AccessToken;

use Cake\TestSuite\TestCase;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtEncodeService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;

/**
 * @covers \Passbolt\JwtAuthentication\Service\AccessToken\JwtEncodeService
 */
class JwtEncodeServiceTest extends TestCase
{
    public function testJwtEncodeService_Encode_ReturnsJwtDecodableWithInstancePublicKey(): void
    {
        $claims = ['sub' => 'foo', 'exp' => time() + 60];

        $jwt = (new JwtEncodeService())->encode($claims);

        $decoded = JWT::decode(
            $jwt,
            new Key((new JwksGetService())->getRawPublicKey(), JwtTokenCreateService::JWT_ALG),
        );
        $this->assertSame('foo', $decoded->sub);
    }

    public function testJwtEncodeService_Encode_UsesConfiguredJwtAlgHeader(): void
    {
        $jwt = (new JwtEncodeService())->encode(['sub' => 'bar', 'exp' => time() + 60]);

        [$header] = explode('.', $jwt, 2);
        $headerDecoded = json_decode(base64_decode(strtr($header, '-_', '+/')), true);
        $this->assertSame(JwtTokenCreateService::JWT_ALG, $headerDecoded['alg']);
    }

    public function testJwtEncodeService_Encode_HonorsSetKeyPathOverride(): void
    {
        $unreadable = TMP . 'nonexistent-' . uniqid() . '.key';

        $service = (new JwtEncodeService())->setKeyPath($unreadable);

        $this->expectException(InvalidJwtKeyPairException::class);
        $service->encode(['sub' => 'baz', 'exp' => time() + 60]);
    }

    public function testJwtEncodeService_Encode_DefaultsToJwtTokenCreateServiceKeyPath(): void
    {
        $service = new JwtEncodeService();

        $this->assertSame(JwtTokenCreateService::JWT_SECRET_KEY_PATH, $service->getKeyPath());
    }
}
