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

namespace Passbolt\EmailDigest\Utility\Marshaller\Type;

use Cake\ORM\Entity;
use Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerPool;

/**
 * Marshall emails digests using a collection of digest marshallers which are contained in a collection.
 * This collection is hold in the DigestMarshallerPool. It also defines the order in which the digests marshallers are returned.
 *
 * When marshalling an email, the first digest marshaller returned by the DigestMarshallerPool which can marshal
 * the given email will be used to marshall a digest with this email.
 */
class PoolDigestMarshaller extends AbstractDigestMarshaller implements DigestMarshallerInterface
{
    /**
     * @var DigestMarshallerPool
     */
    private $digestMarshallerPool;

    /**
     * @param DigestMarshallerPool $digestMarshallerPool Pool of Email Digest Marshallers
     */
    public function __construct(DigestMarshallerPool $digestMarshallerPool = null)
    {
        $this->digestMarshallerPool = $digestMarshallerPool ?? DigestMarshallerPool::getInstance();
    }

    /**
     * Foreach each email entity, it goes through each email marshallers, check if it can digest the email entity, if yes add emails data to it.
     * The first marshaller to be picked in the list, if can marshall, will be the first and ONLY one marshaller served for the email.
     * @return EmailDigestInterface[]
     */
    public function marshalDigests()
    {
        $digests = [];

        foreach ($this->digestMarshallerPool->getDigestMarshallers() as $marshaller) {
            $digests = array_merge($digests, $marshaller->marshalDigests());
        }

        return $digests;
    }

    /**
     * The pool digest marshaller can marshall what the marshallers in the pool can marshall themselves.
     * Return false if no marshaller in the pool supports the email data.
     * @param Entity $emailQueueEntity An email entity from email queue
     * @return bool
     */
    public function canMarshalDigestsFrom(Entity $emailQueueEntity)
    {
        foreach ($this->digestMarshallerPool->getDigestMarshallers() as $marshaller) {
            if ($marshaller->canMarshalDigestsFrom($emailQueueEntity)) {
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
    public function addEmailEntityToMarshal(Entity $emailQueueEntity)
    {
        foreach ($this->digestMarshallerPool->getDigestMarshallers() as $digestMarshaller) {
            if ($digestMarshaller->canMarshalDigestsFrom($emailQueueEntity)) {
                $digestMarshaller->addEmailEntityToMarshal($emailQueueEntity);

                return $this;
            }
        }

        throw new UnsupportedEmailDigestDataException($emailQueueEntity);
    }
}
