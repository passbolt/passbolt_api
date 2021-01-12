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
 * @since         3.0.0
 */
namespace Passbolt\EmailDigest\Utility\Digest;

/**
 * DigestsPool handle a collection of Digest instances which are ordered by priority.
 *
 * @see DigestInterface
 *
 * It is used by the DigestsCollection and other components to retrieve and add digests digests dynamically.
 *
 * @see DigestsCollection
 * Lowest priority is equal to DigestsPool::LOWEST_PRIORITY
 */
class DigestsPool
{
    public const LOWEST_PRIORITY = -1;

    /**
     * @var static
     */
    private static $instance;

    /**
     * @var \Passbolt\EmailDigest\Utility\Digest\DigestInterface[]
     */
    private $digests = [];

    /**
     * Access to constructor is restricted because it is a singleton.
     */
    private function __construct()
    {
    }

    /**
     * Return a singleton of the DigestsPool
     *
     * @return \Passbolt\EmailDigest\Utility\Digest\DigestsPool
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Add a digest instance to the pool of digests with an optional priority.
     *
     * @param \Passbolt\EmailDigest\Utility\Digest\DigestInterface $digest Digest digest instance
     * @param int $priority An integer equals to the priority level of the digest. Higher number is more prior.
     * @return $this
     */
    public function addDigest(DigestInterface $digest, int $priority = self::LOWEST_PRIORITY)
    {
        $this->digests[] = [
            'digest' => $digest,
            'priority' => $priority,
        ];

        return $this;
    }

    /**
     * Return a collection of email digests ordered by priority.
     *
     * @return \Passbolt\EmailDigest\Utility\Digest\DigestInterface[]
     */
    public function getDigests()
    {
        $digests = $this->digests;

        // Sort the digests by priority in ascendant order.
        usort($digests, function ($digestA, $digestB) {
            if ($digestA['priority'] == $digestB['priority']) {
                return 0;
            }
            // -1 if priority of digest A is lower than the priority of digest B
            // 0 if priority of digest A is equal to priority of digest B
            // 1 if priority of digest A is greater than the priority of digest B
            return $digestA['priority'] < $digestB['priority'] ? -1 : 1;
        });

        // Then we reverse the array to make it in descendant order
        // since we want to start with the digest with the highest priority, not the lowest.
        $digests = array_reverse($digests);

        return array_column($digests, 'digest');
    }
}
