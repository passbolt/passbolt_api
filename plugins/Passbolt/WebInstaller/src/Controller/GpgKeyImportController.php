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

use App\Utility\Gpg;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Passbolt\WebInstaller\Form\GpgKeyImportForm;

class GpgKeyImportController extends WebInstallerController
{
    const MY_CONFIG_KEY = 'gpg';

    // Gpg lib.
    public $Gpg = null;

    // Gpg key import form.
    public $GpgKeyImportForm = null;

    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['previous'] = 'install/database';
        $this->stepInfo['next'] = 'install/email';
        $this->stepInfo['template'] = 'Pages/gpg_key_import';
        $this->stepInfo['generate_key_cta'] = 'install/gpg_key';

        $this->Gpg = new Gpg();
        $this->GpgKeyImportForm = new GpgKeyImportForm();
    }

    /**
     * Index
     * @return mixed
     */
    public function index()
    {
        $data = $this->request->getData();
        if (!empty($data)) {
            try {
                $this->_validateData($data);
                $data['fingerprint'] = $this->_importKeyIntoKeyring($data['armored_key']);
                $this->_checkEncryptDecrypt($data['armored_key']);
                $this->_exportArmoredKeysIntoConfig($data['fingerprint']);
                $this->_saveConfiguration(self::MY_CONFIG_KEY, [
                    'fingerprint' => $data['fingerprint'],
                    'public' => Configure::read('passbolt.gpg.serverKey.public'),
                    'private' => Configure::read('passbolt.gpg.serverKey.private')
                ]);
            } catch (Exception $e) {
                return $this->_error($e->getMessage());
            }

            return $this->_success();
        }

        $this->render($this->stepInfo['template']);
    }

    /**
     * Import key into keyring.
     * @param string $armoredKey armored key
     * @return string fingerprint.
     */
    protected function _importKeyIntoKeyring($armoredKey)
    {
        try {
            $fingerprint = $this->Gpg->importKeyIntoKeyring($armoredKey);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $fingerprint;
    }

    /**
     * Export armored keys into config.
     * @param string $fingerprint key fingerprint
     * @return bool|void
     */
    protected function _exportArmoredKeysIntoConfig($fingerprint)
    {
        try {
            $this->GpgKeyImportForm->exportArmoredKeys($fingerprint);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return true;
    }

    /**
     * Validate data.
     * @param array $data request data
     * @return string key fingerprint
     */
    protected function _validateData($data)
    {
        $gpgKeyImportForm = new GpgKeyImportForm();
        $confIsValid = $gpgKeyImportForm->execute($data);
        $this->set('gpgKeyImportForm', $gpgKeyImportForm);

        if (!$confIsValid) {
            throw new Exception(__('This is not a valid GPG key'));
        }

        $keyInfo = $this->_getAndAssertGpgkey($data['armored_key']);
        if ($keyInfo === false) {
            throw new Exception(__('This is not a valid GPG key'));
        }

        if ($keyInfo['expires'] !== null) {
            throw new Exception(__('GPG keys with expiry date are currently not supported. Please use another key without expiry date.'));
        }

        return $keyInfo['fingerprint'];
    }

    /**
     * Check that the key provided can be used to encrypt and decrypt.
     * @param string $armoredKey the armored key
     * @return mixed
     */
    protected function _checkEncryptDecrypt($armoredKey)
    {
        try {
            $messageToEncrypt = 'open source password manager for teams';
            $this->Gpg->setEncryptKey($armoredKey);
            $this->Gpg->setSignKey($armoredKey);
            $encryptedMessage = $this->Gpg->encrypt($messageToEncrypt, true);
            $this->Gpg->setDecryptKey($armoredKey);
            $decryptedMessage = $this->Gpg->decrypt($encryptedMessage, '', true);
        } catch (Exception $e) {
            throw new Exception(__('This key cannot be used by passbolt. Please note that passbolt does not support GPG key with master passphrase. Error message: {0}', [$e->getMessage()]));
        } catch (\Exception $e) {
            throw new Exception(__('This key cannot be used by passbolt. Please note that passbolt does not support GPG key with master passphrase. Error message: {0}', [$e->getMessage()]));
        }

        if ($messageToEncrypt !== $decryptedMessage) {
            throw new Exception(__('Encrypt / decrypt operation returned an incorrect result. The key does not seem to be valid.'));
        }
    }

    /**
     * Parses a gpg key and verifies that it's readable and with a valid format.
     *
     * @param string $armoredKey the armored key
     * @return array|bool information array
     */
    protected function _getAndAssertGpgkey($armoredKey)
    {
        $gpg = new Gpg();
        if (!$gpg->isParsableArmoredPrivateKeyRule($armoredKey)) {
            return false;
        }
        try {
            $gpg = new Gpg();
            $info = $gpg->getKeyInfo($armoredKey);
        } catch (Exception $e) {
            return false;
        }

        return $info;
    }
}
