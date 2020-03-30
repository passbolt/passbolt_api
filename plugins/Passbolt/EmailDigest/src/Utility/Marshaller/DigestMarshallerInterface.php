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

use Cake\ORM\Entity;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;

/**
 * The emails are marshalled following rules defined in what we call a digest marshaller.
 * The purpose of a digest marshaller is to create a collection of emails digests from a collection of given emails.
 *
 * A marshaller can define which emails it wants to marshal as digests and the strategy used to marshal (compose) them.
 *
 * It accepts a collection of emails entities and return a collection of EmailDigestInterface.
 *
 * A marshaller is responsible to compose the emails digests with their properties (subject, content, recipients, etc..).
 *
 * @see EmailDigestInterface
 */
interface DigestMarshallerInterface
{
    /**
     * Add some email entities to marshall.
     * @param Entity $emailQueueEntity An email entity to add to the marshaller
     * @return DigestMarshallerInterface
     */
    public function addEmailEntityToMarshal(Entity $emailQueueEntity);

    /**
     * Return a list of digests. Even if the marshaller return one digest, the digest must be in an array.
     * Contain the digest strategy for the digest marshaller which can compose the digests with emails data in a fashioned manner.
     * @return EmailDigestInterface[]
     */
    public function marshalDigests();

    /**
     * Return a boolean indicating if the marshaller can handle the given email entity from email queue and marshal it into an email digest.
     * @param Entity $emailQueueEntity An instance of EmailDigest
     * @return bool
     */
    public function canMarshalDigestsFrom(Entity $emailQueueEntity);
}
