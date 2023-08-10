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

namespace Passbolt\Sso\Model\Dto;

use Cake\Http\Exception\InternalErrorException;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\Providers\SsoActiveProvidersGetService;

class SsoSettingsDto extends AbstractSsoSettingsDto
{
    /**
     * @param \Passbolt\Sso\Model\Entity\SsoSetting $setting entity
     * @param array|null $data provider specific data
     */
    public function __construct(SsoSetting $setting, ?array $data = null)
    {
        $this->provider = $setting->provider ?? null;
        $this->providers = (new SsoActiveProvidersGetService())->get();
        $this->id = $setting->id ?? null;
        $this->status = $setting->status ?? null;
        $this->created = $setting->created ?? null;
        $this->modified = $setting->modified ?? null;
        $this->created_by = $setting->created_by ?? null;
        $this->modified_by = $setting->modified_by ?? null;
        if (isset($data)) {
            $this->data = self::ssoSettingsDataDtoFactory($this->provider, $data);
        } else {
            $this->data = null;
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->id,
            'provider' => $this->getProvider(),
            'providers' => $this->getProviders(),
            'status' => $this->status,
            'created' => $this->created->toDateTimeString(),
            'modified' => $this->created->toDateTimeString(),
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
        ];
        if (isset($this->data)) {
            $result['data'] = $this->data->toArray();
        }

        return $result;
    }

    /**
     * @param string $provider provider name
     * @param array $data provider specific data
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDataDtoInterface
     */
    public static function ssoSettingsDataDtoFactory(string $provider, array $data): SsoSettingsDataDtoInterface
    {
        switch ($provider) {
            case SsoSetting::PROVIDER_AZURE:
                return new SsoSettingsAzureDataDto($data);
            case SsoSetting::PROVIDER_GOOGLE:
                return new SsoSettingsGoogleDataDto($data);
            default:
                throw new InternalErrorException('SSO provider not implemented.');
        }
    }
}
