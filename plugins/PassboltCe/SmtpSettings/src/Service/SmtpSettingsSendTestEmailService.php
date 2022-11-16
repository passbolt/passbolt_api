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
 * @since         3.8.0
 */
namespace Passbolt\SmtpSettings\Service;

use App\Error\Exception\FormValidationException;
use App\Mailer\Transport\DebugSmtpTransport;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\TestSuite\TestEmailTransport;
use Passbolt\SmtpSettings\Form\EmailConfigurationForm;

class SmtpSettingsSendTestEmailService
{
    /**
     * Transport class to be used for testing.
     * We use our own DebugSmtp that will get the server communication trace.
     */
    public const TRANSPORT_CLASS_NAME_DEBUG_SMTP = 'DebugSmtp';

    /**
     * Name of the transport configuration that we'll use.
     * (will be created on the fly).
     */
    public const TRANSPORT_CONFIG_NAME_DEBUG_EMAIL = 'debugEmail';

    /**
     * Name of the field in the form defining the recipient of the test email
     */
    public const EMAIL_TEST_TO = 'email_test_to';

    /**
     * @var \Cake\Mailer\Mailer|null
     */
    private $email;

    /**
     * @param array $smtpSettings SMTP Settings passed in the payload
     * @return \Cake\Mailer\Mailer
     * @throws \App\Error\Exception\FormValidationException if the settings passed do not validate the EmailConfigurationForm
     */
    public function sendTestEmail(array $smtpSettings): Mailer
    {
        $smtpSettings = $this->validateAndGetSmtpSettings($smtpSettings);

        $this->email = new Mailer('default');
        $this->setDebugTransport($smtpSettings);
        $this->sendEmail($smtpSettings);

        return $this->email;
    }

    /**
     * @return array
     */
    public function getTrace(): array
    {
        if (is_null($this->email)) {
            return [];
        }

        $transport = $this->email->getTransport();

        if ($transport instanceof DebugSmtpTransport) {
            return $transport->getTrace();
        }

        return [];
    }

    /**
     * @param array $smtpSettings SMTP Settings of the email
     * @return void
     */
    protected function sendEmail(array $smtpSettings): void
    {
        $this->email
            ->setFrom([
                $smtpSettings['sender_email'] => $smtpSettings['sender_name'],
            ])
            ->setTo($smtpSettings[self::EMAIL_TEST_TO])
            ->setSubject(__('Passbolt test email'))
            ->deliver($this->getDefaultMessage());
    }

    /**
     * @param array $data Data in the payload
     * @return array
     * @throws \App\Error\Exception\FormValidationException if the data passed do not validate the EmailConfigurationForm
     */
    public function validateAndGetSmtpSettings(array $data): array
    {
        $form = new EmailConfigurationForm();
        $form
            ->getValidator()
            ->requirePresence(self::EMAIL_TEST_TO, 'create', __('A test recipient is required.'));

        if (!$form->execute($data)) {
            throw new FormValidationException(__('Could not validate the smtp settings.'), $form);
        }

        return (array)$form->getData();
    }

    /**
     * Set a custom transport class name.
     * In the context of this debugger, we'll use our own class name.
     *
     * @param array $data request data
     * @return void
     */
    protected function setDebugTransport(array $data): void
    {
        if ($this->isRunningOnTestEnvironment()) {
            return;
        }
        $transportConfig = TransportFactory::getConfig('default');
        $transportConfig['className'] = self::TRANSPORT_CLASS_NAME_DEBUG_SMTP;
        $transportConfig['host'] = $data['host'];
        $transportConfig['port'] = $data['port'];
        $transportConfig['username'] = empty($data['username']) ? null : $data['username'];
        $transportConfig['password'] = empty($data['password']) ? null : $data['password'];
        $transportConfig['tls'] = ($data['tls'] == '1' ? true : null);
        TransportFactory::setConfig(self::TRANSPORT_CONFIG_NAME_DEBUG_EMAIL, $transportConfig);
        $this->email->setTransport(self::TRANSPORT_CONFIG_NAME_DEBUG_EMAIL);
    }

    /**
     * Get default message (email content).
     *
     * @return string
     */
    protected function getDefaultMessage(): string
    {
        return __('Congratulations!') . "\n" .
            __('If you receive this email, it means that your passbolt smtp configuration is working fine.');
    }

    /**
     * We exceptionally need here to detect test environment in order to make
     * the sending of email testable.
     *
     * @return bool
     */
    public function isRunningOnTestEnvironment(): bool
    {
        return TransportFactory::getConfig('default')['className'] === TestEmailTransport::class;
    }
}
