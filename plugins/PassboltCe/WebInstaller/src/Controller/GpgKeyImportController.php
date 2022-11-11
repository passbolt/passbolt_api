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

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Log\Log;
use Cake\Utility\Hash;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetSettingsInDbService;
use Passbolt\WebInstaller\Form\GpgKeyForm;
use Passbolt\WebInstaller\Utility\DatabaseConfiguration;

class GpgKeyImportController extends WebInstallerController
{
    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->stepInfo['previous'] = '/install/database';
        $this->stepInfo['next'] = '/install/options';
        $this->stepInfo['template'] = 'Pages/gpg_key_import';
        $this->stepInfo['generate_key_cta'] = '/install/gpg_key';
    }

    /**
     * Index
     *
     * @return mixed
     */
    public function index()
    {
        if ($this->request->is('post')) {
            return $this->indexPost();
        }

        $this->set('formExecuteResult', null);
        $this->render($this->stepInfo['template']);
    }

    /**
     * Index post
     *
     * @return mixed
     */
    protected function indexPost()
    {
        $data = $this->request->getData();
        try {
            $this->validateData($data);
            $fingerprint = $data['fingerprint'];
            $hasSmtpSettings = $this->hasValidSmtpSettingsInDB($fingerprint);
        } catch (Exception $e) {
            $this->_error($e->getMessage());

            return;
        }

        $this->webInstaller->setSettings('gpg', $data);
        $this->webInstaller->setSettings('hasSmtpSettings', $hasSmtpSettings);
        $this->webInstaller->saveSettings();
        $this->goToNextStep();
    }

    /**
     * Validate data.
     *
     * @param array $data request data
     * @throws \Cake\Core\Exception\Exception The key is not valid
     * @return void
     */
    protected function validateData($data)
    {
        $form = new GpgKeyForm();
        $confIsValid = $form->execute($data);
        $this->set('formExecuteResult', $form);
        if (!$confIsValid) {
            $errors = Hash::flatten($form->getErrors());
            $errorMessage = implode('; ', $errors);
            throw new Exception(__('The data entered are not correct: {0}', $errorMessage));
        }
    }

    /**
     * @param string $fingerprint Fingerprint of the server key
     * @return bool
     */
    protected function hasValidSmtpSettingsInDB(string $fingerprint): bool
    {
        $dbSettings = $this->webInstaller->getSettings('database');
        Configure::write('passbolt.gpg.serverKey.fingerprint', $fingerprint);
        try {
            DatabaseConfiguration::setDefaultConfig($dbSettings);
            $smtpSettingsInDb = (new SmtpSettingsGetSettingsInDbService())->getSettings();
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return false;
        }

        return !is_null($smtpSettingsInDb);
    }
}
