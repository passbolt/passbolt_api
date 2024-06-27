<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.9.0
 */
namespace Passbolt\DirectorySync\Test\Utility;

use App\Test\Factory\UserFactory;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class LdapConfigurationTestUtility
{
    /**
     * Get dummy test settings data
     *
     * @param bool $addSecondDomain
     * @return array
     */
    public static function getDummyFormData(bool $addSecondDomain = false): array
    {
        $defaultConfigSettings = DirectoryOrgSettings::getDefaultSettings();

        $settings = [
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
            'user_path' => 'my user_path',
            'group_custom_filters' => 'group-custom-filters',
            'user_custom_filters' => 'user-custom-filters',
            'group_object_class' => 'my group_object_class',
            'user_object_class' => 'my user_object_class',
            'use_email_prefix_suffix' => true,
            'email_prefix' => 'uid',
            'email_suffix' => '@passbolt.com',
            'default_user' => UserFactory::make()->admin()->persist()->get('id'),
            'default_group_admin_user' => UserFactory::make()->user()->persist()->get('id'),
            'sync_users_create' => true,
            'sync_users_delete' => false,
            'sync_users_update' => true,
            'sync_groups_create' => true,
            'sync_groups_delete' => false,
            'sync_groups_update' => true,
            'fields_mapping' => $defaultConfigSettings['fieldsMapping'],
            'field_fallbacks' => [
                'ad' => ['username' => ''],
            ],
        ];
        if ($addSecondDomain) {
            $settings['domains']['org_domain_2'] = [
                'directory_type' => 'ad',
                'hosts' => ['127.0.0.1'],
                'domain_name' => 'ldap2.passbolt.local',
                'authentication_type' => 'basic',
                'connection_type' => 'tls',
                'host' => 'my host',
                'port' => 999,
                'username' => 'root',
                'password' => 'password',
                'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
            ];
        }

        return $settings;
    }
}
