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
use App\Mailer\Transport\DebugTransport;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Passbolt\SmtpSettings\Event\SmtpTransportSendTestEmailEventListener;
use Passbolt\SmtpSettings\Form\EmailConfigurationForm;

class SmtpSettingsSendTestEmailService
{
    use EventDispatcherTrait;

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

        // Do not assign the sender as found in the DB settings
        // as we use the one provided in the $smtpSettings
        EventManager::instance()->on(new SmtpTransportSendTestEmailEventListener());
        $this->email = $this->getDebugEmail($smtpSettings);
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

        /** @var \App\Mailer\Transport\DebugSmtpTransport $transport */
        $transport = $this->email->getTransport();

        return $transport->getTrace();
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

        if (!$form->execute($data, ['validate' => 'sendTestEmail'])) {
            throw new FormValidationException(__('Could not validate the smtp settings.'), $form);
        }

        return (array)$form->getData();
    }

    /**
     * Get an email with custom transport class name.
     * In the context of this debugger, we'll use our own class name.
     *
     * @param array $data request data
     * @return \Cake\Mailer\Mailer
     */
    protected function getDebugEmail(array $data): Mailer
    {
        $config = [
            'className' => DebugSmtpTransport::class,
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => empty($data['username']) ? null : $data['username'],
            'password' => $data['password'],
            'tls' => $data['tls'],
            'client' => $data['client'],
        ];
        if ($this->isRunningOnTestEnvironment()) {
            $debugTransport = TransportFactory::get('default');
        } else {
            $debugTransport = new DebugSmtpTransport($config);
        }

        return new Mailer([
            'transport' => $debugTransport,
        ]);
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
        return TransportFactory::get('default') instanceof DebugTransport;
    }
}
