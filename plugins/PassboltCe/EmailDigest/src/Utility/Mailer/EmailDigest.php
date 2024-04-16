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

    private array $emailsData = [];
    private array $emailIds = [];
    private string $content;
    private string $recipient;
    private string $subject;
    private string $fullBaseUrl;
    /**
     * @var string|null Template to use to compose the email
     */
    private ?string $template = null;
    private array $templateVars = [];
    private array $layoutVars = [];

    /**
     * Return the list of ids of the emails part of the digest
     *
     * @return string[]
     */
    public function getEmailIds(): array
    {
        return $this->emailIds;
    }

    /**
     * @inheritDoc
     */
    public function setEmailIds(array $emailIds): self
    {
        $this->emailIds = $emailIds;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addEmailData(Entity $emailQueueEntity): self
    {
        $this->emailsData[] = $emailQueueEntity;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEmailsData(): array
    {
        return $this->emailsData;
    }

    /**
     * Return vars associated to the view (layout + template)
     *
     * @return array
     */
    public function getViewVars(): array
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
     * @inheritDoc
     */
    public function getEmailFormat(): string
    {
        return self::DEFAULT_FORMAT;
    }

    /**
     * @inheritDoc
     */
    public function getTemplate(): string
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @inheritDoc
     */
    public function setEmailRecipient(string $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEmailRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @inheritDoc
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        $this->addLayoutVar('title', $subject);

        return $this;
    }

    /**
     * Return the translated subject of the digest
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function setContent(string $digestContent): self
    {
        $this->content = $digestContent;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addTemplateVar(string $name, $value): self
    {
        $this->templateVars[$name] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addLayoutVar(string $name, $value): self
    {
        $this->layoutVars[$name] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFullBaseUrl(string $fullBaseUrl): self
    {
        $this->fullBaseUrl = $fullBaseUrl;
        $this->addLayoutVar('fullBaseUrl', $fullBaseUrl);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFullBaseUrl(): string
    {
        return $this->fullBaseUrl;
    }
}
