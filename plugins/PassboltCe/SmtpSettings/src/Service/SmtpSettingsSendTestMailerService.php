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
 * @since         4.1.0
 */
namespace Passbolt\SmtpSettings\Service;

use App\Mailer\Transport\DebugSmtpTransport;
use App\Mailer\Transport\DebugTransport;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;

class SmtpSettingsSendTestMailerService
{
    /**
     * Name of the field in the form defining the recipient of the test email
     */
    public const EMAIL_TEST_TO = 'email_test_to';

    /**
     * @var \Cake\Mailer\Mailer|null
     */
    private $email = null;

    /**
     * @var array
     */
    private $smtpSettings = [];

    /**
     * Sends an email.
     *
     * @param array $smtpSettings SMTP settings.
     * @return void
     */
    public function sendEmail(array $smtpSettings): void
    {
        $this->setSmtpSettings($smtpSettings);

        $this->email
            ->setFrom([$this->smtpSettings['sender_email'] => $this->smtpSettings['sender_name']])
            ->setTo($this->smtpSettings[self::EMAIL_TEST_TO])
            ->setSubject(__('Passbolt test email'))
            ->deliver($this->getDefaultMessage());
    }

    /**
     * @param array $smtpSettings SMTP settings.
     * @return void
     */
    private function setSmtpSettings(array $smtpSettings): void
    {
        $this->smtpSettings = $smtpSettings;

        $config = [
            'className' => DebugSmtpTransport::class,
            'host' => $smtpSettings['host'],
            'port' => $smtpSettings['port'],
            'username' => empty($smtpSettings['username']) ? null : $smtpSettings['username'],
            'password' => $smtpSettings['password'],
            'tls' => $smtpSettings['tls'],
            'client' => $smtpSettings['client'],
        ];

        // Merge SSL Options if present in config
        $sslOptions = (new SmtpSettingsSslOptionsGetService())->get();
        if (!empty($sslOptions)) {
            $config['context'] = ['ssl' => $sslOptions];
        }

        if ($this->isRunningOnTestEnvironment()) {
            $debugTransport = TransportFactory::get('default');
        } else {
            $debugTransport = new DebugSmtpTransport($config);
        }

        $this->email = new Mailer(['transport' => $debugTransport]);
    }

    /**
     * Returns the traces from the email.
     *
     * @return array
     */
    public function getTrace(): array
    {
        if (is_null($this->email)) {
            return [];
        }

        /** @var \App\Mailer\Transport\DebugSmtpTransport|null $transport */
        $transport = $this->email->getTransport();

        return $transport->getTrace();
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
