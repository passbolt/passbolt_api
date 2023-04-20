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

use App\Model\Validation\EmailValidationRule;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException;
use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;

/**
 * Default digest to fall back to building a single email
 * Adding more than one email to the digest will return as many "digests" as there is emails.
 */
class Digest extends AbstractDigest implements DigestInterface
{
    public const MAXIMUM_EMAILS_IN_DIGEST = 10;

    /**
     * @var \Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory
     */
    private $emailPreviewFactory;

    /**
     * @var array
     */
    private $emails;

    /**
     * @var array
     */
    private $emailCount;

    /**
     * List of supported templates. An empty list means that all templates are supported.
     * Email with a template included in this list will be part of the same digest.
     *
     * @var string[]
     */
    private $supportedTemplates;

    /**
     * Name of the variable in template vars body which contain the user.
     * i.e. in template, $body['user'] contains the user executing the action, then $executedByTemplateVarKey = 'user';
     * in template, if $body['admin'] contains the user executing the action, then $executedByTemplateVarKey = 'admin'
     *
     * @var string
     */
    private $executedByTemplateVarKey;

    /**
     * Subject of the digest.
     *
     * @var string
     */
    private $subject;

    /**
     * @var callable
     */
    private $onThresholdCallback;

    /**
     * Digest constructor.
     *
     * @param string $subject templates
     * @param array $supportedTemplates subject
     * @param string $executedByTemplateVarKey key to look for user info in email data
     * @param callable $onThresholdCallback what to do when too many emails are present in digest
     * @param \Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory|null $emailPreviewFactory email preview factory
     */
    public function __construct(
        string $subject,
        array $supportedTemplates,
        string $executedByTemplateVarKey,
        callable $onThresholdCallback,
        ?EmailPreviewFactory $emailPreviewFactory = null
    ) {
        $this->supportedTemplates = $supportedTemplates;
        $this->subject = $subject;
        $this->executedByTemplateVarKey = $executedByTemplateVarKey;
        $this->onThresholdCallback = $onThresholdCallback;
        $this->emailPreviewFactory = $emailPreviewFactory ?? new EmailPreviewFactory();
        $this->emails = [];
        $this->emailCount = [];
    }

    /**
     * Handle a collection of digests internally. Each time a new email is added,,
     * it checks if a digest in the collection already exists with the same template than the one from the email to add.
     * If it exists, the email will be part of the same digest, if not a new digest is created for the email.
     *
     * @param \Cake\ORM\Entity $emailQueueEntity An email entity
     * @return $this
     * @throws \Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException
     */
    public function addEmailEntity(Entity $emailQueueEntity)
    {
        if (!$this->canAddToDigest($emailQueueEntity)) {
            throw new UnsupportedEmailDigestDataException($emailQueueEntity);
        }

        $operator = $this->getOperatorFromEmail($emailQueueEntity);
        if (!isset($operator['username']) || !EmailValidationRule::check($operator['username'])) {
            throw new UnsupportedEmailDigestDataException($emailQueueEntity);
        }

        $operator = $operator['username'];
        $username = $emailQueueEntity['email'];
        $this->addToUserCount($username, $operator);

        $this->emails[$username][$operator][] = $emailQueueEntity;

        return $this;
    }

    /**
     * Process and set the content of the emails (as EmailDigest).
     *
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface[]
     */
    public function marshalEmails(): array
    {
        $result = [];
        foreach ($this->emails as $username => $emailsByOperator) {
            foreach ($emailsByOperator as $operator => $emails) {
                $numberOfEmails = $this->getEmailCount($username, $operator);
                if ($numberOfEmails === 1) {
                    $result[] = $this->buildSingleEmailDigest($emails[0]);
                } elseif (!$this->isPassThreshold($username, $operator)) {
                    $result[] = $this->buildMultipleEmailDigest($emails);
                } else {
                    $result[] = $this->onThresholdCallback($emails, $numberOfEmails);
                }
            }
        }

        foreach ($result as $digest) {
            $digest->setContent($this->renderDigestContentFromEmailPreview($this->emailPreviewFactory, $digest));
        }

        /** @var \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface[] $result */
        return $result;
    }

    /**
     * @param array $emails array of EmailQueue Entity
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigest
     */
    public function buildMultipleEmailDigest(array $emails)
    {
        $digest = new EmailDigest();
        foreach ($emails as $i => $emailQueueEntity) {
            $operator = $this->getOperatorFromEmail($emailQueueEntity);
            if (!isset($operator['profile']['first_name']) || !is_string($operator['profile']['first_name'])) {
                throw new UnsupportedEmailDigestDataException($emailQueueEntity);
            }

            $firstName = $operator['profile']['first_name'];
            $digest->addEmailData($emailQueueEntity)
                ->setSubject(__($this->subject, $firstName))
                ->setEmailRecipient($emailQueueEntity->email);
        }

        return $digest;
    }

    /**
     * Single email digest can always add any email.
     *
     * @param \Cake\ORM\Entity $emailQueueEntity An email entity
     * @return bool
     */
    public function canAddToDigest(Entity $emailQueueEntity)
    {
        $executedBy = $this->getOperatorFromEmail($emailQueueEntity);

        return !empty($executedBy) ? $this->isTemplateSupported($emailQueueEntity->get('template')) : false;
    }

    /**
     * Return true if the template is supported by the digest, false otherwise.
     *
     * @param string $template Template to use
     * @return bool
     */
    private function isTemplateSupported(string $template)
    {
        return !empty($this->supportedTemplates) ? in_array($template, $this->supportedTemplates) : true;
    }

    /**
     * Return the user from the variable of the email.
     *
     * @param \Cake\ORM\Entity $emailData An email queue entity
     * @return \App\Model\Entity\User|null
     */
    private function getOperatorFromEmail(Entity $emailData)
    {
        return $emailData->get('template_vars')['body'][$this->executedByTemplateVarKey] ?? null;
    }

    /**
     * Add +1 to the total email count for a given user
     *
     * @param string $username email of the user affected by the action
     * @param string $operator email of the user doing the action
     * @return void
     */
    private function addToUserCount(string $username, string $operator)
    {
        if (!isset($this->emailCount[$username][$operator])) {
            $this->emailCount[$username][$operator] = 1;
        } else {
            $this->emailCount[$username][$operator]++;
        }
    }

    /**
     * Return email count for given user and operator
     *
     * @param string $username email of the user affected by the action
     * @param string $operator email of the user doing the action
     * @return int
     */
    private function getEmailCount(string $username, string $operator)
    {
        if (!isset($this->emailCount[$username]) || !isset($this->emailCount[$username][$operator])) {
            return 0;
        }

        return $this->emailCount[$username][$operator];
    }

    /**
     * Return true if threshold is passed for a given user and operator
     *
     * @param string $username email of the user affected by the action
     * @param string $operator email of the user doing the action
     * @return bool
     */
    private function isPassThreshold(string $username, string $operator)
    {
        return $this->emailCount[$username][$operator] > self::MAXIMUM_EMAILS_IN_DIGEST;
    }

    /**
     * Callback executed when the maximum threshold defined is reached.
     *
     * Must return an array of email digests.
     *
     * @param \Cake\ORM\Entity[] $emailQueueEntities An email queue entities
     * @param int $emailCount Count of the emails
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface[]
     */
    private function onThresholdCallback(array $emailQueueEntities, int $emailCount)
    {
        return call_user_func($this->onThresholdCallback, $emailQueueEntities, $emailCount);
    }
}
