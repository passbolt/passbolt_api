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
 * @since         2.0.0
 */
namespace App\Command;

use App\Mailer\Transport\DebugTransport;
use App\Mailer\Transport\SmtpTransport;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetService;
use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestEmailService;

class SendTestEmailCommand extends PassboltCommand
{
    /**
     * @var \Passbolt\SmtpSettings\Service\SmtpSettingsSendTestEmailService
     */
    public $sendTestEmailService;

    /**
     * Injects the service to facilitate the unit testing of the command
     *
     * @param \Passbolt\SmtpSettings\Service\SmtpSettingsSendTestEmailService $sendTestEmailService Service to send test email.
     */
    public function __construct(SmtpSettingsSendTestEmailService $sendTestEmailService)
    {
        parent::__construct();
        $this->sendTestEmailService = $sendTestEmailService;
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Debug Email shell for the passbolt application.'));

        $parser->addOption('recipient', [
            'short' => 'r',
            'help' => __('The recipient whom to send the test email to'),
            'required' => true,
        ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $io->out(' Debug email shell');
        $io->hr();

        $recipient = $args->getOption('recipient');

        /** Validate recipient email. */
        if (! Validation::email($recipient, Configure::read('passbolt.email.validate.mx'))) {
            $this->error(__('The recipient should be a valid email address.', $recipient), $io);
            $this->abort();
        }

        $this->checkSmtpIsSet($io);
        try {
            $transportConfig = (new SmtpSettingsGetService())->getSettings();
        } catch (\Throwable $e) {
            $this->error($e->getMessage(), $io);
            $this->abort();
        }

        $transportConfig[SmtpSettingsSendTestEmailService::EMAIL_TEST_TO] = $recipient;

        $this->checkFromIsSet($transportConfig, $io);

        $this->displayConfiguration($transportConfig, $recipient, $io);
        $this->sendEmail($transportConfig, $args, $io);
        $this->displayTrace($this->sendTestEmailService->getTrace(), $io);

        $io->nl(0);
        $this->success('The message has been successfully sent!', $io);

        return $this->successCode();
    }

    /**
     * Get email from config and return it as a human readable string.
     * The Email from parameter in the config can take either a string or an array. The purpose
     * of this function is to provided a standardized way to display the from field.
     *
     * @param array $transportConfig Transport configuration
     * @return string
     */
    protected function getEmailFromAsString(array $transportConfig): string
    {
        /** @var array|string $from */
        $from = $this->getFromInTransportConfig($transportConfig) ?? '';
        if (is_array($from)) {
            $emailFrom = key($from);
            $nameFrom = $from[$emailFrom];

            return "$nameFrom <$emailFrom>";
        } else {
            return $from;
        }
    }

    /**
     * Display configuration options.
     *
     * @param array $transportConfig Transport configuration.
     * @param string $recipient Recipient email address.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayConfiguration(array $transportConfig, string $recipient, ConsoleIo $io): void
    {
        $io->nl(0);
        $io->out('<info>Email configuration</info>');
        $io->hr();
        $io->out(__('Host: {0}', $transportConfig['host']));
        $io->out(__('Port: {0}', $transportConfig['port']));
        $io->out(__('Username: {0}', $transportConfig['username'] ?? ''));
        $io->out(__('Password: {0}', '*********'));
        $io->out(__('TLS: {0}', $transportConfig['tls'] == null ? 'false' : 'true'));
        $io->nl(0);
        $io->out(__('Sending email from: {0}', $this->getEmailFromAsString($transportConfig)));
        $io->out(__('Sending email to: {0}', $recipient));
        $io->hr();
    }

    /**
     * Get recipient email address.
     *
     * @param \Cake\Console\Arguments $args Arguments.
     * @return string
     */
    protected function getRecipient(Arguments $args): string
    {
        $recipient = $args->getOption('recipient');
        if (!is_string($recipient) || empty($recipient)) {
            $recipient = 'no-reply@passbolt.com';
        }

        return $recipient;
    }

    /**
     * Send email and display the trace.
     *
     * @param array $transportConfig transport configuration
     * @param \Cake\Console\Arguments $args transport configuration
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function sendEmail(array $transportConfig, Arguments $args, ConsoleIo $io): void
    {
        try {
            $this->sendTestEmailService->sendTestEmail($transportConfig);
        } catch (\Exception $e) {
            $this->displayTrace($this->sendTestEmailService->getTrace(), $io);
            $io->nl(0);
            $this->error(__('Could not send the test email.'), $io);
            $this->error(__('Error: {0}', $e->getMessage()), $io);
            $this->abort();
        }
    }

    /**
     * Display trace of the communication with the server.
     *
     * @param array $trace Trace of the email
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayTrace(array $trace, ConsoleIo $io): void
    {
        $io->nl(0);
        $io->out('<info>Trace</info>');
        foreach ($trace as $entry) {
            if (isset($entry['cmd'])) {
                $cmd = $this->removeCredentials($entry['cmd']);
                $io->out("<info> {$cmd}</info>");
            }
            if (!empty($entry['response'])) {
                foreach ($entry['response'] as $response) {
                    $msg = $this->removeCredentials($response['message']);
                    $io->out("[{$response['code']}] {$msg}");
                }
            }
        }
    }

    /**
     * Remove credentials (username and password) from a string.
     *
     * @param string $str string where to remove the credentials
     * @return mixed
     */
    protected function removeCredentials($str)
    {
        $toReplace = [];
        $replaceMask = '*****';
        $replaceWith = [];
        $transportConfig = TransportFactory::getConfig('default');

        if (isset($transportConfig['username'])) {
            $usernameEncoded = base64_encode($transportConfig['username']);
            $usernameClear = $transportConfig['username'];
            $toReplace[] = $usernameClear;
            $replaceWith[] = $replaceMask;
            $toReplace[] = $usernameEncoded;
            $replaceWith[] = $replaceMask;
        }
        if (isset($transportConfig['password'])) {
            $passwordEncoded = base64_encode($transportConfig['password']);
            $passwordClear = $transportConfig['password'];
            $toReplace[] = $passwordEncoded;
            $replaceWith[] = $replaceMask;
            $toReplace[] = $passwordClear;
            $replaceWith[] = $replaceMask;
        }

        return str_replace($toReplace, $replaceWith, $str);
    }

    /**
     * Check if Smtp is set as the transporter in the configuration.
     * Exit if smtp is not set and display an error message.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function checkSmtpIsSet(ConsoleIo $io): void
    {
        $transportConfig = TransportFactory::getConfig('default');
        $className = Hash::get($transportConfig, 'className');
        if ($className != 'Smtp' && $className != SmtpTransport::class && $className !== DebugTransport::class) {
            $msg = __('Your email transport configuration is not set to use "Smtp". ({0} is set instead)', $className);
            $this->error($msg, $io);
            $this->error(__('This email debug task is only for SMTP configurations.'), $io);
            $msg = __('To fix this, edit EmailTransport.default.className in passbolt.php, and set className to "Smtp"');// phpcs:ignore
            $this->error($msg, $io);
            $this->abort();
        }
    }

    /**
     * Check if a default from is provided in the configuration.
     * Exit if none is provided and display an error message.
     *
     * @param array $transportConfig Transport configuration.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function checkFromIsSet(array $transportConfig, ConsoleIo $io)
    {
        $from = $this->getFromInTransportConfig($transportConfig);

        if (empty($from)) {
            $this->error(__('Your email configuration doesn\'t define a default "from"'), $io);
            $msg = __('To fix this, edit Email.default.from property in /config/passbolt.php') . ' ';
            $msg .= __('And add \'from\' => [\'passbolt@your_organization.com\' => \'Passbolt\']');
            $this->error($msg, $io);
            $this->abort();
        }
    }

    /**
     * Get the sender based on the transport config.
     *
     * @param array $transportConfig Transport Config.
     * @return array|null
     */
    protected function getFromInTransportConfig(array $transportConfig): ?array
    {
        // If the SMTP settings are in the DB, read the sender and sender_email from the DB settings
        if ($transportConfig['source'] === SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_DB) {
            return [$transportConfig['sender_email'] => $transportConfig['sender_name']];
        }

        // Else read the sender and sender_email from the config files
        $emailConfig = Mailer::getConfig('default');

        return Hash::get($emailConfig, 'from');
    }
}
