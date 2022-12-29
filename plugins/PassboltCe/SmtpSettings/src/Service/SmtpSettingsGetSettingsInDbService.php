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
use Cake\Core\Exception\Exception;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\SmtpSettings\Form\EmailConfigurationForm;

class SmtpSettingsGetSettingsInDbService
{
    public const SMTP_SETTINGS_PROPERTY_NAME = 'smtp';

    /**
     * Reads the SMTP settings in the DB
     * Validates the data returned
     *
     * @return array|null
     * @throws \App\Error\Exception\FormValidationException if the data does not validate the EmailConfigurationForm
     * @throws \Cake\Http\Exception\InternalErrorException if the data in the DB cannot be decrypted
     * @throws \Cake\Database\Exception\MissingConnectionException if the database is not in place (this is the case during installation)
     */
    public function getSettings(): ?array
    {
        $form = new EmailConfigurationForm();
        $data = $this->readConfigInDB();

        if (is_null($data)) {
            return null;
        }

        if (!$form->execute($data)) {
            // An exception is thrown, the settings should not be consumed, but the source is appended for
            // diagnostic purposes.
            $form->set('source', SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_DB);
            throw new FormValidationException(__('Could not validate the smtp settings found in database.'), $form);
        }

        return $form->getData();
    }

    /**
     * Reads SMTP in the organization settings table
     *
     * @return array|null
     * @throws \Cake\Http\Exception\InternalErrorException if the data in the DB cannot be decrypted
     */
    protected function readConfigInDB(): ?array
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        try {
            $settings = $OrganizationSettings->getByProperty(self::SMTP_SETTINGS_PROPERTY_NAME);
        } catch (\Throwable $e) {
            // During installation, the connection might not be set yet
            return null;
        }
        if (is_null($settings)) {
            return null;
        }

        $value = $settings->get('value');
        $decryptedValue = $this->decrypt($value);

        return array_merge(
            ['id' => $settings['id']],
            json_decode($decryptedValue, true),
            [
                'created' => $settings['created'],
                'modified' => $settings['modified'],
                'created_by' => $settings['created_by'],
                'modified_by' => $settings['modified_by'],
            ]
        );
    }

    /**
     * Decrypts the value stored in the organization setting
     *
     * @param string $encryptedValue Encrypted value to be decrypted
     * @return string
     * @throw InternalErrorException If the smtp settings cannot be decrypted
     */
    protected function decrypt(string $encryptedValue): string
    {
        $keyFingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $gpg = OpenPGPBackendFactory::get();

        // set the key to be used for decrypting
        try {
            $gpg->setDecryptKeyFromFingerprint($keyFingerprint, $passphrase);
        } catch (Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setDecryptKeyFromFingerprint($keyFingerprint, $passphrase);
            } catch (Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to decrypt.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg);
            }
        }

        try {
            return $gpg->decrypt($encryptedValue);
        } catch (\Throwable $e) {
            $msg = __('The OpenPGP server key cannot be used to decrypt the SMTP settings stored in database.');
            $msg .= ' ' . __('To fix this problem, you need to configure the SMTP server again.') . ' ';
            $msg .= $e->getMessage();
            throw new InternalErrorException($msg, 500, $e);
        }
    }
}
