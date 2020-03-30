<?php
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
 * @since         2.14.0
 */

namespace Passbolt\EmailDigest\Utility\Marshaller;

use Passbolt\EmailDigest\Utility\Marshaller\Type\PoolDigestMarshaller;

/**
 * DigestMarshallerPool handle a collection of DigestMarshaller instances which are ordered by priority.
 * @see DigestMarshallerInterface
 *
 * It is used by the PoolDigestMarshaller and other components to retrieve and add digests marshallers dynamically.
 *
 * @see PoolDigestMarshaller
 * Lowest priority is equal to DigestMarshallerPool::LOWEST_PRIORITY
 */
class DigestMarshallerPool
{
    const LOWEST_PRIORITY = -1;

    /**
     * @var static
     */
    private static $instance;

    /**
     * @var DigestMarshallerInterface[]
     */
    private $digestMarshallers = [];

    /**
     * Access to constructor is restricted because it is a singleton.
     */
    private function __construct()
    {
    }

    /**
     * Return a singleton of the DigestMarshallerPool
     * @return DigestMarshallerPool
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Add a digest marshaller instance to the pool of digest marshaller with an optional priority.
     * @param DigestMarshallerInterface $digestMarshaller Digest marshaller instance
     * @param int $priority An integer equals to the priority level of the marshaller. Higher number is more prior.
     * @return $this
     */
    public function addDigestMarshaller(DigestMarshallerInterface $digestMarshaller, int $priority = self::LOWEST_PRIORITY)
    {
        $this->digestMarshallers[] = [
            'marshaller' => $digestMarshaller,
            'priority' => $priority,
        ];

        return $this;
    }

    /**
     * Return a collection of email digest marshallers ordered by priority.
     * @return DigestMarshallerInterface[]
     */
    public function getDigestMarshallers()
    {
        $marshallers = $this->digestMarshallers;

        // Sort the marshallers by priority in ascendant order.
        usort($marshallers, function ($marshallerA, $marshallerB) {
            if ($marshallerA['priority'] == $marshallerB['priority']) {
                return 0;
            }
            // -1 if priority of marshaller A is lower than the priority of marshaller B
            // 0 if priority of marshaller A is equal to priority of marshaller B
            // 1 if priority of marshaller A is greater than the priority of marshaller B
            return ($marshallerA['priority'] < $marshallerB['priority']) ? -1 : 1;
        });

        // Then we reverse the array to make it in descendant order
        // since we want to start with the marshaller with the highest priority, not the lowest.
        $marshallers = array_reverse($marshallers);

        return array_column($marshallers, 'marshaller');
    }
}
