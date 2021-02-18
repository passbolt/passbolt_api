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

use Cake\ORM\Entity;
use Cake\Utility\Hash;

class EmailDigest implements EmailDigestInterface
{
    /**
     * The default format used for the email
     */
    public const DEFAULT_FORMAT = 'html';

    /**
     * The default template used for the email
     */
    public const DEFAULT_TEMPLATE = 'Passbolt/EmailDigest.LU/email_digest';

    /**
     * The var name containing the digest of the email
     */
    public const TPL_VAR_DIGEST_CONTENT = 'digest_content';

    /**
     * @var array
     */
    private $emailsData = [];

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string Template to use to compose the email
     */
    private $template;

    /**
     * @var array
     */
    private $templateVars = [];

    /**
     * @var array
     */
    private $layoutVars = [];

    /**
     * Return the list of ids of the emails part of the digest
     *
     * @return array|\Passbolt\EmailDigest\Utility\Mailer\ArrayAccess|string[]
     */
    public function getEmailIds()
    {
        return Hash::extract($this->emailsData, '{n}.id');
    }

    /**
     * @param \Cake\ORM\Entity $emailQueueEntity An instance of EmailQueue entity
     * @return $this
     */
    public function addEmailData(Entity $emailQueueEntity)
    {
        $this->emailsData[] = $emailQueueEntity;

        return $this;
    }

    /**
     * @return \Cake\ORM\Entity[]
     */
    public function getEmailsData()
    {
        return $this->emailsData;
    }

    /**
     * Return vars associated to the view (layout + template)
     *
     * @return array
     */
    public function getViewVars()
    {
        $vars = $this->templateVars;

        if (empty($vars)) {
            $vars = [
                static::TPL_VAR_DIGEST_CONTENT => $this->getContent(),
            ];
        }

        return array_merge($this->layoutVars, ['body' => $vars]);
    }

    /**
     * @return string
     */
    public function getEmailFormat()
    {
        return self::DEFAULT_FORMAT;
    }

    /**
     * Return the path of the template file to use for this email
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Return the email recipient
     *
     * @param string $recipient Email Recipient of the digest, i.e: ada@passbolt.com
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigest
     */
    public function setEmailRecipient(string $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Return the email recipient
     *
     * @return string
     */
    public function getEmailRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $subject Subject of the digest
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigest
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Return the subject of the digest
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Define the content of the email digest
     *
     * @param string $digestContent Content of the digest
     * @return $this
     */
    public function setContent(string $digestContent)
    {
        $this->content = $digestContent;

        return $this;
    }

    /**
     * @param string $template Template
     * @return $this
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Add key/value pair to "body" variable available in email view
     *
     * @param string $name Name of the variable to add to be used with the template of the email
     * @param mixed $value Value of the variable
     * @return $this
     */
    public function addTemplateVar(string $name, $value)
    {
        $this->templateVars[$name] = $value;

        return $this;
    }

    /**
     * Add variable available in email template
     *
     * @param string $name Name of the variable to add to be used with the layout of the email
     * @param mixed $value Value of the variable
     * @return $this
     */
    public function addLayoutVar(string $name, $value)
    {
        $this->layoutVars[$name] = $value;

        return $this;
    }
}
