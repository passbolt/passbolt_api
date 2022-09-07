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
 * @since         3.8.0
 */
namespace Passbolt\SmtpSettings\Service;

use App\Error\Exception\FormValidationException;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\WebInstaller\Form\EmailConfigurationForm;

class SmtpSettingsGetSettingsInDbService
{
    public const SMTP_SETTINGS_PROPERTY_NAME = 'smtp';

    /**
     * Reads the SMTP settings in the DB
     * Validates the data returned
     *
     * @return array|null
     * @throws \App\Error\Exception\FormValidationException if the data does not validate the EmailConfigurationForm
     */
    public function getSettings(): ?array
    {
        $form = new EmailConfigurationForm();
        $data = $this->readConfigInDB();

        if (is_null($data)) {
            return null;
        }

        if (!$form->execute($data)) {
            throw new FormValidationException(__('The data entered are not correct'), $form);
        }

        return $form->getData();
    }

    /**
     * Reads SMTP in the organization settings table
     *
     * @return array|null
     */
    protected function readConfigInDB(): ?array
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $settings = $OrganizationSettings->getByProperty(self::SMTP_SETTINGS_PROPERTY_NAME);
        if (is_null($settings)) {
            return null;
        }

        $value = $settings->get('value');
        $decryptedValue = $this->decrypt($value);

        return json_decode($decryptedValue, true);
    }

    /**
     * Decrypts the value stored in the organization setting
     *
     * @param string $encryptedValue Encrypted value to be decrypted
     * @return string
     */
    protected function decrypt(string $encryptedValue): string
    {
        $gpgConfig = Configure::read('passbolt.gpg');
        $keyId = $gpgConfig['serverKey']['fingerprint'];
        $passphrase = $gpgConfig['serverKey']['passphrase'];
        $gpg = OpenPGPBackendFactory::get();

        try {
            $gpg->setDecryptKeyFromFingerprint($keyId, $passphrase);

            return $gpg->decrypt($encryptedValue);
        } catch (\Throwable $e) {
            $msg = __('The OpenPGP server key defined in the config cannot be used to decrypt.') . ' ';
            $msg .= __('To fix this problem, you need to configure the SMTP server again.') . ' ';
            $msg .= $e->getMessage();
            throw new InternalErrorException($msg);
        }
    }
}
