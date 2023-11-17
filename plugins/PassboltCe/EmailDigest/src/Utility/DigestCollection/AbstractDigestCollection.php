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
 * @since         4.5.0
 */

namespace Passbolt\EmailDigest\Utility\DigestCollection;

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
abstract class AbstractDigestCollection
{
    /**
     * Add some email entities to digest collection
     *
     * @param \Cake\ORM\Entity $emailQueue An email entity to add to the digest collection
     * @return self
     */
    abstract public function addEmailEntity(Entity $emailQueue): self;

    /**
     * Return a list of emails. Even if the digest return one, the digest must be in an array.
     * This function contains the strategy for the digest to use to compose the final emails
     *
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface[]
     */
    abstract public function marshalEmails(): array;
}
