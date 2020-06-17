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

namespace Passbolt\EmailDigest\Utility\Mailer;

use Cake\ORM\Entity;

/**
 * A digest is an email composed of one or multiple emails.
 */
interface EmailDigestInterface extends EmailInterface
{
    /**
     * An array of data composed with email queue format
     * @param Entity $emailQueueEntity An email entity
     * @return $this
     */
    public function addEmailData(Entity $emailQueueEntity);

    /**
     * Return the emails entity associated to the email digest
     * @return Entity[]
     */
    public function getEmailsData();

    /**
     * Render the content of the email digest.
     * @return string
     */
    public function getContent();

    /**
     * A digest must be able to return the ids of the emails which compose the digest.
     * Return the list of email ids associated with the digest.
     * @return string[]
     */
    public function getEmailIds();

    /**
     * @param string $digestContent Content of the digest
     * @return $this
     */
    public function setContent(string $digestContent);
}
