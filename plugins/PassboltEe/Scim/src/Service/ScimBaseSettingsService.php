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
 * @since         5.5.0
 */
namespace Passbolt\Scim\Service;

use App\Model\Entity\OrganizationSetting;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Scim\Form\Settings\ScimSettingsForm;

/**
 * ScimBaseSettingsService class
 */
abstract class ScimBaseSettingsService
{
    use OpenPGPCommonServerOperationsTrait;

    public const SCIM_SETTINGS_PROPERTY_NAME = 'scim';

    /**
     * Renders the value merging the validated settings
     * with the created/modified related fields and the id.
     *
     * The form is passed in order to ensure that the data returned is sanitized
     *
     * @param \App\Model\Entity\OrganizationSetting $setting Setting in the DB
     * @param \Passbolt\Scim\Form\Settings\ScimSettingsForm $form Form validating the value of the setting
     * @return array
     */
    public function getRenderedValue(OrganizationSetting $setting, ScimSettingsForm $form): array
    {
        $data = $this->decryptSettings($setting);
        $renderedValue = array_merge(
            $form->getData(),
            [
                'id' => $setting->id,
                'base_api_endpoint' => Router::url('scim/v2/' . Hash::get($data, 'setting_id'), true),
                'setting_id' => Hash::get($data, 'setting_id'),
                'scim_user_id' => Hash::get($data, 'scim_user_id'),
                'created' => $setting->modified,
                'modified' => $setting->modified,
                'created_by' => $setting->created_by,
                'modified_by' => $setting->modified_by,
            ]
        );

        unset($renderedValue['secret_token']);

        return $renderedValue;
    }

    /**
     * @param \App\Model\Entity\OrganizationSetting $organizationSetting
     * @return array
     */
    protected function decryptSettings(OrganizationSetting $organizationSetting): array
    {
        $value = $organizationSetting->get('value');
        if (!$value) {
            return $this->getDefaultSettings();
        }

        try {
            $gpg = OpenPGPBackendFactory::get();
            $gpg = $this->setDecryptKeyWithServerKey($gpg);
            $data = json_decode($gpg->decrypt($value), associative: true);
        } catch (Exception $exception) {
            $msg = $exception->getMessage() . ' ';
            $msg .= __('The SCIM settings could not be decrypted with the server gpg key.');
            throw new InternalErrorException($msg, 500, $exception);
        }

        return $data;
    }

    /**
     * @param array $settingsValue
     * @return string
     */
    protected function encryptSettings(array $settingsValue): string
    {
        try {
            $gpg = OpenPGPBackendFactory::get();
            $gpg = $this->setEncryptKeyWithServerKey($gpg);

            $data = $gpg->encrypt(json_encode($settingsValue));
        } catch (Exception $exception) {
            $msg = $exception->getMessage() . ' ';
            $msg .= __('The SCIM settings could not be encrypted with the server gpg key.');
            throw new InternalErrorException($msg, 500, $exception);
        }

        return $data;
    }

    /**
     * @return array<null>
     */
    protected function getDefaultSettings(): array
    {
        return [];
    }
}
