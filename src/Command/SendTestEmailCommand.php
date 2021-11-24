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

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\TestSuite\TestEmailTransport;
use Cake\Utility\Hash;

class SendTestEmailCommand extends PassboltCommand
{
    /**
     * Name of the transport configuration that we'll use.
     * (will be created on the fly).
     */
    public const TRANSPORT_CONFIG_NAME = 'debugEmail';

    /**
     * Transport class to be used for testing.
     * We use our own DebugSmtp that will get the server communication trace.
     */
    public const TRANSPORT_CLASS = 'DebugSmtp';

    /**
     * Instance of the Email component.
     *
     * @var \Cake\Mailer\Mailer Email
     */
    public $email;

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Debug Email shell for the passbolt application.'));

        $parser->addOption('recipient', [
            'short' => 'r',
            'help' => __('The recipient whom to send the test email to'),
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

        $this->checkSmtpIsSet($io);
        $this->checkFromIsSet($io);

        $this->displayConfiguration($args, $io);

        $this->email = new Mailer('default');
        $this->setCustomTransportClassName(self::TRANSPORT_CLASS);

        $this->sendEmail(
            $this->getRecipient($args),
            'Passbolt test email',
            $this->getDefaultMessage(),
            $io
        );
        if (!$this->isRunningOnTestEnvironment()) {
            $this->displayTrace($io);
        }
        $io->nl(0);
        $this->success('The message has been successfully sent!', $io);

        return $this->successCode();
    }

    /**
     * Get email from config and return it as a human readable string.
     * The Email from parameter in the config can take either a string or an array. The purpose
     * of this function is to provided a standardized way to display the from field.
     *
     * @return string
     */
    protected static function getEmailFromAsString(): string
    {
        $config = Mailer::getConfig('default');
        /** @var array|string $from */
        $from = $config['from'] ?? '';
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
     * @param \Cake\Console\Arguments $args Arguments.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayConfiguration(Arguments $args, ConsoleIo $io): void
    {
        $transportConfig = TransportFactory::getConfig('default');

        $io->nl(0);
        $io->out('<info>Email configuration</info>');
        $io->hr();
        $io->out(__('Host: {0}', $transportConfig['host']));
        $io->out(__('Port: {0}', $transportConfig['port']));
        $io->out(__('Username: {0}', $transportConfig['username'] ?? ''));
        $io->out(__('Password: {0}', '*********'));
        $io->out(__('TLS: {0}', $transportConfig['tls'] == null ? 'false' : 'true'));
        $io->nl(0);
        $io->out(__('Sending email from: {0}', self::getEmailFromAsString()));
        $io->out(__('Sending email to: {0}', $this->getRecipient($args)));
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
            $recipient = 'doesnotexist@passboltdummydomain.com';
        }

        return $recipient;
    }

    /**
     * Send email and display the trace.
     *
     * @param string $to email address to send the email to
     * @param string $subject the subject of the email
     * @param string $message the content of the email
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function sendEmail($to, $subject, $message, ConsoleIo $io): void
    {
        $config = Mailer::getConfig('default');
        try {
            $this->email
                ->setFrom($config['from'])
                ->setTo($to)
                ->setSubject($subject)
                ->deliver($message);
        } catch (\Exception $e) {
            $this->displayTrace($io);
            $io->nl(0);
            $this->error(__('Could not send the test email.'), $io);
            $this->error(__('Error: {0}', $e->getMessage()), $io);
            $this->abort();
        }
    }

    /**
     * Display trace of the communication with the server.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayTrace(ConsoleIo $io): void
    {
        /** @var \App\Mailer\Transport\DebugSmtpTransport $transport */
        $transport = $this->email->getTransport();
        $trace = $transport->getTrace();

        $io->nl(0);
        $io->out('<info>Trace</info>');
        foreach ($trace as $entry) {
            if (isset($entry['cmd'])) {
                $cmd = $this->removeCredentials($entry['cmd']);
                $io->out("<info>> {$cmd}</info>");
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
     * Get default message (email content).
     *
     * @return string
     */
    protected function getDefaultMessage(): string
    {
        $message = "Congratulations!\n" .
        'If you receive this email, it means that your passbolt smtp configuration is working fine.';

        return $message;
    }

    /**
     * Set a custom transport class name.
     * In the context of this debugger, we'll use our own class name.
     *
     * @param string $customTransportClassName name of the custom transport class to use
     * @return void
     */
    protected function setCustomTransportClassName(string $customTransportClassName): void
    {
        // Return if we are in test context. This will enable the sending of email to be tested.
        if ($this->isRunningOnTestEnvironment()) {
            return;
        }
        $transportConfig = TransportFactory::getConfig('default');
        $transportConfig['className'] = $customTransportClassName;
        TransportFactory::setConfig(self::TRANSPORT_CONFIG_NAME, $transportConfig);
        $this->email->setTransport(self::TRANSPORT_CONFIG_NAME);
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
        if ($className != 'Smtp' && $className !== TestEmailTransport::class) {
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
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function checkFromIsSet(ConsoleIo $io)
    {
        $emailConfig = Mailer::getConfig('default');
        $from = Hash::get($emailConfig, 'from');
        if (empty($from)) {
            $this->error(__('Your email configuration doesn\'t define a default "from"'), $io);
            $msg = __('To fix this, edit Email.default.from property in /config/passbolt.php') . ' ';
            $msg .= _('And add \'from\' => [\'passbolt@your_organization.com\' => \'Passbolt\']');
            $this->error($msg, $io);
            $this->abort();
        }
    }

    /**
     * We exceptionally need here to detect test environment in order to make
     * the sending of email testable.
     *
     * @return bool
     */
    protected function isRunningOnTestEnvironment(): bool
    {
        return TransportFactory::getConfig('default')['className'] === TestEmailTransport::class;
    }
}
