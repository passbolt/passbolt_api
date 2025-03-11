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
 * @since         3.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\Factory;

use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Passbolt\AccountSettings\Model\Entity\AccountSetting;
use Passbolt\AccountSettings\Test\Factory\AccountSettingFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * MfaAccountSettingFactory
 */
class MfaAccountSettingFactory extends AccountSettingFactory
{
    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        $property = AccountSetting::UUID_NAMESPACE . MfaSettings::MFA;
        $this->patchData([
            'property' => MfaSettings::MFA,
            'property_id' => UuidFactory::uuid($property),
            'value' => json_encode([MfaSettings::PROVIDERS => []]),
        ]);
    }

    /**
     * @param string $uri Provisioning URI
     * @param \Cake\I18n\DateTime|null $verified Date of verification
     * @return self
     */
    public function totp(string $uri = 'Foo', ?\Cake\I18n\DateTime $verified = null): self
    {
        $settings = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_TOTP]];
        $settings[MfaSettings::PROVIDER_TOTP] = [
            MfaAccountSettings::VERIFIED => $verified ?? \Cake\I18n\DateTime::now(),
            MfaAccountSettings::OTP_PROVISIONING_URI => $uri,
        ];
        $value = json_encode($settings);

        return $this->patchData(compact('value'));
    }

    /**
     * @param string|null $yubikeyId YUBI Key ID
     * @param \Cake\I18n\DateTime|null $verified Date of verification
     * @return self
     */
    public function yubikey(?string $yubikeyId = null, ?\Cake\I18n\DateTime $verified = null): self
    {
        $yubikeyId = $yubikeyId ?? $this->getFaker()->sentence;
        $settings = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_YUBIKEY]];
        $settings[MfaSettings::PROVIDER_YUBIKEY] = [
            MfaAccountSettings::VERIFIED => $verified ?? \Cake\I18n\DateTime::now(),
            MfaAccountSettings::YUBIKEY_ID => $yubikeyId,
        ];
        $value = json_encode($settings);

        return $this->patchData(compact('value'));
    }

    /**
     * @param \Cake\I18n\DateTime|null $verified Date of verification
     * @return self
     */
    public function duo(?\Cake\I18n\DateTime $verified = null): self
    {
        $settings = [MfaSettings::PROVIDERS => [MfaSettings::PROVIDER_DUO]];
        $settings[MfaSettings::PROVIDER_DUO] = [
            MfaAccountSettings::VERIFIED => $verified ?? \Cake\I18n\DateTime::now(),
        ];
        $value = json_encode($settings);

        return $this->patchData(compact('value'));
    }

    /**
     * @param \Cake\I18n\DateTime|null $verified Date of verification
     * @return self
     */
    public function duoWithTotp(string $uri = 'Foo', ?\Cake\I18n\DateTime $verified = null): self
    {
        $settings = [MfaSettings::PROVIDERS => [
            MfaSettings::PROVIDER_DUO,
            MfaSettings::PROVIDER_TOTP,
        ]];
        $settings[MfaSettings::PROVIDER_DUO] = [
            MfaAccountSettings::VERIFIED => $verified ?? \Cake\I18n\DateTime::now(),
        ];
        $settings[MfaSettings::PROVIDER_TOTP] = [
            MfaAccountSettings::VERIFIED => $verified ?? \Cake\I18n\DateTime::now(),
            MfaAccountSettings::OTP_PROVISIONING_URI => $uri,
        ];
        $value = json_encode($settings);

        return $this->patchData(compact('value'));
    }
}
