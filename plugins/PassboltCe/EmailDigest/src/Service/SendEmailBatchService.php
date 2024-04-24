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
use Cake\Mailer\Mailer;
use Cake\Network\Exception\SocketException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\EmailTrait;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;

/**
 * Class SendEmailBatchService sends batch of emails entities as digests.
 * Digests are composed using EmailDigestService
 *
 * @see EmailDigestService
 */
class SendEmailBatchService
{
    use EmailTrait;

    /**
     * @var \EmailQueue\Model\Table\EmailQueueTable
     */
    private $emailQueueTable;

    /**
     * SendEmailBatchService construct
     */
    public function __construct()
    {
        $this->emailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
    }

    /**
     * Get and send the next emails batch from the email queue. The size of the email batch is determined by $limit.
     *
     * @param \Cake\ORM\Entity[] $emailQueues array of emails.
     * @return void
     * @throws \Exception
     */
    public function sendNextEmailsBatch(array $emailQueues): void
    {
        Configure::write('App.baseUrl', '/');

        $emailDigests = (new EmailDigestService())->createEmailDigests($emailQueues);

        foreach ($emailDigests as $digest) {
            $this->sendDigest($digest);
        }
    }

    /**
     * @param \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface $emailDigest An instance of Email digest
     * @return void
     */
    private function sendDigest(EmailDigestInterface $emailDigest): void
    {
        $email = $this->mapEmailDigestToMailerEmail(new Mailer('default'), $emailDigest);

        try {
            $email->send();
            $this->flagEmailsFromDigestAsSentWithSuccess($emailDigest);
        } catch (SocketException $exception) {
            $this->flagEmailsFromDigestAsFailedWithError($emailDigest, $exception->getMessage());
        } finally {
            // We use finally to guarantee that even if an exception occurred
            // while flagging the emails, locks are released
            if (!empty($emailDigest->getEmailIds())) {
                $this->emailQueueTable->releaseLocks($emailDigest->getEmailIds());
            }
        }
    }

    /**
     * Configure the view for the email as it should be send with layout, theme and template from the digest.
     *
     * @param \Cake\Mailer\Mailer $email An instance of Mailer email
     * @param \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface $digest An instace of email digest
     * @return \Cake\Mailer\Mailer
     */
    private function prepareEmailToBeSend(Mailer $email, EmailDigestInterface $digest): Mailer
    {
        $email->viewBuilder()
            ->setLayout('default')
            ->setTheme('')
            ->setTemplate($digest->getTemplate());

        return $email;
    }

    /**
     * Flag the list of given emails ids as sent
     *
     * @param \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface $emailDigest An email digest
     * @return void
     */
    private function flagEmailsFromDigestAsSentWithSuccess(EmailDigestInterface $emailDigest): void
    {
        foreach ($emailDigest->getEmailIds() as $id) {
            $this->emailQueueTable->success($id);
        }
    }

    /**
     * Flag the list of given emails ids as failed
     *
     * @param \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface $digest An email digest
     * @param string $message Error message to store in db
     * @return void
     */
    private function flagEmailsFromDigestAsFailedWithError(EmailDigestInterface $digest, string $message): void
    {
        foreach ($digest->getEmailIds() as $id) {
            $this->emailQueueTable->fail($id, $message);
        }
    }

    /**
     * Map an instance of EmailDigest to an instance of Email, so it can be send.
     *
     * @param \Cake\Mailer\Mailer $email An instance of Email
     * @param \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface $emailDigest An instance of EmailDigest
     * @return \Cake\Mailer\Mailer
     */
    private function mapEmailDigestToMailerEmail(Mailer $email, EmailDigestInterface $emailDigest): Mailer
    {
        $email
            ->setTo($emailDigest->getEmailRecipient())
            ->setSubject($emailDigest->getSubject())
            ->setEmailFormat($emailDigest->getEmailFormat())
            ->setMessageId(true)
            ->setViewVars($emailDigest->getViewVars())
            ->setReturnPath($email->getFrom());

        $this->prepareEmailToBeSend($email, $emailDigest);

        return $email;
    }
}
