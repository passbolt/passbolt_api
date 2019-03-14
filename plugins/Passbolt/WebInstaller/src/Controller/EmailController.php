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
namespace Passbolt\WebInstaller\Controller;

use Cake\Core\Exception\Exception;
use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;
use Passbolt\WebInstaller\Form\EmailConfigurationForm;

class EmailController extends WebInstallerController
{
    public $components = ['Flash'];

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
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install/gpg_key';
        $this->stepInfo['next'] = 'install/options';
        $this->stepInfo['template'] = 'Pages/email';
    }

    /**
     * Index
     * @return void|mixed
     */
    public function index()
    {
        if ($this->request->is('post')) {
            return $this->indexPost();
        }

        $databaseSettings = $this->webInstaller->getSettings('email');
        if (!empty($databaseSettings)) {
            foreach ($databaseSettings as $key => $databaseSetting) {
                $this->request = $this->request->withData($key, $databaseSetting);
            }
        }

        $this->set('formExecuteResult', null);
        $this->render($this->stepInfo['template']);
    }

    /**
     * Index post
     * @return void|mixed
     */
    protected function indexPost()
    {
        $data = $this->request->getData();
        try {
            $this->validateData($data);
        } catch (Exception $e) {
            return $this->_error($e->getMessage());
        }

        if (isset($data['send_test_email'])) {
            $this->sendTestEmail($data);
            $this->render($this->stepInfo['template']);
        } else {
            $this->webInstaller->setSettingsAndSave('email', $data);
            $this->goToNextStep();
        }
    }

    /**
     * Validate data.
     * @param array $data request data
     * @throws Exception The data does not validate
     * @return void
     */
    protected function validateData($data)
    {
        $form = new EmailConfigurationForm();
        $this->set('formExecuteResult', $form);
        if (!$form->execute($data)) {
            throw new Exception(__('The data entered are not correct'));
        }
    }

    /**
     * Send test email.
     * @param array $data request data
     * @return void
     */
    protected function sendTestEmail($data)
    {
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
     * @param array $data request data
     * @return void
     */
    protected function _setTransport($customTransportClassName, $data)
    {
        $transportConfig = TransportFactory::getConfig('default');
        $transportConfig['className'] = $customTransportClassName;
        $transportConfig['host'] = $data['host'];
        $transportConfig['port'] = $data['port'];
        $transportConfig['username'] = empty($data['username']) ? null : $data['username'];
        $transportConfig['password'] = empty($data['password']) ? null : $data['password'];
        $transportConfig['tls'] = ($data['tls'] == '1' ? true : null);
        TransportFactory::setConfig(self::TRANSPORT_CONFIG_NAME, $transportConfig);
        $this->email->setTransport(self::TRANSPORT_CONFIG_NAME);
    }

    /**
     * Get default message (email content).
     * @return string
     */
    protected function _getDefaultMessage()
    {
        $message = __("Congratulations!") . "\n" .
            __("If you receive this email, it means that your passbolt smtp configuration is working fine.");

        return $message;
    }
}
