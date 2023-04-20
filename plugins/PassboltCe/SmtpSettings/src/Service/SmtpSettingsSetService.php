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
use App\Model\Entity\OrganizationSetting;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\SmtpSettings\Form\EmailConfigurationForm;

class SmtpSettingsSetService
{
    use SmtpSettingsServiceTrait;

    public const SMTP_SETTINGS_ALLOWED_FIELDS = [
        'sender_name',
        'sender_email',
        'host',
        'tls',
        'port',
        'client',
        'username',
        'password',
    ];

    /**
     * @var \App\Utility\UserAccessControl
     */
    private $uac;

    /**
     * @param \App\Utility\UserAccessControl $uac UAC
     */
    public function __construct(UserAccessControl $uac)
    {
        $this->uac = $uac;
    }

    /**
     * Validates the SMTP Settings provided in the payload
     * Encrypts in the data
     * Saves the settings
     *
     * @param array $data Data provided in the payload
     * @return \App\Model\Entity\OrganizationSetting
     * @throws \App\Error\Exception\FormValidationException if the data does not validate the EmailConfigurationForm
     */
    public function saveSettings(array $data): OrganizationSetting
    {
        $data = $this->sanitizeData($data, self::SMTP_SETTINGS_ALLOWED_FIELDS);

        $form = new EmailConfigurationForm();
        if (!$form->execute($data, ['validate' => 'update'])) {
            throw new FormValidationException(__('Could not validate the smtp settings.'), $form);
        }

        $value = $this->encodeAndEncryptData($form->getData());

        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');

        return $OrganizationSettings->createOrUpdateSetting(
            SmtpSettingsGetSettingsInDbService::SMTP_SETTINGS_PROPERTY_NAME,
            $value,
            $this->uac
        );
    }

    /**
     * Converts the data to json and encrypts with the server key
     *
     * @param array $data Data to encrypt
     * @return string
     */
    private function encodeAndEncryptData(array $data): string
    {
        $gpgConfig = Configure::read('passbolt.gpg');
        $keyId = $gpgConfig['serverKey']['fingerprint'];
        $passphrase = $gpgConfig['serverKey']['passphrase'];
        $gpg = OpenPGPBackendFactory::get();
        $gpg->setSignKeyFromFingerprint($keyId, $passphrase);
        $gpg->setEncryptKeyFromFingerprint($keyId);

        return $gpg->encrypt(json_encode($data));
    }
}
