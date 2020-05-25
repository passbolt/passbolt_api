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
 * @since         2.13.0
 */
namespace Passbolt\EmailDigest\Utility\Mailer;

/**
 * Should be implemented by any class holding email data (simple email, digest). Ideally, this interface should also be implemented
 * by email entity returned by emailqueue plugin as well.
 */
interface EmailInterface
{
    /**
     * Return the recipient of the email digest.
     * i.e: sendmeanemail@domain.com
     * @return string
     */
    public function getEmailRecipient();

    /**
     * Return the subject of the email digest
     * i.e: your password has been reset
     * @return string
     */
    public function getSubject();

    /**
     * Return the template vars associated to the digest
     * @return array
     */
    public function getViewVars();

    /**
     * Return the format of the email digest
     * i.e: html
     * @return string
     */
    public function getEmailFormat();

    /**
     * Return the template file to use with this email digest
     * i.e: LU/template.ctp
     * @return string
     */
    public function getTemplate();

    /**
     * Add a variable to the template variables for the email.
     * @param string $name Name of the variable
     * @param mixed $value Value of the variable
     * @return $this
     */
    public function addTemplateVar(string $name, $value);

    /**
     * Add a variable to the layout variables for the email.
     * @param string $name Name of the variable
     * @param mixed $value Value of the variable
     * @return $this
     */
    public function addLayoutVar(string $name, $value);

    /**
     * @param string $subject Subject of the digest
     * @return $this
     */
    public function setSubject(string $subject);

    /**
     * @param string $recipient Recipient of the digest
     * @return $this
     */
    public function setEmailRecipient(string $recipient);
}
