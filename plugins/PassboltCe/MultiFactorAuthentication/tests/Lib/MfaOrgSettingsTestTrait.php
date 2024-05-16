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
 * @since         2.6.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\Lib;

use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

trait MfaOrgSettingsTestTrait
{
    /**
     * @param array $data org settings ['providers' => [...
     * @param string $type configure or database
     * @param UserAccessControl $user optional, only used when $type is database
     */
    public function mockMfaOrgSettings(array $data, string $type = 'configure', ?UserAccessControl $user = null)
    {
        if ($type === 'configure') {
            Configure::write('passbolt.plugins.multiFactorAuthentication', $data);
        } else {
            $data = json_encode($data);
            /** @var \App\Model\Table\OrganizationSettingsTable $OrgSettings */
            $OrgSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
            $OrgSettings->createOrUpdateSetting(MfaSettings::MFA, $data, $user);
        }
    }

    public function getDefaultMfaOrgSettings(): array
    {
        return [
            MfaSettings::PROVIDERS => [
                MfaSettings::PROVIDER_TOTP => true,
                MfaSettings::PROVIDER_DUO => true,
                MfaSettings::PROVIDER_YUBIKEY => true,
            ],
            MfaSettings::PROVIDER_YUBIKEY => [
                'clientId' => '12345',
                'secretKey' => 'i2/j3jIQBO/axOl3ah4mlgXlXUY=',
            ],
            MfaSettings::PROVIDER_DUO => $this->getDefaultDuoV4OrgSettings(),
        ];
    }

    /**
     * This function returns dummy org settings for the old Duo version (v2)
     *
     * @return array
     */
    public function getDefaultDuoV2OrgSettings(): array
    {
        // SEC-5652 Note to security researchers: these are not leaked credentials
        // They look valid as they should pass validation, but are fake
        return [
            'salt' => 'pG2y71Uu0wx3PsnWvtGom2CK9AGouV5oW84VHtwQ',
            'integrationKey' => 'UICPIC93F14RWR5F55SJ',
            'secretKey' => '8tkYNgi8aGAqa3KW1eqhsJLfjc1nJnHDYC1siNYX',
            'hostName' => 'api-45e9f2ca.duosecurity.com',
        ];
    }

    /**
     * This function returns dummy org settings for the new Duo version (v4)
     *
     * @return array
     */
    public function getDefaultDuoV4OrgSettings(): array
    {
        // SEC-5652 Note to security researchers: these are not leaked credentials
        // They look valid as they should pass validation, but are fake
        return [
            'clientId' => 'UICPIC93F14RWR5F55SJ',
            'clientSecret' => '8tkYNgi8aGAqa3KW1eqhsJLfjc1nJnHDYC1siNYX',
            'apiHostName' => 'api-45e9f2ca.duosecurity.com',
        ];
    }

    public function getMfaOrganizationSettingValue(): array
    {
        /** @var \App\Model\Entity\OrganizationSetting $settingInDb */
        $settingInDb = MfaOrganizationSettingFactory::find()->firstOrFail();
        $this->assertSame('mfa', $settingInDb->property);

        return json_decode($settingInDb->value, true);
    }
}
