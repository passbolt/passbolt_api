<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Controller;

use Cake\Mailer\Email;
use Passbolt\WebInstaller\Form\EmailConfigurationForm;

class EmailController extends WebInstallerController
{
    var $components = ['Flash'];

    /**
     * Transport class to be used for testing.
     * We use our own DebugSmtp that will get the server communication trace.
     */
    const TRANSPORT_CLASS = 'DebugSmtp';

    /**
     * Name of the transport configuration that we'll use.
     * (will be created on the fly).
     */
    const TRANSPORT_CONFIG_NAME = 'debugEmail';

    /**
     * Contains the email class.
     */
    protected $email = null;

    /**
     * Contains the email configuration form.
     */
    protected $emailConfigurationForm = null;

    /**
     * Initialize.
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install/gpg_key';
        $this->stepInfo['next'] = 'install/options';
        $this->stepInfo['template'] = 'Pages/email';

        $this->emailConfigurationForm = new EmailConfigurationForm();
    }

    /**
     * Index
     */
    function index() {
        if(!empty($this->request->getData())) {
            $data = $this->request->getData();
            $this->_validateData($data);
            if(isset($data['send_test_email'])) {
                $this->_sendTestEmail($data);
            }
            else {
                $this->_saveEmailConfiguration($data);
            }
        }

        $this->render($this->stepInfo['template']);
    }

    /**
     * Validate data.
     * @param $data
     */
    protected function _validateData($data) {
        $confIsValid = $this->emailConfigurationForm->execute($data);
        $this->set('emailConfigurationForm', $this->emailConfigurationForm);

        if (!$confIsValid) {
            return $this->_error(__('The data entered are not correct'));
        }
    }

    /**
     * Save email configuration.
     * @param $data
     * @return \Cake\Http\Response|null
     */
    private function _saveEmailConfiguration($data) {
        // Email configuration is valid, store information in the session.
        $session = $this->request->getSession();
        $session->write(self::CONFIG_KEY . '.email', $this->request->getData());
        return $this->_success();
    }

    /**
     * Send test email.
     * @param $data
     */
    private function _sendTestEmail($data) {
        $this->email = new Email('default');
        $this->_setTransport(self::TRANSPORT_CLASS, $data);

        try {
            $this->email
                ->setFrom([
                    $data['sender_email'] => $data['sender_name']
                ])
                ->setTo($data['email_test_to'])
                ->setSubject(__('passbolt test email'))
                ->send($this->_getDefaultMessage());
        } catch (\Exception $e) {
            $trace = $this->email->getTransport()->getTrace();
            $this->set([
                'test_email_status' => false,
                'test_email_error' => $e->getMessage(),
                'test_email_trace' => $trace,
            ]);
            return;
        }
        $this->set(['test_email_status' => true]);
    }

    /**
     * Set a custom transport class name.
     * In the context of this debugger, we'll use our own class name.
     * @param string $customTransportClassName name of the custom transport class to use
     * @return void
     */
    private function _setTransport($customTransportClassName, $data)
    {
        $transportConfig = Email::getConfigTransport('default');
        $transportConfig['className'] = $customTransportClassName;
        $transportConfig['host'] = $data['host'];
        $transportConfig['port'] = $data['port'];
        $transportConfig['username'] = $data['username'];
        $transportConfig['password'] = $data['password'];
        $transportConfig['tls'] = ($data['tls'] == '1' ? true : null);
        Email::setConfigTransport(self::TRANSPORT_CONFIG_NAME, $transportConfig);
        $this->email->setTransport(self::TRANSPORT_CONFIG_NAME);
    }

    /**
     * Get default message (email content).
     * @return string
     */
    private function _getDefaultMessage()
    {
        $message = "Congratulations!\n" .
            "If you receive this email, it means that your passbolt smtp configuration is working fine.";

        return $message;
    }
}