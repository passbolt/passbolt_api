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

namespace Passbolt\Sso\Test\TestCase\Utility\Http;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\Sso\Utility\Http\SsoEgressGuard;

/**
 * @covers \Passbolt\Sso\Utility\Http\SsoEgressGuard
 */
class SsoEgressGuardTest extends TestCase
{
    /**
     * @param array $config Egress config overrides.
     * @return void
     */
    private function configureEgress(array $config = []): void
    {
        Configure::write('passbolt.security.sso.egress', array_merge([
            'enabled' => true,
            'blockLinkLocal' => true,
            'blockPrivateRanges' => true,
            'privateRangeAllowedIps' => null,
        ], $config));
    }

    public function testSsoEgressGuard_GetBlockReason_Success_PublicIpAllowed(): void
    {
        $this->configureEgress();
        $this->assertNull((new SsoEgressGuard())->getBlockReason('8.8.8.8'));
    }

    public function testSsoEgressGuard_GetBlockReason_Error_LoopbackBlocked(): void
    {
        $this->configureEgress();
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('127.0.0.1'));
    }

    public function testSsoEgressGuard_GetBlockReason_Error_MetadataBlocked(): void
    {
        $this->configureEgress();
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('169.254.169.254'));
    }

    public function testSsoEgressGuard_GetBlockReason_Error_UnspecifiedAddressBlocked(): void
    {
        $this->configureEgress();
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('0.0.0.0'));
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('::'));
    }

    public function testSsoEgressGuard_GetBlockReason_Error_Ipv4MappedLoopbackBlocked(): void
    {
        // ::ffff:127.0.0.1 is normalised to 127.0.0.1 and blocked as loopback.
        $this->configureEgress();
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('::ffff:127.0.0.1'));
    }

    public function testSsoEgressGuard_GetBlockReason_Error_Ipv4MappedMetadataBlocked(): void
    {
        // Normalised to 169.254.169.254 and blocked as metadata (never bypassable).
        $this->configureEgress();
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('::ffff:169.254.169.254'));
    }

    public function testSsoEgressGuard_GetBlockReason_Success_Ipv4MappedPublicAllowed(): void
    {
        // Normalised to 8.8.8.8, a public address, so it stays allowed.
        $this->configureEgress();
        $this->assertNull((new SsoEgressGuard())->getBlockReason('::ffff:8.8.8.8'));
    }

    public function testSsoEgressGuard_GetBlockReason_Error_Nat64LoopbackBlocked(): void
    {
        // 64:ff9b::7f00:1 embeds 127.0.0.1 in its last 32 bits.
        $this->configureEgress();
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('64:ff9b::7f00:1'));
    }

    public function testSsoEgressGuard_GetBlockReason_Error_SixToFourBlocked(): void
    {
        $this->configureEgress();
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('2002:a00:1::'));
    }

    public function testSsoEgressGuard_GetBlockReason_Success_GuardDisabledAllowsEverything(): void
    {
        $this->configureEgress(['enabled' => false]);
        $this->assertNull((new SsoEgressGuard())->getBlockReason('127.0.0.1'));
    }

    public function testSsoEgressGuard_GetBlockReason_Success_PrivateRangesCategoryDisabled(): void
    {
        $this->configureEgress(['blockPrivateRanges' => false]);
        $this->assertNull((new SsoEgressGuard())->getBlockReason('10.1.2.3'));
    }

    public function testSsoEgressGuard_GetBlockReason_Success_AllowListedPrivateIpBypassed(): void
    {
        $this->configureEgress(['privateRangeAllowedIps' => ['10.10.5.20']]);
        $this->assertNull((new SsoEgressGuard())->getBlockReason('10.10.5.20'));
        // A different private IP not on the allow-list is still blocked.
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('10.10.5.21'));
    }

    /**
     * @return array
     */
    public static function ssoEgressGuardCommaSeparatedAllowListProvider(): array
    {
        return [
            // Single space after the comma.
            ['10.10.5.20, 10.10.5.21', '10.10.5.20', true],
            ['10.10.5.20, 10.10.5.21', '10.10.5.21', true],
            ['10.10.5.20, 10.10.5.21', '10.10.5.22', false],
            // No spaces around the comma.
            ['10.10.5.21,10.10.5.22', '10.10.5.21', true],
            ['10.10.5.21,10.10.5.22', '10.10.5.22', true],
            // Unusual / multiple spaces mixed with no-space entries.
            ['10.10.5.21,   10.10.5.22,10.10.5.23', '10.10.5.21', true],
            ['10.10.5.21,   10.10.5.22,10.10.5.23', '10.10.5.22', true],
            ['10.10.5.21,   10.10.5.22,10.10.5.23', '10.10.5.23', true],
            ['10.10.5.21,   10.10.5.22,10.10.5.23', '10.10.5.24', false],
        ];
    }

    /**
     * @dataProvider ssoEgressGuardCommaSeparatedAllowListProvider
     * @param string $allowList Comma-separated allow-list config value.
     * @param string $ip IP to check.
     * @param bool $expectedAllowed If IP should be allowed.
     * @return void
     */
    public function testSsoEgressGuard_GetBlockReason_Success_AllowListParsesCommaSeparatedString(
        string $allowList,
        string $ip,
        bool $expectedAllowed,
    ): void {
        $this->configureEgress(['privateRangeAllowedIps' => $allowList]);
        $reason = (new SsoEgressGuard())->getBlockReason($ip);
        $this->assertSame($expectedAllowed, $reason === null);
    }

    public function testSsoEgressGuard_GetBlockReason_Error_LinkLocalNeverBypassableByAllowList(): void
    {
        $this->configureEgress(['privateRangeAllowedIps' => ['169.254.169.254']]);
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('169.254.169.254'));
    }

    public function testSsoEgressGuard_GetBlockReason_Error_AllowListEntryWithSlashRejected(): void
    {
        $this->configureEgress(['privateRangeAllowedIps' => ['10.0.0.0/8']]);
        $this->assertNotNull((new SsoEgressGuard())->getBlockReason('10.1.2.3'));
    }
}
