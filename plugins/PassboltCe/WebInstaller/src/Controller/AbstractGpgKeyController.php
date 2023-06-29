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
namespace Passbolt\WebInstaller\Controller;

use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Log\Log;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetSettingsInDbService;
use Passbolt\WebInstaller\Utility\DatabaseConfiguration;

abstract class AbstractGpgKeyController extends WebInstallerController
{
    /**
     * Validate data.
     *
     * @param array $data request data
     * @throws \Cake\Core\Exception\CakeException The key is not valid or the data does not validate
     * @return void
     */
    abstract protected function validateData(array $data): void;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->stepInfo['previous'] = '/install/database';
        $this->stepInfo['next'] = '/install/options';
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
        } catch (CakeException $e) {
            $this->_error($e->getMessage());

            return;
        }

        $this->webInstaller->setSettings('gpg', $data);
        $this->webInstaller->setSettings('hasSmtpSettings', $hasSmtpSettings);
        $this->webInstaller->saveSettings();
        $this->goToNextStep();
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
