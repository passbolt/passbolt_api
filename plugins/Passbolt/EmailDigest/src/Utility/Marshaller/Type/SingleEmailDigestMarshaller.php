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
use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;

/**
 * Marshall emails into email digests which always contain a single email.
 * Adding more than one email to the marshaller will return as many digests as there is emails.
 */
class SingleEmailDigestMarshaller extends AbstractDigestMarshaller implements DigestMarshallerInterface
{
    /**
     * @var EmailDigestInterface[]
     */
    private $digests = [];

    /**
     * @var EmailPreviewFactory
     */
    private $emailPreviewFactory;

    /**
     * @param EmailPreviewFactory $emailPreviewFactory Factory of email snapshot
     */
    public function __construct(EmailPreviewFactory $emailPreviewFactory = null)
    {
        $this->emailPreviewFactory = $emailPreviewFactory ?? new EmailPreviewFactory();
    }

    /**
     * @param Entity $emailQueueEntity An email entity
     * @return static
     */
    public function addEmailEntityToMarshal(Entity $emailQueueEntity)
    {
        $this->digests[] = (new EmailDigest())
            ->addEmailData($emailQueueEntity)
            ->setSubject($emailQueueEntity->subject)
            ->setEmailRecipient($emailQueueEntity->email);

        return $this;
    }

    /**
     * Process and set the content of the digests.
     * @return EmailDigestInterface[]
     */
    public function marshalDigests()
    {
        foreach ($this->digests as $digest) {
            $digest->setContent($this->renderDigestContentFromEmailPreview($this->emailPreviewFactory, $digest));
        }

        return $this->digests;
    }

    /**
     * Always single email digest can digest any email.
     * @param Entity $emailQueueEntity An email entity
     * @return bool
     */
    public function canMarshalDigestsFrom(Entity $emailQueueEntity)
    {
        return true;
    }
}
