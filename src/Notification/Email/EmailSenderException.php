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

use Exception;

class EmailSenderException extends Exception
{
    /**
     * @var Email
     */
    private $email;
    /**
     * @var array
     */
    private $options;

    /**.
     * @param Email $email Email which failed to send
     * @param array $options Options used to send the email
     */
    public function __construct(Email $email, array $options)
    {
        parent::__construct('Failed to send email');
        $this->email = $email;
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
