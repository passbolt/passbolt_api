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

/**
 * Class EmailCollection
 *
 * @package App\Notification\Email
 *
 * Utility class to help email redactors aggregate multiple Emails to be sent.
 */
class EmailCollection
{
    /**
     * @var \App\Notification\Email\Email[]
     */
    private array $emails = [];

    /**
     * @param \App\Notification\Email\Email[] $emails A list of emails
     */
    public function __construct(array $emails = [])
    {
        foreach ($emails as $email) {
            $this->addEmail($email);
        }
    }

    /**
     * Skip emails which recipient is disabled
     *
     * @param \App\Notification\Email\Email $email Email object to add to the collection
     * @return $this
     */
    public function addEmail(Email $email)
    {
        if ($email->isRecipientUserDisabled()) {
            return $this;
        }

        $this->emails[] = $email;

        return $this;
    }

    /**
     * @return \App\Notification\Email\Email[]
     */
    public function getEmails(): array
    {
        return $this->emails;
    }
}
