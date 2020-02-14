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
 * @since         2.14.0
 */

namespace Passbolt\EmailDigest\Utility\Marshaller\Type;

use App\Model\Entity\User;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Exception\UnsupportedEmailDigestDataException;
use Passbolt\EmailDigest\Utility\Factory\EmailPreviewFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerInterface;

/**
 * Aggregate in a same digest the emails with a template in the list of the marshaller's supported template
 */
class ByTemplateAndExecutedByDigestMarshaller extends AbstractDigestMarshaller implements DigestMarshallerInterface
{
    /**
     * @var EmailPreviewFactory
     */
    private $emailPreviewFactory;

    /**
     * @var EmailDigestInterface[]
     */
    private $digests = [];

    /**
     * List of supported templates. An empty list means that all templates are supported.
     * Email with a template included in this list will be part of the same digest.
     * @var string[]
     */
    private $supportedTemplates = [];

    /**
     * Subject of the digest.
     * @var string
     */
    private $subject;

    /**
     * Name of the variable in template vars body which contain the user.
     * i.e. in template, $body['user'] contains the user executing the action, then $executedByTemplateVarKey = 'user';
     * in template, if $body['admin'] contains the user executing the action, then $executedByTemplateVarKey = 'admin'
     * @var string
     */
    private $executedByTemplateVarKey;

    /**
     * @param string $subject Subject of the email to use when emails are aggregated
     * @param string $executedByTemplateVarKey Name of the variable in template vars body which contain the user
     * @param EmailPreviewFactory $emailPreviewFactory Factory of email snapshots
     */
    public function __construct(string $subject, string $executedByTemplateVarKey, EmailPreviewFactory $emailPreviewFactory = null)
    {
        $this->emailPreviewFactory = $emailPreviewFactory ?? new EmailPreviewFactory();
        $this->subject = $subject;
        $this->executedByTemplateVarKey = $executedByTemplateVarKey;
    }

    /**
     * Handle a collection of digests internally. Each time a new email is added,,
     * it checks if a digest in the collection already exists with the same template than the one from the email to add.
     * If it exists, the email will be part of the same digest, if not a new digest is created for the email.
     *
     * @param Entity $emailQueueEntity An email entity
     * @return $this
     * @throws UnsupportedEmailDigestDataException
     */
    public function addEmailEntityToMarshal(Entity $emailQueueEntity)
    {
        if (!$this->canMarshalDigestsFrom($emailQueueEntity)) {
            throw new UnsupportedEmailDigestDataException($emailQueueEntity);
        }

        $user = $this->getExecutedByUserFromEmail($emailQueueEntity);
        $username = $user->username;
        $firstName = $user->profile->first_name;

        $digest = $this->digests[$username] ?? new EmailDigest();

        $this->digests[$username] = $digest->addEmailData($emailQueueEntity)
            ->setSubject($this->getSubject($firstName))
            ->setEmailRecipient($emailQueueEntity->email);

        return $this;
    }

    /**
     * Return true if the email contain a user variable and if the template is supported
     * @param Entity $emailQueueEntity An email data
     * @return bool
     */
    public function canMarshalDigestsFrom(Entity $emailQueueEntity)
    {
        $executedBy = $this->getExecutedByUserFromEmail($emailQueueEntity);

        return !empty($executedBy) ? $this->isTemplateSupported($emailQueueEntity->template) : false;
    }

    /**
     * Render the previews of the emails and se and set the content of the digests it.
     * @return EmailDigestInterface[]
     */
    public function marshalDigests()
    {
        $digests = [];

        foreach ($this->digests as $username => $digest) {
            $digests[] = $digest->setContent(
                $this->renderDigestContentFromEmailPreview($this->emailPreviewFactory, $digest)
            );
        }

        return $digests;
    }

    /**
     * @param string $username Username of the user who executed the action
     * @return string
     */
    public function getSubject(string $username)
    {
        return __($this->subject, $username);
    }

    /**
     * Add a supported template to the list of supported templates for aggregate.
     * Email with a template included in this list will be part of the same digest.
     * @param string $template Template
     * @return $this
     */
    public function addSupportedTemplate(string $template)
    {
        $this->supportedTemplates[] = $template;

        return $this;
    }

    /**
     * Return true if the template is supported by the marshaller, false otherwise.
     * @param string $template Template to use
     * @return bool
     */
    private function isTemplateSupported(string $template)
    {
        return !empty($this->supportedTemplates) ? in_array($template, $this->supportedTemplates) : true;
    }

    /**
     * Return the user from the variable of the email.
     * @param Entity $emailData An email queue entity
     * @return User|null
     */
    private function getExecutedByUserFromEmail(Entity $emailData)
    {
        return $emailData->template_vars['body'][$this->executedByTemplateVarKey] ?? null;
    }
}
