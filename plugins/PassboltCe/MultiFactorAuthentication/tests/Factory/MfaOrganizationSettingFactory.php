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
namespace Passbolt\MultiFactorAuthentication\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * MfaOrganizationSettingFactory
 */
class MfaOrganizationSettingFactory extends OrganizationSettingFactory
{
    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        $this->patchData([
            'property' => MfaSettings::MFA,
            'property_id' => UuidFactory::uuid(OrganizationSetting::UUID_NAMESPACE . MfaSettings::MFA),
            'value' => json_encode(['providers' => []]),
        ]);
    }

    /**
     * @param string|string[] $providers List of providers
     * @param bool $isActivated Value set to the providers
     * @return self
     */
    public function setProviders($providers, bool $isActivated): self
    {
        $providers = (array)$providers;
        $providers = array_fill_keys($providers, $isActivated);
        $value = [MfaSettings::PROVIDERS => $providers];

        return $this->value($value);
    }

    /**
     * @return self
     */
    public function totp(bool $isActive = true): self
    {
        return $this->setProviders(MfaSettings::PROVIDER_TOTP, $isActive);
    }

    /**
     * @param bool $isActive
     * @param string|null $yubikeyClientId
     * @param string|null $yubikeySecretKey
     * @return $this
     */
    public function yubikey(
        bool $isActive = true,
        ?string $yubikeyClientId = null,
        ?string $yubikeySecretKey = null
    ) {
        $value = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_YUBIKEY => $isActive]];
        $value[MfaSettings::PROVIDER_YUBIKEY] = [
            MfaOrgSettings::YUBIKEY_CLIENT_ID => $yubikeyClientId ?? $this->getFaker()->word,
            MfaOrgSettings::YUBIKEY_SECRET_KEY => $yubikeySecretKey ?? base64_encode($this->getFaker()->word),
        ];

        return $this->value($value);
    }

    /**
     * @param bool $isActive
     * @param string|null $hostName
     * @param string|null $salt
     * @param string|null $integrationKey
     * @param string|null $secretKey
     * @return MfaOrganizationSettingFactory
     */
    public function duo(
        bool $isActive = true,
        ?string $hostName = null,
        ?string $salt = null,
        ?string $integrationKey = null,
        ?string $secretKey = null
    ) {
        $value = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => $isActive]];
        $value[MfaSettings::PROVIDER_DUO] = $this->getDuoDefaultSettings($hostName, $salt, $integrationKey, $secretKey);

        return $this->value($value);
    }

    /**
     * @param bool $isActive
     * @param string|null $hostName
     * @param string|null $salt
     * @param string|null $integrationKey
     * @param string|null $secretKey
     * @return MfaOrganizationSettingFactory
     */
    public function duoWithTotp()
    {
        $value = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO => true, MfaSettings::PROVIDER_TOTP => true]];
        $value[MfaSettings::PROVIDER_DUO] = $this->getDuoDefaultSettings();

        return $this->value($value);
    }

    /**
     * @param string|null $hostName
     * @param string|null $salt
     * @param string|null $integrationKey
     * @param string|null $secretKey
     * @return array
     */
    protected function getDuoDefaultSettings(
        ?string $hostName = null,
        ?string $salt = null,
        ?string $integrationKey = null,
        ?string $secretKey = null
    ) {
        return [
            MfaOrgSettings::DUO_SALT => $salt ?? 'qwertyuiopasdfghjklzxcvbnm12345678901234567890',
            MfaOrgSettings::DUO_INTEGRATION_KEY => $integrationKey ?? 'DICPIC33F13IWF1FR52J',
            MfaOrgSettings::DUO_SECRET_KEY => $secretKey ?? '7TkYNgK8AGAuv3KW12qhsJLeIc1mJjHDHC1siNYX',
            MfaOrgSettings::DUO_HOSTNAME => $hostName ?? 'api-42e9f2fe.duosecurity.com',
        ];
    }
}
