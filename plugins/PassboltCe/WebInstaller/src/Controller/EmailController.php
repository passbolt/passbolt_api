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
use Passbolt\SmtpSettings\Service\SmtpSettingsTestEmailService;

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
     * @param \Passbolt\SmtpSettings\Service\SmtpSettingsTestEmailService $sendTestEmailService Service injected for unit test purposes
     * @return void|mixed
     */
    public function index(SmtpSettingsTestEmailService $sendTestEmailService)
    {
        if ($this->request->is('post')) {
            return $this->indexPost($sendTestEmailService);
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
     * @param \Passbolt\SmtpSettings\Service\SmtpSettingsTestEmailService $sendTestEmailService Service injected for unit test purposes
     * @return void|mixed
     */
    protected function indexPost(SmtpSettingsTestEmailService $sendTestEmailService)
    {
        $data = $this->getRequest()->getData();
        try {
            $this->validateData($data);
        } catch (CakeException $e) {
            $this->_error($e->getMessage());

            return;
        }

        /** @var \Passbolt\SmtpSettings\Form\EmailConfigurationForm $emailConfigForm */
        $emailConfigForm = $this->viewBuilder()->getVar('formExecuteResult');
        $data = $emailConfigForm->getData();

        if (isset($data['send_test_email'])) {
            $this->sendTestEmail($sendTestEmailService, $data);
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
        if (!$form->execute($data, ['validate' => 'webInstaller'])) {
            throw new CakeException(__('The data entered are not correct'));
        }
    }

    /**
     * Send test email.
     *
     * @param \Passbolt\SmtpSettings\Service\SmtpSettingsTestEmailService $sendTestEmailService Service injected for unit test purposes
     * @param array $data request data
     * @return void
     */
    protected function sendTestEmail(SmtpSettingsTestEmailService $sendTestEmailService, array $data)
    {
        try {
            $sendTestEmailService->sendTestEmail($data);
            $result = ['test_email_status' => true];
        } catch (\Throwable $e) {
            $result = [
                'test_email_status' => false,
                'test_email_error' => $e->getMessage(),
                'test_email_trace' => $sendTestEmailService->getTrace(),
            ];
        }
        $this->set($result);
    }
}
