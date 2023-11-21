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
namespace Passbolt\EmailDigest\Utility\DigestCollection;

use Cake\ORM\Entity;
use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;

/**
 * Default digest to fall back to building a single email
 * Adding more than one email to the digest will return as many "digests" as there is emails.
 */
class SingleDigestCollection extends AbstractDigestCollection
{
    /**
     * @var \Cake\ORM\Entity[]
     */
    private array $emails = [];

    /**
     * Add an email
     *
     * @param \Cake\ORM\Entity $emailQueue An email entity
     * @return self
     */
    public function addEmailEntity(Entity $emailQueue): self
    {
        $this->emails[] = $emailQueue;

        return $this;
    }

    /**
     * Process and set the content of the emails (as EmailDigest).
     *
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface[]
     */
    public function marshalEmails(): array
    {
        $emailPreviewFactory = new EmailPreviewFactory();
        $result = [];
        foreach ($this->emails as $email) {
            $emailDigest = $emailPreviewFactory->buildSingleEmailDigest($email);
            $emailDigest->setContent($emailPreviewFactory->renderDigestContentFromEmailPreview($emailDigest));
            $result[] = $emailDigest;
        }

        return $result;
    }
}
