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

namespace Passbolt\Sso\Test\TestCase\Utility\Http;

use Cake\TestSuite\TestCase;
use Passbolt\Sso\Utility\Http\HostValidator;

/**
 * @covers \Passbolt\Sso\Utility\Http\HostValidator
 */
class HostValidatorTest extends TestCase
{
    /**
     * @return array
     */
    public static function ipv6WithBracketsValuesProvider(): array
    {
        return [
            ['[::1]', '::1'],
            ['[fd00:ec2::254]', 'fd00:ec2::254'],
            ['[::ffff:127.0.0.1]', '::ffff:127.0.0.1'],
            ['::1', '::1'],
            ['idp.example.com', 'idp.example.com'],
            ['127.0.0.1', '127.0.0.1'],
        ];
    }

    /**
     * @dataProvider ipv6WithBracketsValuesProvider
     * @param string $host Host to strip.
     * @param string $expected Expected result.
     * @return void
     */
    public function testHostValidator_StripIpv6Brackets_Success(string $host, string $expected): void
    {
        $this->assertSame($expected, HostValidator::stripIpv6Brackets($host));
    }

    /**
     * @return array
     */
    public static function supportedIpLiteralProvider(): array
    {
        return [
            ['127.0.0.1'],
            ['8.8.8.8'],
            ['::1'],
            ['fd00:ec2::254'],
            ['::ffff:169.254.169.254'],
        ];
    }

    /**
     * @dataProvider supportedIpLiteralProvider
     * @param string $host Host to test.
     * @return void
     */
    public function testHostValidator_IsIpLiteral_Success(string $host): void
    {
        $this->assertTrue(HostValidator::isIpLiteral($host));
        $this->assertFalse(HostValidator::isHostName($host));
    }

    /**
     * @return array
     */
    public static function supportedHostNameProvider(): array
    {
        return [
            ['idp.example.com'],
            ['auth.pingone.com'],
            ['sso.internal.example'],
            ['my-idp-01.corp.example.com'],
            ['my_idp.example.com'],
            ['xn--bcher-kva.example.com'],
            ['localhost'],
            ['idp'],
        ];
    }

    /**
     * @dataProvider supportedHostNameProvider
     * @param string $host Host to test.
     * @return void
     */
    public function testHostValidator_IsHostName_Success(string $host): void
    {
        $this->assertTrue(HostValidator::isHostName($host));
        $this->assertTrue(HostValidator::isSupported($host));
    }

    /**
     * @return array
     */
    public static function unsupportedValuesProvider(): array
    {
        return [
            'decimal' => ['2130706433'],
            'decimal metadata' => ['2852039166'],
            'dotted octal' => ['0177.0.0.1'],
            'flat octal' => ['017700000001'],
            'flat hexadecimal' => ['0x7f000001'],
            'dotted hexadecimal' => ['0x7f.0x0.0x0.0x1'],
            'two-part short form' => ['127.1'],
            'three-part short form' => ['127.0.1'],
            'bare zero' => ['0'],
            'padded octet' => ['127.000.000.001'],
            'trailing dot' => ['127.0.0.1.'],
            'leading hyphen' => ['-bad.example.com'],
            'trailing hyphen' => ['bad-.example.com'],
            'empty' => [''],
            'bracketed ipv6' => ['[::1]'],
        ];
    }

    /**
     * @dataProvider unsupportedValuesProvider
     * @param string $host Host to test.
     * @return void
     */
    public function testHostValidator_IsSupported_Error(string $host): void
    {
        $this->assertFalse(HostValidator::isSupported($host));
    }
}
