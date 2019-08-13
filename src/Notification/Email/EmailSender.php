<?php

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

    public function __construct(EventManagerInterface $eventManager, EmailQueueTable $emailQueue, string $appFullBaseUrl)
    {
        $this->emailQueue = $emailQueue;
        $this->eventManager = $eventManager;
        $this->appFullBaseUrl = $appFullBaseUrl;
    }

    /**
     * @param Email $email
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
     * @param Email $email
     * @return Email
     */
    private function addFullBaseUrlToEmail(Email $email)
    {
        return $email->withData(array_merge($email->getData(), [
            'body' => [
                'fullBaseUrl' => $this->appFullBaseUrl
            ]
        ]));
    }
}
