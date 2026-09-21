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

namespace Passbolt\Sso\Utility\Http;

use Cake\Core\Configure;

/**
 * Resolves an SSO provider host and decides whether the server may connect to it.
 */
class SsoEgressGuard
{
    public const CONFIG_ENABLED = 'passbolt.security.sso.egress.enabled';
    public const CONFIG_BLOCK_LINK_LOCAL = 'passbolt.security.sso.egress.blockLinkLocal';
    public const CONFIG_BLOCK_PRIVATE_RANGES = 'passbolt.security.sso.egress.blockPrivateRanges';
    public const CONFIG_PRIVATE_RANGE_ALLOWED_IPS = 'passbolt.security.sso.egress.privateRangeAllowedIps';

    /**
     * Never bypassable: link-local and cloud metadata ranges. No real identity provider lives here.
     *
     * @var array<string>
     */
    private const LINK_LOCAL = [
        '169.254.0.0/16', // link-local + IPv4 cloud metadata (169.254.169.254)
        'fe80::/10', // link-local (IPv6)
        'fd00:ec2::254/128', // AWS IPv6 metadata
    ];

    /**
     * Bypassable via an exact allow-listed IP only.
     *
     * @var array<string>
     */
    private const PRIVATE_RANGES = [
        '0.0.0.0/8', // "this host" / unspecified - 0.0.0.0 routes to localhost
        '127.0.0.0/8', // loopback
        '::/128', // unspecified (IPv6)
        '::1/128', // loopback (IPv6)
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
        'fd00::/8', // unique local address
        '100.64.0.0/10', // carrier-grade NAT
        '2002::/16', // 6to4 - embeds an arbitrary IPv4 destination
    ];

    /**
     * IPv6 forms that carry an IPv4 address in their last 32 bits.
     *
     * @var array<string>
     */
    private const V4_EMBEDDING_PREFIXES = [
        '::ffff:0:0/96', // IPv4-mapped IPv6
        '64:ff9b::/96', // NAT64 well-known prefix
    ];

    /**
     * Resolve the given host and return the reason it must be blocked, or null if allowed.
     *
     * @param string $host Hostname or IP literal from the URL being fetched.
     * @return string|null Human-readable block reason, or null when the host is allowed.
     */
    public function getBlockReason(string $host): ?string
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $ips = $this->resolve($host);
        if ($ips === []) {
            // Could not resolve: let the HTTP layer fail naturally rather than blocking.
            return null;
        }

        $blockLinkLocal = (bool)Configure::read(self::CONFIG_BLOCK_LINK_LOCAL, true);
        $blockPrivate = (bool)Configure::read(self::CONFIG_BLOCK_PRIVATE_RANGES, true);
        $allowedIps = $this->getAllowedIps();

        foreach ($ips as $ip) {
            // Link-local and metadata: hard block, the allow-list is ignored.
            if ($blockLinkLocal && IpRangeMatcher::inAnyRange($ip, self::LINK_LOCAL)) {
                return __('The provider address resolves to a blocked link-local or metadata range ({0}).', $ip);
            }
            // Private ranges: blocked unless the resolved IP exactly matches an allow-listed IP.
            if (
                $blockPrivate
                && IpRangeMatcher::inAnyRange($ip, self::PRIVATE_RANGES)
                && !in_array($ip, $allowedIps, true)
            ) {
                return __('The provider address resolves to a blocked private range ({0}).', $ip);
            }
        }

        return null;
    }

    /**
     * Whether the egress guard is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        // disabled by default
        return (bool)Configure::read(self::CONFIG_ENABLED, false);
    }

    /**
     * Resolve a host to the list of IP literals it points to.
     *
     * @param string $host Hostname or IP literal.
     * @return array<string> Normalised IP literals.
     */
    private function resolve(string $host): array
    {
        if (filter_var($host, FILTER_VALIDATE_IP) !== false) {
            return [$this->normalizeIp($host)];
        }

        $ips = [];
        foreach ($this->dnsRecords($host, DNS_A) as $record) {
            if (isset($record['ip']) && is_string($record['ip'])) {
                $ips[] = $record['ip'];
            }
        }
        foreach ($this->dnsRecords($host, DNS_AAAA) as $record) {
            if (isset($record['ipv6']) && is_string($record['ipv6'])) {
                $ips[] = $record['ipv6'];
            }
        }

        return array_map(
            fn(string $ip): string => $this->normalizeIp($ip),
            array_values(array_unique($ips)),
        );
    }

    /**
     * Normalise an IPv4-in-IPv6 address (IPv4-mapped or NAT64) to its embedded IPv4.
     *
     * @param string $ip IP literal.
     * @return string
     */
    private function normalizeIp(string $ip): string
    {
        if (!IpRangeMatcher::inAnyRange($ip, self::V4_EMBEDDING_PREFIXES)) {
            return $ip;
        }
        $packed = inet_pton($ip);
        if ($packed === false) {
            return $ip;
        }
        // The embedded IPv4 lives in the last 4 bytes of the 16-byte address.
        $embedded = inet_ntop(substr($packed, 12, 4));

        return $embedded === false ? $ip : $embedded;
    }

    /**
     * Fetch DNS records of the given type for a host.
     *
     * @param string $host Hostname.
     * @param int $type One of the DNS_* constants.
     * @return array<array<string, mixed>>
     */
    private function dnsRecords(string $host, int $type): array
    {
        $records = @dns_get_record($host, $type); // phpcs:ignore

        return is_array($records) ? $records : [];
    }

    /**
     * Exact IPs allowed to bypass the private-range block.
     *
     * @return array<string>
     */
    private function getAllowedIps(): array
    {
        $configured = Configure::read(self::CONFIG_PRIVATE_RANGE_ALLOWED_IPS);
        if (is_string($configured)) {
            $configured = explode(',', $configured);
        }
        if (!is_array($configured)) {
            return [];
        }

        $allowed = [];
        foreach ($configured as $ip) {
            if (!is_string($ip)) {
                continue;
            }
            $ip = trim($ip);
            if ($ip !== '' && !str_contains($ip, '/') && filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                $allowed[] = $ip;
            }
        }

        return $allowed;
    }
}
