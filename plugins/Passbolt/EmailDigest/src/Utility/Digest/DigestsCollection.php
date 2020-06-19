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
 * @since         3.0.0
 */

namespace Passbolt\EmailDigest\Utility\Digest;

use Cake\ORM\Entity;
use Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;

/**
 * Create emails from several other digests.
 * This collection is stored in the DigestPool.
 *
 * When building an email digest, the first digest returned by the DigestsPool which can group
 * the given email will be used to build an digest email with this email.
 */
class DigestsCollection extends AbstractDigest implements DigestInterface
{
    /**
     * @var DigestsPool
     */
    private $digestsPool;

    /**
     * @param DigestsPool $digestsPool pool of Digests
     */
    public function __construct(DigestsPool $digestsPool = null)
    {
        $this->digestsPool = $digestsPool ?? DigestsPool::getInstance();
    }

    /**
     * Foreach each email entity, it goes through each email digests,
     * check if it can digest the email entity, if yes add emails data to it.
     * The first digest to be picked in the list, if can be used, will be the first and ONLY one digest served for the email.
     * @return EmailDigestInterface[]
     */
    public function marshalEmails()
    {
        $digests = [];

        foreach ($this->digestsPool->getDigests() as $digest) {
            $digests = array_merge($digests, $digest->marshalEmails());
        }

        return $digests;
    }

    /**
     * A digest collection can work with an email entity if at least one of the digests
     * in the collection can work with the email
     *
     * @param Entity $emailQueueEntity An email entity from email queue
     * @return bool return false if no digest in the pool supports the email data.
     */
    public function canAddToDigest(Entity $emailQueueEntity)
    {
        foreach ($this->digestsPool->getDigests() as $digest) {
            if ($digest->canAddToDigest($emailQueueEntity)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Entity $emailQueueEntity An instance of email entity
     * @return $this
     * @throws UnsupportedEmailDigestDataException
     */
    public function addEmailEntity(Entity $emailQueueEntity)
    {
        foreach ($this->digestsPool->getDigests() as $digest) {
            if ($digest->canAddToDigest($emailQueueEntity)) {
                $digest->addEmailEntity($emailQueueEntity);

                return $this;
            }
        }

        throw new UnsupportedEmailDigestDataException($emailQueueEntity);
    }
}
