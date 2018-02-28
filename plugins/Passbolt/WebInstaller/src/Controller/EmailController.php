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

use Cake\Controller\Controller;
use Cake\Core\Exception\Exception;
use Cake\Controller\Component\FlashComponent;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;

class EmailController extends Controller
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
     * Will contain the email class.
     * @var null
     */
    protected $email = null;

    /**
     * Index
     */
    function index() {
        if(!empty($this->request->getData())) {
            $data = $this->request->getData();
            if(isset($data['send_test_email'])) {
                $this->_sendTestEmail($data);
            }
            else {
                $this->_saveEmailConfiguration($data);
            }
        }

        $this->render('Pages/email');
    }

    /**
     * Save email configuration.
     * @param $data
     * @return \Cake\Http\Response|null
     */
    private function _saveEmailConfiguration($data) {
        // TODO validate data.
        // Email configuration is valid, store information in the session.
        $session = $this->request->getSession();
        $session->write('Passbolt.Config.email', $this->request->getData());
        return $this->redirect('install/options');
    }

    /**
     * Send test email.
     * @param $data
     */
    private function _sendTestEmail($data) {
        // TODO: validate data.
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