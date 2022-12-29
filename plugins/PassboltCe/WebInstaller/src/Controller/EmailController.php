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
namespace Passbolt\WebInstaller\Controller;

use Cake\Core\Exception\CakeException;
use Passbolt\SmtpSettings\Form\EmailConfigurationForm;
use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestEmailService;

class EmailController extends WebInstallerController
{
    /**
     * Contains the email class.
     *
     * @var \Cake\Mailer\Mailer
     */
    protected $email = null;

    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->stepInfo['previous'] = '/install/options';
        $this->stepInfo['next'] = $this->webInstaller->getSettings('hasAdmin') ?
            'install/installation' : '/install/account_creation';
        $this->stepInfo['template'] = 'Pages/email';
    }

    /**
     * Index
     *
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
     *
     * @return void|mixed
     */
    protected function indexPost()
    {
        $data = $this->getRequest()->getData();
        try {
            $this->validateData($data);
        } catch (CakeException $e) {
            $this->_error($e->getMessage());

            return;
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
     *
     * @param array $data request data
     * @throws \Cake\Core\Exception\CakeException The data does not validate
     * @return void
     */
    protected function validateData($data)
    {
        $form = new EmailConfigurationForm();
        $this->set('formExecuteResult', $form);
        if (!$form->execute($data)) {
            throw new CakeException(__('The data entered are not correct'));
        }
    }

    /**
     * Send test email.
     *
     * @param array $data request data
     * @return void
     */
    protected function sendTestEmail(array $data)
    {
        $service = new SmtpSettingsSendTestEmailService();
        try {
            $service->sendTestEmail($data);
            $result = ['test_email_status' => true];
        } catch (\Throwable $e) {
            $result = [
                'test_email_status' => false,
                'test_email_error' => $e->getMessage(),
                'test_email_trace' => $service->getTrace(),
            ];
        }
        $this->set($result);
    }
}
