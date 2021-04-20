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
     *
     * @var \Cake\ORM\Entity[]
     */
    protected $emailsData = [];

    /**
     * @param \Cake\ORM\Entity $emailQueueEntity Email entity to use to marshal digests.
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
     *
     * @param \Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory $factory Factory
     * @param \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface $digest Email digest to use to render
     * @return string
     */
    protected function renderDigestContentFromEmailPreview(EmailPreviewFactory $factory, EmailDigestInterface $digest)
    {
        $emailDigestContent = [];
        foreach ($digest->getEmailsData() as $emailData) {
            $emailPreview = $factory->renderEmailPreviewFromEmailEntity($emailData);
            $emailDigestContent[] = $emailPreview->getContent();
        }

        return implode('', $emailDigestContent);
    }

    /**
     * @param \Cake\ORM\Entity $emailQueueEntity entity
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigest
     */
    public function buildSingleEmailDigest(Entity $emailQueueEntity): EmailDigest
    {
        return (new EmailDigest())
            ->addEmailData($emailQueueEntity)
            ->setSubject($emailQueueEntity->get('subject'))
            ->setEmailRecipient($emailQueueEntity->get('email'));
    }
}
