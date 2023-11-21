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

namespace Passbolt\EmailDigest\Service;

use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;

/**
 * Class PreviewEmailBatchService allows to preview how the next batch would be rendered.
 * It returns a collection of emails previews.
 *
 * @package Passbolt\EmailDigest\Service
 */
class PreviewEmailBatchService
{
    /**
     * Preview a collection of emails as emails digests
     *
     * @param \Cake\ORM\Entity[] $emailQueues array of emails.
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailPreview[]
     * @throws \Exception
     */
    public function previewNextEmailsBatch(array $emailQueues): array
    {
        $emailDigests = (new EmailDigestService())->createEmailDigests($emailQueues);
        $previews = [];

        foreach ($emailDigests as $emailDigest) {
            $previews[] = (new EmailPreviewFactory())->renderEmailPreviewFromDigest($emailDigest, 'default');
        }

        return $previews;
    }
}
