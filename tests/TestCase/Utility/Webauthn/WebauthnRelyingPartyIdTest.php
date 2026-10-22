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
namespace App\Test\TestCase\Utility\Webauthn;

use App\Error\Exception\WebauthnRelyingPartyIdInvalidException;
use App\Utility\Webauthn\WebauthnRelyingPartyId;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Utility\Webauthn\WebauthnRelyingPartyId
 */
class WebauthnRelyingPartyIdTest extends TestCase
{
    /**
     * @return array
     */
    public static function validFullBaseUrlProvider(): array
    {
        return [
            ['https://passbolt.example.com', 'passbolt.example.com'],
            ['https://passbolt.example.com:8443/subpath', 'passbolt.example.com'],
            // domain starting with digits
            ['https://10.example.com', '10.example.com'],
            // localhost is accepted
            ['https://localhost:8443', 'localhost'],
        ];
    }

    /**
     * @dataProvider validFullBaseUrlProvider
     * @param string $fullBaseUrl Full base URL to test.
     * @param string $expected Expected result.
     */
    public function testWebauthnRelyingPartyId_GetOrFail_Success(string $fullBaseUrl, string $expected): void
    {
        Configure::write('App.fullBaseUrl', $fullBaseUrl);
        $result = WebauthnRelyingPartyId::getOrFail();
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    public static function ipAddressFullBaseUrlProvider(): array
    {
        return [
            ['https://192.168.1.10'],
            ['https://192.168.1.10:8443/subpath'],
            ['https://[::1]'],
            ['https://[2001:db8::1]:8443'],
        ];
    }

    /**
     * @dataProvider ipAddressFullBaseUrlProvider
     * @param string $fullBaseUrl Full base URL to test.
     */
    public function testWebauthnRelyingPartyId_GetOrFail_Error_IpAddress(string $fullBaseUrl): void
    {
        Configure::write('App.fullBaseUrl', $fullBaseUrl);

        $this->expectException(WebauthnRelyingPartyIdInvalidException::class);
        $this->expectExceptionMessage(
            'Passkeys require Passbolt to be reached through a domain name, not an IP address.',
        );
        WebauthnRelyingPartyId::getOrFail();
    }

    /**
     * @return array
     */
    public static function malformedDomainFullBaseUrlProvider(): array
    {
        return [
            ['https://my_host.example.com'],
            ['https://-bad.example.com'],
            ['https://a..b.com'],
        ];
    }

    /**
     * @dataProvider malformedDomainFullBaseUrlProvider
     * @param string $fullBaseUrl The configured full base URL
     */
    public function testWebauthnRelyingPartyId_GetOrFail_Error_MalformedDomain(string $fullBaseUrl): void
    {
        Configure::write('App.fullBaseUrl', $fullBaseUrl);

        $this->expectException(WebauthnRelyingPartyIdInvalidException::class);
        $this->expectExceptionMessage('Passkeys require Passbolt to be reached through a valid domain name.');
        WebauthnRelyingPartyId::getOrFail();
    }

    public function testWebauthnRelyingPartyId_GetOrFail_Error_FullBaseUrlNotConfigured(): void
    {
        Configure::write('App.fullBaseUrl', '');

        $this->expectException(WebauthnRelyingPartyIdInvalidException::class);
        $this->expectExceptionMessage('Passkeys require a valid full base URL to be configured.');
        WebauthnRelyingPartyId::getOrFail();
    }
}
