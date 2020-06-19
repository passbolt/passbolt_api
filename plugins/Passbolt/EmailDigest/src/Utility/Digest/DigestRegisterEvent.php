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
 * @since         2.13.0
 */
namespace Passbolt\EmailDigest\Utility\Digest;

use Cake\Event\Event;
use InvalidArgumentException;

/**
 * @method DigestsPool getSubject()
 *
 * Event triggered to add new digest. It contains the DigestsPool, so it can be manipulated to add
 * new digest at runtime in the digest pool.
 */
class DigestRegisterEvent extends Event
{
    /**
     * Name of the event dispatched when registration of digests is run.
     */
    const EVENT_NAME = 'email_digest.digests.register';

    /**
     * @param string $name Name of the event
     * @param null $subject Subject of the dispatched event
     * @param null $data Data for the event
     */
    public function __construct($name, $subject = null, $data = null)
    {
        if (!$subject instanceof DigestsPool) {
            throw new InvalidArgumentException('`subject` must be an instance of ' . DigestsPool::class);
        }

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param DigestsPool $digestsPool Digests Pool
     * @return DigestRegisterEvent
     */
    public static function create(DigestsPool $digestsPool)
    {
        return new static(static::EVENT_NAME, $digestsPool);
    }

    /**
     * @return DigestsPool
     */
    public function getEmailDigestsPool()
    {
        return $this->getSubject();
    }
}
