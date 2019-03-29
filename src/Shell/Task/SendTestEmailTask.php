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
 * @since         2.0.0
 */
namespace App\Shell\Task;

use App\Shell\AppShell;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Cake\Utility\Hash;

class SendTestEmailTask extends AppShell
{
    /**
     * Name of the transport configuration that we'll use.
     * (will be created on the fly).
     */
    const TRANSPORT_CONFIG_NAME = 'debugEmail';

    /**
     * Transport class to be used for testing.
     * We use our own DebugSmtp that will get the server communication trace.
     */
    const TRANSPORT_CLASS = 'DebugSmtp';

    /**
     * Instance of the Email component.
     * @var Cake/Mailer/Email Email
     */
    public $email = null;

    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(__('Debug Email shell for the passbolt application.'));

        $parser->addOption('recipient', [
            'short' => 'r',
            'help' => __('The recipient whom to send the test email to')
        ]);

        return $parser;
    }

    /**
     * Main debug email task
     *
     * @return bool
     */
    public function main()
    {
        $this->out(' Debug email shell');
        $this->hr();

        $this->_checkSmtpIsSet();
        $this->_checkFromIsSet();

        $this->_displayConfiguration();

        $this->email = new Email('default');
        $this->_setCustomTransportClassName(self::TRANSPORT_CLASS);

        $this->_sendEmail(
            $this->_getRecipient(),
            'Passbolt test email',
            $this->_getDefaultMessage()
        );

        $this->_displayTrace();
        $this->out($this->nl(0));
        $this->_success("The message has been successfully sent!");

        return true;
    }

    /**
     * Get email from config and return it as a human readable string.
     * The Email from parameter in the config can take either a string or an array. The purpose
     * of this function is to provided a standardized way to display the from field.
     * @return array|string
     */
    protected static function _getEmailFromAsString()
    {
        $config = Email::getConfig('default');
        $from = isset($config['from']) ? $config['from'] : '';
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
     * @return void
     */
    protected function _displayConfiguration()
    {
        $transportConfig = TransportFactory::getConfig('default');

        $this->out($this->nl(0));
        $this->out('<info>Email configuration</info>');
        $this->hr();
        $this->out(__('Host: {0}', $transportConfig['host']));
        $this->out(__('Port: {0}', $transportConfig['port']));
        $this->out(__('Username: {0}', $transportConfig['username']));
        $this->out(__('Password: {0}', '*********'));
        $this->out(__('TLS: {0}', $transportConfig['tls'] == null ? 'false' : 'true'));
        $this->out($this->nl(0));
        $this->out(__('Sending email from: {0}', self::_getEmailFromAsString()));
        $this->out(__('Sending email to: {0}', $this->_getRecipient()));
        $this->hr();
    }

    /**
     * Get recipient email address.
     * @return string
     */
    protected function _getRecipient()
    {
        $recipient = $this->param('recipient');
        if (empty($recipient)) {
            $recipient = 'doesnotexist@passboltdummydomain.com';
        }

        return $recipient;
    }

    /**
     * Send email and display the trace.
     * @param string $to email address to send the email to
     * @param string $subject the subject of the email
     * @param string $message the content of the email
     * @return void
     */
    protected function _sendEmail($to, $subject, $message)
    {
        $config = Email::getConfig('default');
        try {
            $this->email
                ->setFrom($config['from'])
                ->setTo($to)
                ->setSubject($subject)
                ->send($message);
        } catch (\Exception $e) {
            $this->_displayTrace();
            $this->out($this->nl(0));
            $this->_error(__('A test email could not be sent.'));
            $this->abort(__('Error: {0}', $e->getMessage()));
        }
    }

    /**
     * Display trace of the communication with the server.
     * @return void
     */
    protected function _displayTrace()
    {
        $trace = $this->email->getTransport()->getTrace();

        $this->out($this->nl(0));
        $this->out('<info>Trace</info>');
        foreach ($trace as $entry) {
            if (isset($entry['cmd'])) {
                $cmd = $this->_removeCredentials($entry['cmd']);
                $this->out("<info>> {$cmd}</info>");
            }
            if (!empty($entry['response'])) {
                foreach ($entry['response'] as $response) {
                    $msg = $this->_removeCredentials($response['message']);
                    $this->out("[{$response['code']}] {$msg}");
                }
            }
        }
    }

    /**
     * Remove credentials (username and password) from a string.
     * @param string $str string where to remove the credentials
     * @return mixed
     */
    protected function _removeCredentials($str)
    {
        $transportConfig = TransportFactory::getConfig('default');
        $usernameClear = $transportConfig['username'];
        $usernameEncoded = base64_encode($transportConfig['username']);
        $passwordClear = base64_encode($transportConfig['password']);
        $passwordEncoded = $transportConfig['password'];
        $replaced = str_replace(
            [$usernameClear, $usernameEncoded, $passwordClear, $passwordEncoded],
            ['*****', '*****', '*****', '*****'],
            $str
        );

        return $replaced;
    }

    /**
     * Get default message (email content).
     * @return string
     */
    protected function _getDefaultMessage()
    {
        $message = "Congratulations!\n" .
        "If you receive this email, it means that your passbolt smtp configuration is working fine.";

        return $message;
    }

    /**
     * Set a custom transport class name.
     * In the context of this debugger, we'll use our own class name.
     * @param string $customTransportClassName name of the custom transport class to use
     * @return void
     */
    protected function _setCustomTransportClassName($customTransportClassName)
    {
        $transportConfig = TransportFactory::getConfig('default');
        $transportConfig['className'] = $customTransportClassName;
        TransportFactory::setConfig(self::TRANSPORT_CONFIG_NAME, $transportConfig);
        $this->email->setTransport(self::TRANSPORT_CONFIG_NAME);
    }

    /**
     * Check if Smtp is set as the transporter in the configuration.
     * Exit if smtp is not set and display an error message.
     * @return bool
     */
    protected function _checkSmtpIsSet()
    {
        $transportConfig = TransportFactory::getConfig('default');
        $className = Hash::get($transportConfig, 'className');
        if ($className != 'Smtp') {
            $this->_error(__('Your email transport configuration is not set to use "Smtp". ({0} is set instead)', $className));
            $this->_error(__('This email debug task is only for SMTP configurations.'));
            $this->abort(__('To fix this, edit EmailTransport.default.className property in /config/passbolt.php, and set className to "Smtp"'));
        }

        return true;
    }

    /**
     * Check if a default from is provided in the configuration.
     * Exit if none is provided and display an error message.
     * @return bool
     */
    protected function _checkFromIsSet()
    {
        $emailConfig = Email::getConfig('default');
        $from = Hash::get($emailConfig, 'from');
        if (empty($from)) {
            $this->_error(__('Your email configuration doesn\'t define a default "from"'));
            $this->abort(__('To fix this, edit Email.default.from property in /config/passbolt.php, and add \'from\' => [\'passbolt@your_organization.com\' => \'Passbolt\']'));
        }

        return true;
    }
}
