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
 * @since         4.9.0
 */
namespace Passbolt\Scim\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Utility\Security;
use Passbolt\DirectorySync\Test\Utility\LdapConfigurationTestUtility;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;
use Passbolt\Scim\Service\ScimBaseSettingsService;

/**
 * DirectoryOrgSettingFactory
 */
class ScimOrgSettingFactory extends OrganizationSettingFactory
{
    public const SCIM_TEST_SETTING_ID = '818b3361-e1a5-40cd-b423-775f1bd35c17';
    public const SCIM_TEST_SECRET_TOKEN = 'pb_TESTTOKEN_FOR_SCIM_INTEGRATION_IN_PB';
    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        $property = OrganizationSetting::UUID_NAMESPACE . ScimBaseSettingsService::SCIM_SETTINGS_PROPERTY_NAME;
        $this->patchData([
            'property' => ScimBaseSettingsService::SCIM_SETTINGS_PROPERTY_NAME,
            'property_id' => UuidFactory::uuid($property),
        ]);
    }

    /**
     * @return $this
     */
    public function default()
    {
        return $this->value($this->getDefaultValue());
    }

    public function getDefaultValue(): array
    {
        return [
            'setting_id' => self::SCIM_TEST_SETTING_ID,
            'scim_user_id' => UserFactory::make()->admin()->persist()->get('id'),
            'secret_token' => Security::hash(self::SCIM_TEST_SECRET_TOKEN, 'sha256')
        ];
    }
}
