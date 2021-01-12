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

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use EmailQueue\Model\Table\EmailQueueTable;
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
     * @var \EmailQueue\Model\Table\EmailQueueTable
     */
    private $emailQueueTable;

    /**
     * @var \Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory
     */
    private $emailPreviewFactory;

    /**
     * @var \Passbolt\EmailDigest\Service\EmailDigestService
     */
    private $emailDigestsService;

    /**
     * @param \EmailQueue\Model\Table\EmailQueueTable|null $emailQueueTable Email Queue Table
     * @param \Passbolt\EmailDigest\Service\EmailDigestService|null $emailDigestsService Emails Digests Service
     * @param \Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory|null $emailPreviewFactory Email Preview Factory
     */
    public function __construct(
        ?EmailQueueTable $emailQueueTable = null,
        ?EmailDigestService $emailDigestsService = null,
        ?EmailPreviewFactory $emailPreviewFactory = null
    ) {
        $options = ['className' => EmailQueueTable::class];
        $this->emailQueueTable = $emailQueueTable ?? TableRegistry::getTableLocator()->get('EmailQueue', $options);
        $this->emailDigestsService = $emailDigestsService ?? new EmailDigestService();
        $this->emailPreviewFactory = $emailPreviewFactory ?? new EmailPreviewFactory();
    }

    /**
     * Get and send the next emails batch from the email queue. The size of the email batch is determined by $limit.
     *
     * @param int $limit Size of the emails batch.
     * @return array
     * @throws \Exception
     */
    public function previewNextEmailsBatch(int $limit = 10): array
    {
        Configure::write('App.baseUrl', '/');

        $emails = $this->emailQueueTable->getBatch($limit);

        if (!empty($emails)) {
            // we release the locks as soon as we get the emails
            // we dont want to block the next batch ran by a cron job because of lock.
            // technically, to do better, we should write the same query ran in getBatch method without locking the emails
            $this->emailQueueTable->releaseLocks(Hash::extract($emails, '{n}.id'));
        }

        return $this->getPreviewsOfEmailsAsDigests($emails);
    }

    /**
     * Preview a collection of emails as emails digests
     *
     * @param array $emails An array of emails entities
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailPreview[]
     * @throws \Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException
     */
    public function getPreviewsOfEmailsAsDigests(array $emails): array
    {
        $emailDigests = $this->emailDigestsService->createDigests($emails);
        $previews = [];

        foreach ($emailDigests as $digest) {
            $previews[] = $this->emailPreviewFactory->renderEmailPreviewFromDigest($digest, 'default');
        }

        return $previews;
    }
}
