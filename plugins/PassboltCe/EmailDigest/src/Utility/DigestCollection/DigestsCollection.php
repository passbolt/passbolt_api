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

namespace Passbolt\EmailDigest\Utility\DigestCollection;

use App\Model\Entity\User;
use App\Model\Validation\EmailValidationRule;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException;
use Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate;
use Passbolt\EmailDigest\Utility\Digest\Digest;
use Passbolt\EmailDigest\Utility\Digest\DigestTemplateRegistry;

/**
 * Create emails from several other digests.
 * This collection is stored in the DigestPool.
 *
 * When building an email digest, the first digest returned by the DigestsPool which can group
 * the given email will be used to build an digest email with this email.
 */
class DigestsCollection extends AbstractDigestCollection
{
    /**
     * @var \Passbolt\EmailDigest\Utility\Digest\Digest[]
     */
    private array $digests = [];

    /**
     * Foreach each email entity, it goes through each email digests,
     * check if it can digest the email entity, if yes add emails data to it.
     * The first digest to be picked in the list, if can be used, will be the first and ONLY one digest served for the email.
     *
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface[]
     */
    public function marshalEmails(): array
    {
        $emailDigests = [];
        foreach ($this->getSortedDigests() as $digest) {
            $emailDigests[] = $digest->marshalEmails();
        }

        return $emailDigests;
    }

    /**
     * @return array
     */
    private function getSortedDigests(): array
    {
        $digests = $this->digests;

        // Sort the digests by priority in ascendant order.
        usort($digests, function (Digest $digestA, Digest $digestB) {
            $priorityA = $digestA->getTemplate()->getPriority();
            $priorityB = $digestB->getTemplate()->getPriority();

            if ($priorityA == $priorityB) {
                return 0;
            }
            // -1 if priority of digest A is lower than the priority of digest B
            // 0 if priority of digest A is equal to priority of digest B
            // 1 if priority of digest A is greater than the priority of digest B
            return $priorityA < $priorityB ? -1 : 1;
        });

        return $digests;
    }

    /**
     * Append the email queue to an exiting digest
     * or create a new digest if no digests exist for this operator
     * and template.
     *
     * If the email queue cannot be added to any template, an exception is thrown,
     * which should be caught to create a SingleDigest for this email queue.
     *
     * @param \Cake\ORM\Entity $emailQueue An instance of email entity
     * @return self
     * @throws \Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException
     */
    public function addEmailEntity(Entity $emailQueue): self
    {
        $digestTemplate = $this->getDigestTemplateFromEmailQueueOrFail($emailQueue);
        $operator = $this->getOperatorFromEmailQueue($digestTemplate, $emailQueue);
        $recipient = $emailQueue->get('email');
        $fullBaseUrl = $this->getFullBaseUrlFromEmailQueue($emailQueue);
        // Check if a digest already exists for this email queue
        foreach ($this->digests as $digest) {
            $isSameRecipient = $digest->getRecipient() === $recipient;
            $isSameOperator = $digest->getOperator()->username === $operator->username;
            $isSameFullBaseUrl = $digest->getFullBaseUrl() === $fullBaseUrl;
            $isSameDigestTemplate = $digest->getTemplate() instanceof $digestTemplate;

            if ($isSameRecipient && $isSameOperator && $isSameFullBaseUrl && $isSameDigestTemplate) {
                $digest->addEmail($emailQueue);

                return $this;
            }
        }

        // Otherwise, create a new digest and add this email queue to it
        $this->digests[] = (new Digest($recipient, $operator, $fullBaseUrl, $emailQueue, $digestTemplate));

        return $this;
    }

    /**
     * Return the user from the variable of the email.
     *
     * @param \Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate $digestTemplate digest template for this email queue
     * @param \Cake\ORM\Entity $emailQueue An email queue entity
     * @return \App\Model\Entity\User
     * @throws \Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException if the operator is not set or invalid
     */
    private function getOperatorFromEmailQueue(AbstractDigestTemplate $digestTemplate, Entity $emailQueue): User
    {
        $operator = $emailQueue->get('template_vars')['body'][$digestTemplate->getOperatorVariableKey()] ?? [];
        if (!isset($operator['profile']['first_name']) || !is_string($operator['profile']['first_name'])) {
            throw new UnsupportedEmailDigestDataException($emailQueue);
        }
        if (!isset($operator['profile']['last_name']) || !is_string($operator['profile']['last_name'])) {
            throw new UnsupportedEmailDigestDataException($emailQueue);
        }
        if (!isset($operator['username']) || !EmailValidationRule::check($operator['username'])) {
            throw new UnsupportedEmailDigestDataException($emailQueue);
        }

        return new User($operator);
    }

    /**
     * Return the full base url from the template vars of the email.
     *
     * @param \Cake\ORM\Entity $emailQueue An email queue entity
     * @return string
     * @throws \Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException if the full base url is not defined or empty
     */
    private function getFullBaseUrlFromEmailQueue(Entity $emailQueue): string
    {
        $fullBaseUrl = $emailQueue->get('template_vars')['body']['fullBaseUrl'] ?? null;
        if (empty($fullBaseUrl) || !is_string($fullBaseUrl)) {
            throw new UnsupportedEmailDigestDataException($emailQueue);
        }

        return $fullBaseUrl;
    }

    /**
     * A digest collection can integrate an email entity into one of its digests
     * if at least one of the digests in the template registry accepts this email
     *
     * @param \Cake\ORM\Entity $emailQueue An email entity from email queue
     * @return \Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate return false if no digest in the pool supports the email data
     * @throws \Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException if no digest template was found for this email queue
     */
    private function getDigestTemplateFromEmailQueueOrFail(Entity $emailQueue): AbstractDigestTemplate
    {
        // All the digest templates in the pool
        $allDigestTemplates = DigestTemplateRegistry::getInstance()->getTemplates();
        // Template of the email queue
        $emailTemplate = $emailQueue->get('template');
        foreach ($allDigestTemplates as $template) {
            if (in_array($emailTemplate, $template->getSupportedTemplates())) {
                return $template;
            }
        }

        throw new UnsupportedEmailDigestDataException($emailQueue);
    }
}
