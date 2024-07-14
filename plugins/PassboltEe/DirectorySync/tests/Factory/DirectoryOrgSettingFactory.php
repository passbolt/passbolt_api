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
namespace Passbolt\DirectorySync\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Test\Utility\LdapConfigurationTestUtility;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

/**
 * DirectoryOrgSettingFactory
 */
class DirectoryOrgSettingFactory extends OrganizationSettingFactory
{
    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        $property = OrganizationSetting::UUID_NAMESPACE . DirectoryOrgSettings::ORG_SETTINGS_PROPERTY;
        $this->patchData([
            'property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY,
            'property_id' => UuidFactory::uuid($property),
        ]);
    }

    /**
     * @return $this
     */
    public function default()
    {
        return $this->value(LdapConfigurationTestUtility::getDummyFormData());
    }

    /**
     * @return $this
     */
    public function deleteUserBehaviorDisable()
    {
        $data = $this->getDefaultValue() + [
            Alias::DELETE_USER_BEHAVIOR_MAPPING_KEY => Alias::DELETE_USER_BEHAVIOR_DISABLE,
        ];

        return $this->value($data);
    }

    /**
     * @return $this
     */
    public function deleteUserBehaviorInvalid()
    {
        $data = $this->getDefaultValue() + [
            Alias::DELETE_USER_BEHAVIOR_MAPPING_KEY => $this->getFaker()->word(),
        ];

        return $this->value($data);
    }

    public function getDefaultValue(): array
    {
        return [
            'enabled' => true,
            'domains' => [
                'org_domain' => [
                    'directory_type' => 'ad',
                    'hosts' => ['127.0.0.1'],
                    'domain_name' => 'ldap.passbolt.local',
                    'authentication_type' => 'basic',
                    'connection_type' => 'tls',
                    'host' => 'my host',
                    'port' => 999,
                    'username' => 'root',
                    'password' => 'password',
                    'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
                ],
            ],
            'userPath' => 'my user_path',
            'groupCustomFilters' => 'group-custom-filters',
            'userCustomFilters' => 'user-custom-filters',
            'groupObjectClass' => 'my group_object_class',
            'userObjectClass' => 'my user_object_class',
            'useEmailPrefixSuffix' => true,
            'emailPrefix' => 'uid',
            'emailSuffix' => '@passbolt.com',
            'defaultUser' => $this->getFaker()->uuid(),
            'defaultGroupAdminUser' => $this->getFaker()->uuid(),
            'jobs' => [
                'users' => [
                    'create' => true,
                    'delete' => true,
                    'update' => true,
                ],
                'groups' => [
                    'create' => true,
                    'delete' => true,
                    'update' => true,
                ],
            ],
            'fieldsMapping' => Configure::read('passbolt.plugins.directorySync.fieldsMapping'),
            'fieldFallbacks' => [
                'ad' => ['username' => ''],
            ],
        ];
    }
}
