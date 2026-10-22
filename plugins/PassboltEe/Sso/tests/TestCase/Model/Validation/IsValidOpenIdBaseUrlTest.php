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
 * @since         5.15.0
 */

namespace Passbolt\Sso\Test\TestCase\Model\Validation;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\Sso\Model\Validation\IsValidOpenIdBaseUrl;

/**
 * @covers \Passbolt\Sso\Model\Validation\IsValidOpenIdBaseUrl
 */
class IsValidOpenIdBaseUrlTest extends TestCase
{
    /**
     * @return array
     */
    public static function ruleProvider(): array
    {
        return [
            // Valid public URLs.
            ['https://accounts.google.com', true],
            ['https://login.microsoftonline.com/common', true],
            ['https://8.8.8.8', true],
            // Hostnames pass at config time; internal resolution is caught at connect time.
            ['https://sso.internal.example', true],
            // Invalid scheme.
            ['http://accounts.google.com', false],
            ['ftp://accounts.google.com', false],
            // Internal IP literals are rejected at config time when the guard is enabled.
            ['https://127.0.0.1', false],
            ['https://10.1.2.3', false],
            ['https://169.254.169.254', false],
            // Bracketed IPv6 literals
            ['https://[::1]', false],
            ['https://[::ffff:127.0.0.1]', false],
            // Non-standard IPv4
            ['https://2130706433', false],
            ['https://2852039166', false],
            ['https://0177.0.0.1', false],
            ['https://0x7f000001', false],
            ['https://127.1', false],
            ['https://0', false],
            ['https://127.0.0.1.', false],
            // Non-string / malformed values.
            [null, false],
            [42, false],
            ['https://', false],
        ];
    }

    /**
     * @dataProvider ruleProvider
     * @param mixed $value Value to validate.
     * @param bool $expected Expected result.
     * @return void
     */
    public function testIsValidOpenIdBaseUrl_Rule(mixed $value, bool $expected): void
    {
        Configure::write('passbolt.security.sso.egress', [
            'enabled' => true,
            'blockLinkLocal' => true,
            'blockPrivateRanges' => true,
            'privateRangeAllowedIps' => null,
        ]);
        $result = (new IsValidOpenIdBaseUrl())->rule($value, null);
        $this->assertSame($expected, $result);
    }

    public function testIsValidOpenIdBaseUrl_Rule_GuardDisabledDefaultAcceptsInternalIp(): void
    {
        Configure::write('passbolt.security.sso.egress', ['enabled' => false]);

        $rule = new IsValidOpenIdBaseUrl();
        $this->assertTrue($rule->rule('https://127.0.0.1', null));
        $this->assertTrue($rule->rule('https://10.1.2.3', null));
        // A malformed value is still rejected regardless of the guard state.
        $this->assertFalse($rule->rule('http://accounts.google.com', null));
    }
}
