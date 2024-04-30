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
 * @since         2.12.0
 */

namespace App\Notification\Email;

use App\Utility\Purifier;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Text;
use EmailQueue\Model\Table\EmailQueueTable;

/**
 * Class EmailSender
 *
 * @package App\Notification\Email
 *
 * Its sole purpose is to send emails. It encapsulates the logic on how the email is sent.
 * In practice, it uses the EmailQueue plugin enqueue. Ultimate send responsibility is deferred to a
 * separated command line task (triggered manually or via cron job).
 */
class EmailSender
{
    /**
     * @var \EmailQueue\Model\Table\EmailQueueTable
     */
    private $emailQueue;

    /**
     * @var string
     */
    private $appFullBaseUrl;

    /**
     * @var string
     */
    private $purifySubject;

    /**
     * @param \EmailQueue\Model\Table\EmailQueueTable|null $emailQueue Email Queue Table instance
     * @param string|null $appFullBaseUrl Full base url of the Passbolt instance
     * @param bool|null $purifySubject if subject of emails must be purified before generation, false default
     */
    public function __construct(
        ?EmailQueueTable $emailQueue = null,
        ?string $appFullBaseUrl = null,
        ?bool $purifySubject = false
    ) {
        $this->emailQueue = $emailQueue ?? TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
        $this->appFullBaseUrl = $appFullBaseUrl ?? Configure::read('App.fullBaseUrl');
        $this->purifySubject = $purifySubject ?? Configure::read('passbolt.email.purify.subject');
    }

    /**
     * @param \App\Notification\Email\Email $email Email to send
     * @throws \Exception
     * @return self
     */
    public function sendEmail(Email $email): EmailSender
    {
        $email = $this->addFullBaseUrlToEmail($email);
        $options = $this->getEmailOptions($email);

        if (!$this->emailQueue->enqueue($email->getRecipient(), $email->getData(), $options)) {
            throw new EmailSenderException($email, $options);
        }

        return $this;
    }

    /**
     * @param \App\Notification\Email\Email $email Email to send
     * @return array
     */
    private function getEmailOptions(Email $email): array
    {
        return [
            'template' => $email->getTemplate(),
            'subject' => $this->purifySubject($email->getSubject()),
            'format' => 'html',
            'config' => 'default',
            'headers' => [
                'Auto-Submitted' => 'auto-generated',
                // Set message-id header which is by default disabled in the lorenzo/cakephp-email-queue plugin.
                'Message-ID' => self::getMessageId(),
            ],
        ];
    }

    /**
     * @param string $subject Subject to purify
     * @return string
     */
    public function purifySubject(string $subject): string
    {
        if ($this->purifySubject) {
            $subject = Purifier::clean($subject);
        }
        // The subject column of the email_queue table is limited to 255 characters.
        $subject = mb_substr($subject, 0, 255);

        return $subject;
    }

    /**
     * Set the full base URL at the body level for the email content
     * and at the higher level for the layout
     *
     * @param \App\Notification\Email\Email $email Email to send
     * @return \App\Notification\Email\Email
     */
    private function addFullBaseUrlToEmail(Email $email): Email
    {
        return $email->withData(array_merge_recursive($email->getData(), [
            'fullBaseUrl' => $this->appFullBaseUrl,
            'body' => [
                'fullBaseUrl' => $this->appFullBaseUrl,
            ],
        ]));
    }

    /**
     * Returns a string that can be used directly in Message-ID header.
     *
     * @return string
     *
     * Original implementation:
     * @see https://github.com/cakephp/cakephp/blob/4.x/src/Mailer/Message.php#L924
     */
    public static function getMessageId(): string
    {
        $domain = self::getDomainFromFullBaseUrl();

        // example: <e8c3db0628f41ea86a03dc53d16d11a@domain.com>
        return '<' . str_replace('-', '', Text::uuid()) . '@' . $domain . '>';
    }

    /**
     * Returns domain part from `Router::url()`.
     *
     * @return string The domain value or empty string if unable to parse host.
     */
    private static function getDomainFromFullBaseUrl(): string
    {
        $parts = parse_url(Router::url('/', true));

        if (!isset($parts['host'])) {
            return '';
        }

        return $parts['host'];
    }
}
