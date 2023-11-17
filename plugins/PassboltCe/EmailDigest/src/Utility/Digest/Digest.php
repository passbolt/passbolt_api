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
 * @since         3.0.0
 */
namespace Passbolt\EmailDigest\Utility\Digest;

use App\Model\Entity\User;
use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;

/**
 * Digest gathering all emails for one recipient, one operator, and one digest template
 */
class Digest
{
    public const MAXIMUM_EMAILS_IN_DIGEST = 10;

    /**
     * Emails queues gathered in this digest
     *
     * @var \Cake\ORM\Entity[]
     */
    private array $emailQueues = [];

    /**
     * @var string
     */
    private string $recipient;

    /**
     * Operator execuring the action
     * i.e. in template, $body['user'] contains the user executing the action, then $executedByTemplateVarKey = 'user';
     * in template, if $body['admin'] contains the user executing the action, then $executedByTemplateVarKey = 'admin'
     *
     * @var \App\Model\Entity\User
     */
    private User $operator;

    /**
     * @var \Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate
     */
    private AbstractDigestTemplate $template;

    /**
     * In case the emails queue are shared across various organizations,
     * each digest are responsible for one single full base URL
     *
     * @var string
     */
    private string $fullBaseUrl;

    /**
     * Digest constructor.
     *
     * @param string $recipient digest recipient
     * @param \App\Model\Entity\User $operator operator
     * @param string $fullBaseUrl full base url
     * @param \Cake\ORM\Entity $emailQueue first email queue passed into the digest. A digest cannot be empty
     * @param \Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate $template digest template used to render this digest
     */
    public function __construct(
        string $recipient,
        User $operator,
        string $fullBaseUrl,
        Entity $emailQueue,
        AbstractDigestTemplate $template
    ) {
        $this->recipient = $recipient;
        $this->operator = $operator;
        $this->fullBaseUrl = $fullBaseUrl;
        $this->template = $template;
        $this->addEmail($emailQueue);
    }

    /**
     * @return \App\Model\Entity\User
     */
    public function getOperator(): User
    {
        return $this->operator;
    }

    /**
     * @return bool
     */
    public function isRecipientTheOperator(): bool
    {
        return $this->getOperator()->username === $this->recipient;
    }

    /**
     * @return \Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate
     */
    public function getTemplate(): AbstractDigestTemplate
    {
        return $this->template;
    }

    /**
     * The recipient of the digest
     *
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * The full base URL covered by the digest
     *
     * @return string
     */
    public function getFullBaseUrl(): string
    {
        return $this->fullBaseUrl;
    }

    /**
     * @return \Cake\ORM\Entity[]
     */
    public function getEmailQueues(): array
    {
        return $this->emailQueues;
    }

    /**
     * @return \Cake\ORM\Entity
     */
    public function getFirstEmailQueue(): Entity
    {
        return $this->emailQueues[0];
    }

    /**
     * Get a list of all the email ids
     *
     * @return array
     */
    public function getEmailQueueIds(): array
    {
        return (array)Hash::extract($this->emailQueues, '{n}.id');
    }

    /**
     * Get the number of emails in the digest
     *
     * @return int
     */
    public function getEmailQueueCount(): int
    {
        return count($this->emailQueues);
    }

    /**
     * Append an email to this digest
     *
     * @param \Cake\ORM\Entity $emailQueue Email queue
     * @return $this
     */
    public function addEmail(Entity $emailQueue)
    {
        $this->emailQueues[] = $emailQueue;

        return $this;
    }

    /**
     * Render the digest into an Email Digest ready to be sent
     *
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface
     */
    public function marshalEmails(): EmailDigestInterface
    {
        $numberOfEmails = $this->getEmailQueueCount();
        $renderFromEmailPreview = true;
        $emailPreviewFactory = new EmailPreviewFactory();
        if ($numberOfEmails === 1) {
            // If the digest contains only one email, render one single email
            $emailDigest = $emailPreviewFactory->buildSingleEmailDigest($this->getFirstEmailQueue());
        } elseif ($numberOfEmails <= self::MAXIMUM_EMAILS_IN_DIGEST) {
            // If the digest contains between 2 and 10 emails, render all the emails in one single digest
            $emailDigest = $emailPreviewFactory->buildMultipleEmailDigest($this);
        } else {
            // If the digest contains more than 10 emails, render only one single email summarizing what happened
            $emailDigest = $emailPreviewFactory->buildSummaryEmailDigest($this);
            $renderFromEmailPreview = false; // Do not render the emails in the digest
        }
        if ($renderFromEmailPreview) {
            $emailDigest->setContent(
                $emailPreviewFactory->renderDigestContentFromEmailPreview($emailDigest)
            );
        }

        return $emailDigest;
    }

    /**
     * Get the locale of the first email, which is the same for all emails
     * as the recipient is the same for all emails of a digest
     *
     * @return string|null
     */
    public function getLocale(): ?string
    {
        $locale = $this->getFirstEmailQueue()->get('template_vars')['locale'] ?? null;
        if (is_null($locale)) {
            Log::error('No locale was defined for this email.');
        }

        return $locale;
    }
}
