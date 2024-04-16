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

namespace Passbolt\EmailDigest\Utility\Factory;

use Cake\Mailer\Mailer;
use Cake\Mailer\Renderer;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Utility\Digest\Digest;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface;
use Passbolt\EmailDigest\Utility\Mailer\EmailPreview;
use Passbolt\Locale\Event\LocaleEmailQueueListener;

/**
 * Create email previews from an email entity or from an email digest.
 *
 * It allow to render an email with or without a layout in what is called an EmailPreview.
 * The email is rendered exactly as it would be send.
 * The produced EmailPreview also contains the headers as it would have been sent.
 *
 * @see EmailPreview
 *
 * It can:
 * - create previews from emailqueue entities which are used to create digests.
 * - create previews from EmailDigests which are used to preview the email before it is send.
 */
class EmailPreviewFactory
{
    /**
     * Create a snapshot of the email as it would be rendered from an email digest.
     *
     * @param \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface $emailDigest Email digest to get a snapshot of
     * @param string|null $layout Layout file name to set.
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailPreview
     */
    public function renderEmailPreviewFromDigest(
        EmailDigestInterface $emailDigest,
        ?string $layout = null
    ): EmailPreview {
        $email = $this->mapEmailDigestToMailerEmail(new Mailer('default'), $emailDigest);

        $this->configureEmailView($email, $emailDigest->getTemplate(), $layout);

        return $this->renderEmailContent($email);
    }

    /**
     * Render email with given email entity.
     *
     * @param \Cake\ORM\Entity $email Email entity.
     * @return string
     */
    private function renderFromEmailEntity(Entity $email): string
    {
        $viewVars = empty($email->template_vars) ? [] : $email->template_vars;
        $viewVars['locale'] = $email->get('template_vars')['locale'];

        $renderer = new Renderer();
        $renderer
            ->viewBuilder()
            ->setTemplate($email->get('template'))
            ->setVars($viewVars)
            ->setLayout('Passbolt/EmailDigest.digest');

        return $renderer->render('', ['html'])['html'];
    }

    /**
     * @param \Cake\ORM\Entity $emailQueueEntity entity
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigest
     */
    public function buildSingleEmailDigest(Entity $emailQueueEntity): EmailDigest
    {
        return (new EmailDigest())
            ->addEmailData($emailQueueEntity)
            ->setSubject($emailQueueEntity->get('subject'))
            ->setEmailIds([$emailQueueEntity->id])
            ->setEmailRecipient($emailQueueEntity->get('email'))
            ->setFullBaseUrl($emailQueueEntity->get('template_vars')['body']['fullBaseUrl'] ?? '/');
    }

    /**
     * Renders an email digest with multiple emails rendered in it
     *
     * @param \Passbolt\EmailDigest\Utility\Digest\Digest $digest digest to render
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigest
     */
    public function buildMultipleEmailDigest(Digest $digest): EmailDigest
    {
        $subject = $digest->getTemplate()->getTranslatedSubject($digest);

        $emailDigest = (new EmailDigest())
            ->setSubject($subject)
            ->setEmailRecipient($digest->getRecipient())
            ->setEmailIds($digest->getEmailQueueIds())
            ->setFullBaseUrl($digest->getFullBaseUrl());
        foreach ($digest->getEmailQueues() as $emailQueueEntity) {
            $emailDigest->addEmailData($emailQueueEntity);
        }

        return $emailDigest;
    }

    /**
     * Renders an email with a summary of what happened in the 11+ emails of the digest
     *
     * @param \Passbolt\EmailDigest\Utility\Digest\Digest $digest digest
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailDigest
     */
    public function buildSummaryEmailDigest(Digest $digest): EmailDigest
    {
        $template = $digest->getTemplate();
        $subject = $template->getTranslatedSubject($digest);

        return (new EmailDigest())
            ->addEmailData($digest->getFirstEmailQueue())
            ->setEmailIds($digest->getEmailQueueIds())
            ->setSubject($subject)
            ->setFullBaseUrl($digest->getFullBaseUrl())
            ->addLayoutVar(LocaleEmailQueueListener::VIEW_VAR_KEY, $digest->getLocale())
            ->setTemplate($template->getDigestTemplate())
            ->setEmailRecipient($digest->getRecipient())
            ->addTemplateVar($template->getOperatorVariableKey(), $digest->getOperator())
            ->addTemplateVar('fullBaseUrl', $digest->getFullBaseUrl())
            ->addTemplateVar('subject', $subject)
            ->addTemplateVar('count', $digest->getEmailQueueCount());
    }

    /**
     * Helper method which render the content of every emails contained in a digest into a string to be used
     * as the content of the digest.
     *
     * @param \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface $emailDigest Email digest to use to render
     * @return string
     */
    public function renderDigestContentFromEmailPreview(EmailDigestInterface $emailDigest): string
    {
        $emailDigestContent = [];
        foreach ($emailDigest->getEmailsData() as $emailData) {
            $emailDigestContent[] = $this->renderFromEmailEntity($emailData);
        }

        return implode('', $emailDigestContent);
    }

    /**
     * Return a preview of the email as it was sent with its headers and its content.
     * The Email::send() method handle the render of the email. It call the send method of its configured
     * transport once its render is done which can be retrieved through Email::getContent() method.
     * The trick is to attach a Debug transport which do not send the email but which gives us instead an
     * opportunity to retrieve the content and the headers of the email as it would be send.
     *
     * @param \Cake\Mailer\Mailer $email A Mailer email
     * @return \Passbolt\EmailDigest\Utility\Mailer\EmailPreview
     * @see Email::send()
     * @see DebugTransport::send()
     */
    private function renderEmailContent(Mailer $email): EmailPreview
    {
        # @todo remove the usage of the Debug transport for rendering here
        $email->setTransport('Debug');
        /** @var \App\Mailer\Transport\DebugTransport $transport */
        $transport = $email->getTransport();
        // Debug keeps in memory pas emails. This is here not required nor wanted to avoid memory limit issues
        $transport->clearMessages();

        $contents = $email->send();

        return new EmailPreview($contents['headers'], $contents['message']);
    }

    /**
     * Configure the email view for a Mailer email, theme, template, layout can be changed.
     *
     * @param \Cake\Mailer\Mailer $email An Email
     * @param string $template Template
     * @param string|null $layout Layout file name to set.
     * @param string|null $theme Theme name.
     * @return void
     */
    private function configureEmailView(
        Mailer $email,
        string $template,
        ?string $layout = null,
        ?string $theme = null
    ): void {
        $email->viewBuilder()
            ->setVar('title', 'Email digest preview')
            ->setLayout($layout)
            ->setTheme($theme)
            ->setTemplate($template);
    }

    /**
     * Map an instance of EmailDigest to an instance of Email, so it can be sent.
     *
     * @param \Cake\Mailer\Mailer $email An instance of Email
     * @param \Passbolt\EmailDigest\Utility\Mailer\EmailDigestInterface $emailDigest An instance of EmailDigest
     * @return \Cake\Mailer\Mailer
     */
    private function mapEmailDigestToMailerEmail(Mailer $email, EmailDigestInterface $emailDigest): Mailer
    {
        $emailData = $emailDigest->getEmailsData()[0];
        if (!empty($emailData->from_email) && !empty($emailData->from_name)) {
            $email->setFrom($emailData->from_email, $emailData->from_name);
        }

        return $email
            ->setTo($emailDigest->getEmailRecipient())
            ->setSubject($emailDigest->getSubject())
            ->setEmailFormat($emailDigest->getEmailFormat())
            ->setMessageId(false)
            ->setViewVars($emailDigest->getViewVars())
            ->setReturnPath($email->getFrom());
    }
}
