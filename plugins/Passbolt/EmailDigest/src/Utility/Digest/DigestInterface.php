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
 * @since         2.13.0
 */

namespace Passbolt\EmailDigest\Utility\Digest;

use Cake\ORM\Entity;

/**
 * The emails grouped together following rules defined in what we call a digest.
 * The purpose of a digest is to create a single email from a collection of given emails.
 *
 * A digest can define which emails it wants to group together and the strategy used to group them.
 * It accepts a collection of emails entities and return a collection of EmailDigestInterface.
 *
 * A digest is responsible to compose the composite emails with their properties (subject, content, recipients, etc..).
 *
 * @see EmailDigestInterface
 */
interface DigestInterface
{
    /**
     * Add some email entities to digest.
     *
     * @param \Cake\ORM\Entity $emailQueueEntity An email entity to add to the digest
     * @return \Passbolt\EmailDigest\Utility\Digest\DigestInterface
     */
    public function addEmailEntity(Entity $emailQueueEntity);

    /**
     * Return a list of emails. Even if the digest return one, the digest must be in an array.
     * This function contains the strategy for the digest to use to compose the final emails
     *
     * @return \Passbolt\EmailDigest\Utility\Digest\EmailDigestInterface[]
     */
    public function marshalEmails();

    /**
     * Return a boolean indicating if the digest can handle the given email entity from email queue and marshal it into an email digest.
     *
     * @param \Cake\ORM\Entity $emailQueueEntity An instance of EmailDigest
     * @return bool
     */
    public function canAddToDigest(Entity $emailQueueEntity);
}
