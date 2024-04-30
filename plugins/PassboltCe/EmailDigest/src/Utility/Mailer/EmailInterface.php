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
     *
     * @return string
     */
    public function getEmailRecipient(): string;

    /**
     * Return the subject of the email digest
     * i.e: your password has been reset
     *
     * @return string
     */
    public function getSubject(): string;

    /**
     * Return the template vars associated to the digest
     *
     * @return array
     */
    public function getViewVars(): array;

    /**
     * Return the format of the email digest
     * i.e: html
     *
     * @return string
     */
    public function getEmailFormat(): string;

    /**
     * Return the template file to use with this email digest
     * i.e: LU/template.php
     *
     * @return string
     */
    public function getTemplate(): string;

    /**
     * Add a variable to the template variables for the email.
     *
     * @param string $name Name of the variable
     * @param mixed $value Value of the variable
     * @return self
     */
    public function addTemplateVar(string $name, $value): self;

    /**
     * Add a variable to the layout variables for the email.
     *
     * @param string $name Name of the variable
     * @param mixed $value Value of the variable
     * @return self
     */
    public function addLayoutVar(string $name, $value): self;

    /**
     * @param string $subject Subject of the digest
     * @return self
     */
    public function setSubject(string $subject): self;

    /**
     * Return the email recipient
     *
     * @param string $recipient Email Recipient of the digest, i.e: ada@passbolt.com
     * @return self
     */
    public function setEmailRecipient(string $recipient): self;

    /**
     * Sets the list of ids of the emails part of the digest
     *
     * @param string[] $emailIds email ids of the emails under this email digest
     * @return self
     */
    public function setEmailIds(array $emailIds): self;

    /**
     * Sets the full base URL for this digest
     *
     * @param string $fullBaseUrl full base url
     * @return self
     */
    public function setFullBaseUrl(string $fullBaseUrl): self;

    /**
     * Gets the full base URL for this digest
     *
     * @return string
     */
    public function getFullBaseUrl(): string;
}
