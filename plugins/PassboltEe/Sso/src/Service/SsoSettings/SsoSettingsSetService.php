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
 * @since         3.9.0
 */
namespace Passbolt\Sso\Service\SsoSettings;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\Sso\Form\BaseSsoSettingsForm;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Form\SsoSettingsGoogleDataForm;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoSetting;

class SsoSettingsSetService
{
    /**
     * Create an encrypted org setting
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $data user provided data
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    public function create(UserAccessControl $uac, array $data): SsoSettingsDto
    {
        if (!$uac->isAdmin()) {
            throw new BadRequestException(__('Only administrators can create SSO settings.'));
        }

        $form = $this->getSsoSettingsForm($data);
        if (!$form->execute($data)) {
            throw new CustomValidationException(
                __('Something went wrong when validating the single-sign on settings.'),
                $form->getErrors()
            );
        }
        $data = $form->getData();

        // Prepare the data, serialize the JSON and encrypt using server key
        $serializedData = $this->serializeData($data['provider'], $data['data']);
        $encryptedData = $this->encrypt($serializedData);

        // Build entity
        $ssoSettingsTable = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoSettings');
        /** @var \Passbolt\Sso\Model\Entity\SsoSetting $ssoSettingEntity */
        $ssoSettingEntity = $ssoSettingsTable->newEntity(
            [
                'provider' => $data['provider'],
                'status' => SsoSetting::STATUS_DRAFT,
                'data' => $encryptedData,
                'created_by' => $uac->getId(),
                'modified_by' => $uac->getId(),
            ],
            [
                'accessibleFields' => [
                    'provider' => true,
                    'status' => true,
                    'data' => true,
                    'created_by' => true,
                    'modified_by' => true,
                ],
            ]
        );

        // Check for validation or build rules errors
        $errors = $ssoSettingEntity->getErrors();
        if (!empty($errors) || !$ssoSettingsTable->save($ssoSettingEntity)) {
            $msg = __('It is not possible to save the SSO settings.');
            throw new ValidationException($msg, $ssoSettingEntity, $ssoSettingsTable);
        }

        return new SsoSettingsDto($ssoSettingEntity, $data['data']);
    }

    /**
     * @param string $provider provider name
     * @param array $data provider configuration data
     * @return string
     */
    protected function serializeData(string $provider, array $data): string
    {
        $dataDto = SsoSettingsDto::ssoSettingsDataDtoFactory($provider, $data);

        $result = json_encode($dataDto->toArray());
        if (!$result) {
            throw new InternalErrorException(__('It is not possible to save the SSO settings.'));
        }

        return $result;
    }

    /**
     * Encrypt a data with the server OpenPGP key.
     *
     * @param string $jsonData the data in json format
     * @return string
     */
    protected static function encrypt(string $jsonData)
    {
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $gpg = OpenPGPBackendFactory::get();
        $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);

        try {
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setEncryptKeyFromFingerprint($fingerprint);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to encrypt.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg->encrypt($jsonData, true);
    }

    /**
     * @param array $data payload
     * @return \Passbolt\Sso\Form\BaseSsoSettingsForm
     */
    protected function getSsoSettingsForm(array $data): BaseSsoSettingsForm
    {
        if (!isset($data['provider'])) {
            throw new BadRequestException('Service provider missing.');
        }
        if (!is_string($data['provider'])) {
            throw new BadRequestException('Service provider invalid.');
        }
        if (!in_array($data['provider'], SsoSetting::ALLOWED_PROVIDERS)) {
            throw new BadRequestException('Service provider not supported.');
        }

        switch ($data['provider']) {
            case SsoSetting::PROVIDER_AZURE:
                return new SsoSettingsAzureDataForm();
            case SsoSetting::PROVIDER_GOOGLE:
                return new SsoSettingsGoogleDataForm();
            default:
                throw new BadRequestException('Service provider not supported.');
        }
    }
}
