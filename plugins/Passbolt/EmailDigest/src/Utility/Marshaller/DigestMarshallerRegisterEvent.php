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

use Cake\Event\Event;
use InvalidArgumentException;

/**
 * @method DigestMarshallerPool getSubject()
 *
 * Event triggered to add new marshaller. It contains the DigestMarshallerPool, so it can be manipulated to add
 * new digest marshaller at runtime in the pool.
 */
class DigestMarshallerRegisterEvent extends Event
{
    /**
     * Name of the event dispatched when registration of marshallers is run.
     */
    const EVENT_NAME = 'email_digest.marshallers.register';

    /**
     * @param string $name Name of the event
     * @param null $subject Subject of the dispatched event
     * @param null $data Data for the event
     */
    public function __construct($name, $subject = null, $data = null)
    {
        if (!$subject instanceof DigestMarshallerPool) {
            throw new InvalidArgumentException('`subject` must be an instance of ' . DigestMarshallerPool::class);
        }

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param DigestMarshallerPool $digestMarshallerPool Digest Marshaller Pool
     * @return DigestMarshallerRegisterEvent
     */
    public static function create(DigestMarshallerPool $digestMarshallerPool)
    {
        return new static(static::EVENT_NAME, $digestMarshallerPool);
    }

    /**
     * @return DigestMarshallerPool
     */
    public function getEmailDigestMarshallerPool()
    {
        return $this->getSubject();
    }
}
