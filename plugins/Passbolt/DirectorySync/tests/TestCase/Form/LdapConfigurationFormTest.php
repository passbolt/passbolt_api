<?php
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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Form;

use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\TestSuite\TestCase;
use Passbolt\DirectorySync\Form\LdapConfigurationForm;
use Cake\Utility\Hash;
use App\Model\Entity\Role;

class LdapConfigurationFormTest extends TestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/secrets', 'app.Base/roles',
        'app.Base/groups_users', 'app.Base/permissions', 'app.Base/avatars',
        'app.Base/favorites', 'app.Base/organization_settings'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->ldapConfigurationForm = new LdapConfigurationForm();
    }


    public function testDefaultUserValidationError() {
        $data = [
            'default_user' => 'notavalidemail',
        ];
        $validate = $this->ldapConfigurationForm->validate($data);
        $errors = $this->ldapConfigurationForm->errors();
        $this->assertFalse($validate);
        $this->assertTrue(isset($errors['default_user']['email']));
        $this->assertEquals($errors['default_user']['email'], 'Default user should be an email');

        $data = [
            'default_user' => 'notexist@passbolt.com',
        ];
        $this->ldapConfigurationForm->validate($data);
        $errors = $this->ldapConfigurationForm->errors();
        $this->assertTrue(isset($errors['default_user']));
        $this->assertEquals($errors['default_user']['isValidAdmin'], 'The admin user provided does not exist.');
    }

    public function testDefaultUserValidationSuccess() {
        $data = [
            'default_user' => 'admin@passbolt.com',
        ];
        $validate = $this->ldapConfigurationForm->validate($data);
        $errors = $this->ldapConfigurationForm->errors();
        $this->assertFalse(isset($errors['default_user']['email']));
        $this->assertFalse(isset($errors['default_user']['isValidAdmin']));
    }

    public function testDefaultGroupAdminUserValidationError() {
        $data = [
            'default_group_admin_user' => 'notavalidemail',
        ];
        $validate = $this->ldapConfigurationForm->validate($data);
        $errors = $this->ldapConfigurationForm->errors();
        $this->assertFalse($validate);
        $this->assertTrue(isset($errors['default_group_admin_user']['email']));
        $this->assertEquals($errors['default_group_admin_user']['email'], 'Default group admin user should be an email');

        $data = [
            'default_group_admin_user' => 'notexist@passbolt.com',
        ];
        $this->ldapConfigurationForm->validate($data);
        $errors = $this->ldapConfigurationForm->errors();
        $this->assertTrue(isset($errors['default_group_admin_user']));
        $this->assertEquals($errors['default_group_admin_user']['isValidUser'], 'The group admin user provided does not exist.');
    }

    public function testDataToConfig() {
        $data = [
            'directory_type' => 'ad',
            'domain_name' => 'passbolt.local',
            'username' => 'root',
            'password' => 'test',
            'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
            'server' => '127.0.0.1',
            'port' => '636',
            'group_object_class' => 'groupObjectClass',
            'user_object_class' => 'userObjectClass',
            'group_path' => '',
            'user_path' => '',
            'default_user' => 'admin@passbolt.com',
            'default_group_admin_user' => 'ada@passbolt.com',
            'connection_type' => 'tls',
        ];
        $config = $this->ldapConfigurationForm->dataToConfig($data);

        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.ldap_type'), 'ad');
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.domain_name'), 'passbolt.local');
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.username'), 'root');
        $this->assertEquals(preg_match('/BEGIN PGP MESSAGE/', Hash::get($config, 'ldap.domains.org_domain.password')), 1);
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.base_dn'), 'OU=PassboltUsers,DC=passbolt,DC=local');
        $this->assertFalse(isset($config['groupPath']));
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.servers.0'), '127.0.0.1');
    }

    public function testConfigToData() {
        $config = [
            'userPath' => 'CN=Operations',
            'defaultUser' => 'adminpassbolt.com',
            'defaultGroupAdminUser' => 'ada@passbolt.com',
            'ldap' => [
                'domains' => [
                    'org_domain' => [
                        'domain_name' => 'passbolt.local',
                        'username' => 'root',
                        'password' => OrganizationSettingsTable::encryptData('test'),
                        'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
                        'servers' => ['127.0.0.1'],
                        'port' => 636,
                        'use_ssl' => false,
                        'ldap_type' => 'ad',
                    ],
                ],
            ]
        ];

        $data = $this->ldapConfigurationForm->configToData($config);
        $this->assertEquals($data['directory_type'], 'ad');
        $this->assertEquals($data['domain_name'], 'passbolt.local');
        $this->assertEquals($data['username'], 'root');
        $this->assertEquals($data['password'], 'test');
        $this->assertEquals($data['connection_type'], 'plain');
        $this->assertEquals($data['base_dn'], 'OU=PassboltUsers,DC=passbolt,DC=local');
        $this->assertFalse(isset($data['group_path']));
    }

    public function testSaveReadConfiguration() {
        $userAccess = new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
        $data = [
            'directory_type' => 'ad',
            'domain_name' => 'passbolt.local',
            'username' => 'root',
            'password' => 'test',
            'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
            'server' => '127.0.0.1',
            'port' => '636',
            'group_object_class' => 'groupObjectClass',
            'user_object_class' => 'userObjectClass',
            'group_path' => '',
            'user_path' => '',
            'default_user' => 'admin@passbolt.com',
            'default_group_admin_user' => 'ada@passbolt.com',
            'connection_type' => 'tls',
        ];

        $savedConfig = $this->ldapConfigurationForm->saveConfiguration($data, $userAccess);
        $this->assertEquals(count($savedConfig), 13);

        $readConfig = $this->ldapConfigurationForm->readConfiguration();
        // Remove empty values since they should not be saved.
        unset($data['group_path']);
        unset($data['user_path']);

        // Assert that the configuration returned is the same as the one sent.
        $this->assertEquals($readConfig, $data);
    }
}