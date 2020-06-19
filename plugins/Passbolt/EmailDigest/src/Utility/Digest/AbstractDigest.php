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
use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;

/**
 * Class AbstractDigest contains the boilerplate code to create a concrete Digest
 */
abstract class AbstractDigest implements DigestInterface
{
    /**
     * Email from email queue plugin
     * @var Entity[]
     */
    protected $emailsData = [];

    /**
     * @param Entity $emailQueueEntity Email entity to use to marshal digests.
     * @return static
     */
    public function addEmailEntity(Entity $emailQueueEntity)
    {
        $this->emailsData[] = $emailQueueEntity;

        return $this;
    }

    /**
     * Helper method which render the content of every emails contained in a digest into a string to be used
     * as the content of the digest.
     * @param EmailPreviewFactory $factory Factory
     * @param EmailDigestInterface $emailDigest Email digest to use to generate the render
     * @return string
     */
    protected function renderDigestContentFromEmailPreview(EmailPreviewFactory $factory, EmailDigestInterface $emailDigest)
    {
        $emailDigestContent = [];
        foreach ($emailDigest->getEmailsData() as $emailData) {
            $emailPreview = $factory->renderEmailPreviewFromEmailEntity($emailData);
            $emailDigestContent[] = $emailPreview->getContent();
        }

        return implode("", $emailDigestContent);
    }

    /**
     * @param Entity $emailQueueEntity entity
     * @return EmailDigest
     */
    public function buildSingleEmailDigest(Entity $emailQueueEntity)
    {
        return (new EmailDigest())
            ->addEmailData($emailQueueEntity)
            ->setSubject($emailQueueEntity->subject)
            ->setEmailRecipient($emailQueueEntity->email);
    }
}
