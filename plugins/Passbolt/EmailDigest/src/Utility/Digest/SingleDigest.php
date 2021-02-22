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

/**
 * Default digest to fall back to building a single email
 * Adding more than one email to the digest will return as many "digests" as there is emails.
 */
class SingleDigest extends AbstractDigest implements DigestInterface
{
    /**
     * @var \Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory
     */
    private $emailPreviewFactory;

    /**
     * @var $emails[]
     */
    private $emails = [];

    /**
     * Digest constructor.
     *
     * @param \Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory|null $emailPreviewFactory email preview factory
     */
    public function __construct(?EmailPreviewFactory $emailPreviewFactory = null)
    {
        $this->emailPreviewFactory = $emailPreviewFactory ?? new EmailPreviewFactory();
    }

    /**
     * Add an email
     *
     * @param \Cake\ORM\Entity $emailQueueEntity An email entity
     * @return $this
     */
    public function addEmailEntity(Entity $emailQueueEntity)
    {
        $this->emails[] = $emailQueueEntity;

        return $this;
    }

    /**
     * Process and set the content of the emails (as EmailDigest).
     *
     * @return \Passbolt\EmailDigest\Utility\Digest\EmailDigestInterface[]
     */
    public function marshalEmails()
    {
        $result = [];
        foreach ($this->emails as $username => $emails) {
            $result[] = $this->buildSingleEmailDigest($emails);
        }
        foreach ($result as $digest) {
            $digest->setContent($this->renderDigestContentFromEmailPreview($this->emailPreviewFactory, $digest));
        }

        return $result;
    }

    /**
     * Single email digest can always add any email.
     *
     * @param \Cake\ORM\Entity $emailQueueEntity An email entity
     * @return bool
     */
    public function canAddToDigest(Entity $emailQueueEntity)
    {
        return true;
    }
}
