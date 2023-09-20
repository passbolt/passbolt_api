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

use App\Model\Entity\User;

/**
 * Class Email
 *
 * @package App\Notification\Email
 *
 * Object returned by an EmailRedactor and used by an EmailSender to send the email.
 * It contains all the details for the emails: recipient, subject, data and template to use.
 *
 * This object is immutable: any modifications made after its creation must return a new instance.
 */
class Email
{
    private User $recipientUser;
    private string $recipient;
    private string $subject;
    private array $data;
    private string $template;

    /**
     * @param \App\Model\Entity\User $recipientUser Email recipient user entity
     * @param string $subject Subject of the email
     * @param array  $data Data to inject in the email template
     * @param string $template Template to use for the email
     */
    public function __construct(User $recipientUser, string $subject, array $data, string $template)
    {
        $this->recipientUser = $recipientUser;
        $this->recipient = $recipientUser->username;
        $this->subject = $subject;
        $this->data = $data;
        $this->template = $template;
    }

    /**
     * Check if the 'disabled' field is in the properties. If not, we consider the status of the recipient as unknown
     * and the user is considered as disabled.
     *
     * If 'disabled' is defined, fallbacks on the User entity logic
     *
     * @return bool
     */
    public function isRecipientUserDisabled(): bool
    {
        $isDisabledInUserProperties = array_key_exists('disabled', $this->recipientUser->toArray());
        if (!$isDisabledInUserProperties) {
            return true;
        }

        return $this->recipientUser->isDisabled();
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Return a new instance of Email with the provided data
     *
     * @param array $data Data to use for the email
     * @return self
     */
    public function withData(array $data): self
    {
        $new = clone $this;
        $new->data = $data;

        return $new;
    }
}
