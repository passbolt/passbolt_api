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
 * @since         2.5.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Utility;

use App\Model\Entity\Role;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\DirectorySyncPlugin;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class DirectoryOrgSettingsTest extends AppTestCase
{
    use UserAccessControlTrait;

    public $fixtures = [
        'app.Base/Users',
        'app.Base/Roles',
    ];

    /**
     * Get dummy test settings data
     *
     * @param bool $addSecondDomain
     * @return array
     */
    public static function getDummySettings(bool $addSecondDomain = false)
    {
        $settings = [
            'enabled' => true,
            'userPath' => 'CN=Operations',
            'defaultUser' => 'admin@passbolt.com',
            'defaultGroupAdminUser' => 'ada@passbolt.com',
            'jobs' => [
                'users' => [
                    'create' => true,
                    'delete' => false,
                    'update' => true,
                ],
                'groups' => [
                    'create' => true,
                    'delete' => false,
                    'update' => true,
                ],
            ],
            'ldap' => [
                'domains' => [
                    'org_domain' => [
                        'domain_name' => 'passbolt.local',
                        'username' => 'root',
                        'password' => 'password',
                        'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
                        'hosts' => ['127.0.0.1'],
                        'port' => 636,
                        'use_ssl' => true,
                        'use_tls' => false,
                        'ldap_type' => 'ad',
                        'use_sasl' => false,
                    ],
                ],
            ],
        ];
        if ($addSecondDomain) {
            $settings['ldap']['org_domain2'] = [
                'domain_name' => 'passbolt2.local',
                'username' => 'root',
                'password' => 'password',
                'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
                'hosts' => ['127.0.0.1'],
                'port' => 636,
                'use_ssl' => true,
                'use_tls' => false,
                'ldap_type' => 'ad',
                'use_sasl' => true,
            ];
        }

        return $settings;
    }

    /**
     * @group directorySync
     * @group directoryOrgSettings
     */
    public function testDirectoryOrgSettings_GetEmpty()
    {
        $directorySettings = DirectoryOrgSettings::get();
        $this->assertFalse($directorySettings->isEnabled());
    }

    /**
     * @group directorySync
     * @group directoryOrgSettings
     */
    public function testDirectoryOrgSettings_SaveSuccess()
    {
        $uac = $this->mockUserAccessControl('admin', Role::ADMIN);
        $settings = self::getDummySettings(true);
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);

        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $settings = json_decode($OrganizationSettings->getFirstSettingOrFail(DirectoryOrgSettings::ORG_SETTINGS_PROPERTY)->value, true);
        $domains = Hash::get($settings, 'ldap.domains', []);
        foreach ($domains as $domain) {
            $this->assertEquals(preg_match('/BEGIN PGP MESSAGE/', $domain['password']), 1);
        }
    }

    /**
     * @group directorySync
     * @group directoryOrgSettings
     */
    public function testDirectoryOrgSettings_SaveAndGetSuccess()
    {
        $uac = $this->mockUserAccessControl('admin', Role::ADMIN);
        $settings = self::getDummySettings();
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);

        // Merge with default config.
        $defaultSettings = require DirectorySyncPlugin::PLUGIN_CONFIG_PATH . 'config.php';
        $settings = Hash::merge(Hash::get($defaultSettings, 'passbolt.plugins.directorySync'), $settings);

        $settings = array_merge(['source' => 'db'], $settings);
        $retrievedDirectoryOrgSettings = DirectoryOrgSettings::get();
        $this->assertEquals($settings, $retrievedDirectoryOrgSettings->toArray());
    }

    /**
     * Test getPassword
     *
     * @return void
     */
    public function testDirectoryOrgSettings_getPassword()
    {
        $settings = self::getDummySettings();
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $this->assertSame('password', $directoryOrgSettings->getPassword());
    }
}
