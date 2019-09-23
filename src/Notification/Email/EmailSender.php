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
 * @since         2.0.0
 */

namespace App\Notification\Email;

use App\Utility\Purifier;
use Cake\Event\EventManagerInterface;
use EmailQueue\EmailQueue;
use EmailQueue\Model\Table\EmailQueueTable;
use Exception;

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
     * @param EventManagerInterface $eventManager EventManager object
     * @param EmailQueueTable $emailQueue Email queue
     * @param string $appFullBaseUrl Full base url of the Passbolt instance
     */
    public function __construct(EventManagerInterface $eventManager, EmailQueueTable $emailQueue, string $appFullBaseUrl)
    {
        $this->emailQueue = $emailQueue;
        $this->eventManager = $eventManager;
        $this->appFullBaseUrl = $appFullBaseUrl;
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
            'subject' => Purifier::clean($email->getSubject()),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated']
        ];
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
