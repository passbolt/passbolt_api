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
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;

/**
 * Class AbstractDigestMarshaller contains the boilerplate code for a concrete DigestMarshaller
 */
abstract class AbstractDigestMarshaller implements DigestMarshallerInterface
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
    public function addEmailEntityToMarshal(Entity $emailQueueEntity)
    {
        $this->emailsData[] = $emailQueueEntity;

        return $this;
    }

    /**
     * Helper method which render the content of every emails contained in the marshaller into a string to be used
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
}
