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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;
use Duo\DuoUniversal\Client;
use Duo\DuoUniversal\DuoException;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoGetSdkClientService;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsDuoService
{
    /**
     * Duo organisation settings array
     *
     * @var array
     */
    private $settings;

    /**
     * @param array $data user provider data
     */
    public function __construct(array $data)
    {
        $this->settings = $data;
    }

    /**
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoClientId(): string
    {
        return $this->getSetting(
            MfaOrgSettings::DUO_CLIENT_ID,
            __('No configuration set for Duo client ID.')
        );
    }

    /**
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoApiHostname(): string
    {
        return $this->getSetting(
            MfaOrgSettings::DUO_API_HOSTNAME,
            __('No configuration set for Duo API hostname.')
        );
    }

    /**
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if config is missing
     * @return string
     */
    public function getDuoClientSecret(): string
    {
        return $this->getSetting(
            MfaOrgSettings::DUO_CLIENT_SECRET,
            __('No configuration set for Duo client secret.')
        );
    }

    /**
     * @param \Duo\DuoUniversal\Client|null $client Duo SDK Client
     * @param bool $performHealthcheck Whether to perform the Duo Healthcheck or not
     * @return void
     * @throws \App\Error\Exception\CustomValidationException if there is an issue
     */
    public function validateDuoSettings(?Client $client = null, bool $performHealthcheck = true): void
    {
        $errors = [];

        try {
            $clientSecret = $this->getDuoClientSecret();
            if (!Validation::custom($clientSecret, '/^[a-zA-Z0-9]{32,128}$/')) {
                $msg = __('This is not a valid Duo client secret.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_SECRET]['isValidClientSecret'] = $msg;
            }
        } catch (RecordNotFoundException $e) {
            $msg = __('No configuration set for Duo client secret.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_SECRET]['notEmpty'] = $msg;
        }

        try {
            $hostname = $this->getDuoApiHostname();
            if (!Validation::custom($hostname, '/^api-[a-fA-F0-9]{8,16}\.duosecurity\.com$/')) {
                $msg = __('This is not a valid Duo API hostname.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_API_HOSTNAME]['isValidApiHostname'] = $msg;
            }
        } catch (RecordNotFoundException $e) {
            $msg = __('No configuration set for Duo API hostname.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_API_HOSTNAME]['notEmpty'] = $msg;
        }

        try {
            $clientId = $this->getDuoClientId();
            if (!Validation::custom($clientId, '/^[a-zA-Z0-9]{16,32}$/')) {
                $msg = __('This is not a valid Duo client ID.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_ID]['isValidClientId'] = $msg;
            }
        } catch (RecordNotFoundException $e) {
            $msg = __('No configuration set for Duo client ID.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_ID]['notEmpty'] = $msg;
        }

        if ($performHealthcheck && empty($errors[MfaSettings::PROVIDER_DUO])) {
            try {
                $duoClient = $client ?? (new MfaDuoGetSdkClientService())->getOrFail(
                    $this,
                    AuthenticationToken::TYPE_MFA_SETUP
                );
                $duoClient->healthCheck();
            } catch (DuoException | InternalErrorException $e) {
                $msg = __('Cannot verify Duo settings.') . ' ' . $e->getMessage();
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HEALTH_CHECK] = $msg;
            }
        }

        if (count($errors) !== 0) {
            $msg = __('Could not validate Duo configuration');
            throw new CustomValidationException($msg, $errors);
        }
    }

    /**
     * Get Duo provider setting.
     *
     * @param string $settingKey organization settings key
     * @param string $errorMessage error message if organization settings key is not found
     * @return string
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if setting is missing
     */
    private function getSetting(string $settingKey, string $errorMessage): string
    {
        if (!isset($this->settings[MfaSettings::PROVIDER_DUO][$settingKey])) {
            throw new RecordNotFoundException($errorMessage);
        }

        return $this->settings[MfaSettings::PROVIDER_DUO][$settingKey];
    }
}
