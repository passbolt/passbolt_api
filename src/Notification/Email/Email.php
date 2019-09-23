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

/**
 * Class Email
 * @package App\Notification\Email
 *
 * Object returned by an EmailRedactor and used by an EmailSender to send the email.
 * It contains all the details for the emails: recipient, subject, data and template to use.
 *
 * This object is immutable: any modifications made after its creation must return a new instance.
 */
class Email
{
    /**
     * @var string
     */
    private $to;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $template;

    /**
     * @param string $to Email recipient
     * @param string $subject Subject of the email
     * @param array $data Data to inject in the email template
     * @param string $template Template to use for the email
     */
    public function __construct(string $to, string $subject, array $data, string $template)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->data = $data;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Return a new instance of Email with the provided data
     * @param array $data Data to use for the email
     * @return Email
     */
    public function withData(array $data)
    {
        $new = clone $this;
        $new->data = $data;

        return $new;
    }
}
