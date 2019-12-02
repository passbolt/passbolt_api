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
 * @since         2.12.0
 */

namespace App\Notification\Email;

use App\Utility\Purifier;
use Cake\Event\EventManagerInterface;
use EmailQueue\EmailQueue;
use EmailQueue\Model\Table\EmailQueueTable;
use Exception;

/**
 * Class EmailSender
 * @package App\Notification\Email
 *
 * Its sole purpose is to send emails. It encapsulates the logic on how the email is sent.
 * In practices it uses the EmailQueue plugin enqueue. Ultimate send responsibility is deferred to a
 * separated command line task (triggered manually or via cron job).
 */
class EmailSender
{
    /**
     * @var EmailQueue
     */
    private $emailQueue;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var string
     */
    private $appFullBaseUrl;

    /**
     * @var string
     */
    private $purifySubject = false;

    /**
     * @param EventManagerInterface $eventManager EventManager object
     * @param EmailQueueTable $emailQueue Email queue
     * @param string $appFullBaseUrl Full base url of the Passbolt instance
     * @param bool $purifySubject True if subject must be purified
     */
    public function __construct(EventManagerInterface $eventManager, EmailQueueTable $emailQueue, string $appFullBaseUrl, bool $purifySubject)
    {
        $this->emailQueue = $emailQueue;
        $this->eventManager = $eventManager;
        $this->appFullBaseUrl = $appFullBaseUrl;
        $this->purifySubject = $purifySubject;
    }

    /**
     * @param Email $email Email to send
     * @return $this
     * @throws Exception
     */
    public function sendEmail(Email $email)
    {
        $email = $this->addFullBaseUrlToEmail($email);
        $options = $this->getEmailOptions($email);

        if (!$this->emailQueue->enqueue($email->getTo(), $email->getData(), $options)) {
            throw new EmailSenderException($email, $options);
        }

        return $this;
    }

    /**
     * @param Email $email Email to send
     * @return array
     */
    private function getEmailOptions(Email $email)
    {
        return [
            'template' => $email->getTemplate(),
            'subject' => $this->purifySubject($email->getSubject()),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated']
        ];
    }

    /**
     * @param string $subject Subject to purify
     * @return string
     */
    private function purifySubject(string $subject)
    {
        if ($this->purifySubject) {
            $subject = Purifier::clean($subject);
        }

        return $subject;
    }

    /**
     * @param Email $email Email to send
     * @return Email
     */
    private function addFullBaseUrlToEmail(Email $email)
    {
        return $email->withData(array_merge_recursive($email->getData(), [
            'body' => [
                'fullBaseUrl' => $this->appFullBaseUrl
            ]
        ]));
    }
}
