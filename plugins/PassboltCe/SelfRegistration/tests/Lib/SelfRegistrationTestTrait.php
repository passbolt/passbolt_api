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
 * @since         3.10.0
 */

namespace Passbolt\SelfRegistration\Test\Lib;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\OrganizationSettingFactory;
use Passbolt\SelfRegistration\Service\SelfRegistrationBaseSettingsService;

trait SelfRegistrationTestTrait
{
    private function getSelfRegistrationSettingsData(?string $field = null, $value = null): array
    {
        $validData = [
            'provider' => 'email_domains',
            'data' => ['allowed_domains' => [
                'passbolt.com',
            ]],
        ];

        if (isset($field)) {
            $validData[$field] = $value;
        }

        return $validData;
    }

    private function setSelfRegistrationSettingsData(?string $field = null, $value = null): OrganizationSetting
    {
        $data = $this->getSelfRegistrationSettingsData($field, $value);

        /** @var \App\Model\Entity\OrganizationSetting $setting */
        $setting = OrganizationSettingFactory::make()
            ->setPropertyAndValue(
                SelfRegistrationBaseSettingsService::USER_SELF_REGISTRATION_SETTINGS_PROPERTY_NAME,
                $data
            )->persist();

        return $setting;
    }

    private function getExpectedKeys(): array
    {
        return [
            'id',
            'provider',
            'data',
            'created',
            'modified',
            'created_by',
            'modified_by',
        ];
    }
}
