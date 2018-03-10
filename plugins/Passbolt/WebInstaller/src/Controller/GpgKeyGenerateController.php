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

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Passbolt\WebInstaller\Form\GpgKeyGenerateForm;

class GpgKeyGenerateController extends WebInstallerController
{
    // GPG key generate form.
    protected $gpgKeyGenerateForm = null;

    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install/database';
        $this->stepInfo['next'] = 'install/email';
        $this->stepInfo['template'] = 'Pages/gpg_key_generate';
        $this->stepInfo['import_key_cta'] = 'install/gpg_key_import';

        $this->gpgKeyGenerateForm = new GpgKeyGenerateForm();
    }

    /**
     * Index
     * @return mixed
     */
    public function index()
    {
        if (!empty($this->request->getData())) {
            $this->_validateData($this->request->getData());
            try {
                $fingerprint = $this->gpgKeyGenerateForm->generateKey($this->request->getData());
                $this->gpgKeyGenerateForm->exportArmoredKeys($fingerprint);
            } catch (Exception $e) {
                return $this->_error($e->getMessage());
            }

            $this->_saveConfiguration(['fingerprint' => $fingerprint]);

            return $this->_success();
        }

        $this->render($this->stepInfo['template']);
    }

    /**
     * Save configuration.
     * @param array $data request data
     * @return void
     */
    protected function _saveConfiguration($data)
    {
        $session = $this->request->getSession();
        $session->write(self::CONFIG_KEY . '.gpg', [
            'fingerprint' => $data['fingerprint'],
            'public' => Configure::read('passbolt.gpg.serverKey.public'),
            'private' => Configure::read('passbolt.gpg.serverKey.private')
        ]);
    }

    /**
     * Validate data.
     * @param array $data request data
     * @return mixed
     */
    protected function _validateData($data)
    {
        $confIsValid = $this->gpgKeyGenerateForm->execute($data);
        $this->set('gpgKeyGenerateForm', $this->gpgKeyGenerateForm);

        if (!$confIsValid) {
            return $this->_error(__('The data entered are not correct'));
        }
    }
}
