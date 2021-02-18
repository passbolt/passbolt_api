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

use Passbolt\EmailDigest\Utility\Factory\DigestFactory;

/**
 * The EmailDigestService is a service designed to aggregate multiple emails together.
 *
 * The emails are aggregated following rules defined in what we call the digests.
 * The purpose of a digest is to create a collection of email digests from a collection of given emails.
 * Each digest can define the emails that it can group together and the way it groups the emails.
 *
 * Rules applied while creating email digests are the following:
 * - One user can have one or many email digests.
 * - One email digest is composed of one or many emails entities.
 * - One email entity can be only present in one digest.
 *
 * Class EmailDigestService
 *
 * @package Passbolt\EmailDigest\Service
 */
class EmailDigestService
{
    /**
     * @var \Passbolt\EmailDigest\Utility\Factory\DigestFactory
     */
    private $digestFactory;

    /**
     * @param \Passbolt\EmailDigest\Utility\Factory\DigestFactory|null $digestFactory Factory
     */
    public function __construct(?DigestFactory $digestFactory = null)
    {
        $this->digestFactory = $digestFactory ?? DigestFactory::getInstance();
    }

    /**
     * Handle the emails data for each recipient and transform them to emails digests
     *
     * @param array $emails An array of emails entities from email queue.
     * @return array of EmailDigestInterface
     * @throws \Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException
     */
    public function createDigests(array $emails): array
    {
        // Group the emails entities by recipient and create digests for each group of emails.
        $digestsCollection = $this->digestFactory->createDigestsCollection();
        $fallback = $this->digestFactory->createSingleDigest();

        foreach ($emails as $emailQueueEntity) {
            if ($digestsCollection->canAddToDigest($emailQueueEntity)) {
                $digestsCollection->addEmailEntity($emailQueueEntity);
            } else {
                $fallback->addEmailEntity($emailQueueEntity);
            }
        }

        return array_merge($fallback->marshalEmails(), $digestsCollection->marshalEmails());
    }
}
