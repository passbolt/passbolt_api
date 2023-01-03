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
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Error\Exception\CustomValidationException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Routing\Router;
use Cake\Validation\Validation;
use Duo\DuoUniversal\Client;
use Duo\DuoUniversal\DuoException;

trait MfaOrgSettingsDuoTrait
{
    /**
     * Get an instance of Duo SDK Client object. This object is used to perform various operations,
     * like validating settings, getting an authentication URL, or exchanging an authorization code
     * for an MFA result payload.
     *
     * @param array $data user provider data, otherwise settings if no data provided
     * @return \Duo\DuoUniversal\Client
     */
    public function getDuoSdkClient(?array $data = null)
    {
        $duoCallbackURL = Router::url('/mfa/setup/duo/callback');
        $duoClient = new Client(
            $this->getDuoClientId($data),
            $this->getDuoClientSecret($data),
            $this->getDuoApiHostname($data),
            $duoCallbackURL,
            true,
        );

        return $duoClient;
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @param array $data user provider data, otherwise settings if no data provided
     * @return string
     */
    public function getDuoClientId(?array $data = null)
    {
        return $this->getSetting(
            MfaOrgSettings::DUO_CLIENT_ID,
            __('No configuration set for Duo client ID.'),
            $data,
        );
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @param array $data user provider data, otherwise settings if no data provided
     * @return string
     */
    public function getDuoApiHostname(?array $data = null)
    {
        return $this->getSetting(
            MfaOrgSettings::DUO_API_HOSTNAME,
            __('No configuration set for Duo API hostname.'),
            $data,
        );
    }

    /**
     * @throw RecordNotFoundException if config is missing
     * @param array $data user provider data, otherwise settings if no data provided
     * @return string
     */
    public function getDuoClientSecret(?array $data = null)
    {
        return $this->getSetting(
            MfaOrgSettings::DUO_CLIENT_SECRET,
            __('No configuration set for Duo client secret.'),
            $data,
        );
    }

    /**
     * @throw CustomValidationException if there is an issue
     * @param array $data user provider data
     * @return void
     */
    public function validateDuoSettings(array $data)
    {
        $errors = [];

        try {
            $clientSecret = $this->getDuoClientSecret($data);
            if (!Validation::custom($clientSecret, '/^[a-zA-Z0-9]{32,128}$/')) {
                $msg = __('This is not a valid Duo client secret.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_SECRET]['isValidClientSecret'] = $msg;
            }
        } catch (RecordNotFoundException $e) {
            $msg = __('No configuration set for Duo client secret.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_SECRET]['notEmpty'] = $msg;
        }

        try {
            $hostname = $this->getDuoApiHostname($data);
            if (!Validation::custom($hostname, '/^api-[a-fA-F0-9]{8,16}\.duosecurity\.com$/')) {
                $msg = __('This is not a valid Duo API hostname.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_API_HOSTNAME]['isValidApiHostname'] = $msg;
            }
        } catch (RecordNotFoundException $e) {
            $msg = __('No configuration set for Duo API hostname.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_API_HOSTNAME]['notEmpty'] = $msg;
        }

        try {
            $clientId = $this->getDuoClientId($data);
            if (!Validation::custom($clientId, '/^[a-zA-Z0-9]{16,32}$/')) {
                $msg = __('This is not a valid Duo client ID.');
                $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_ID]['isValidClientId'] = $msg;
            }
        } catch (RecordNotFoundException $e) {
            $msg = __('No configuration set for Duo client ID.');
            $errors[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_CLIENT_ID]['notEmpty'] = $msg;
        }

        if (empty($errors[MfaSettings::PROVIDER_DUO])) {
            $duoClient = $this->getDuoSdkClient($data);
            try {
                $duoClient->healthCheck();
            } catch (DuoException $e) {
                $msg = __("Cannot verify Duo settings. {$e->getMessage()}");
                $errors[MfaSettings::PROVIDER_DUO]['healthCheck'] = $msg;
            }
        }

        if (count($errors) !== 0) {
            $msg = __('Could not validate Duo configuration');
            throw new CustomValidationException($msg, $errors);
        }
    }

    /**
     * @throw RecordNotFoundException if setting is missing
     * @param string $settingKey organization settings key
     * @param string $errorMessage error message if organization settings key is not found
     * @param array|null $data user provider data, otherwise settings if no data provided
     * @return string
     */
    private function getSetting(string $settingKey, string $errorMessage, ?array $data = null)
    {
        $conf = $data ?? $this->settings;

        if (!isset($conf[MfaSettings::PROVIDER_DUO][$settingKey])) {
            throw new RecordNotFoundException($errorMessage);
        }

        return $conf[MfaSettings::PROVIDER_DUO][$settingKey];
    }
}
