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

use App\Model\Entity\Role;
use App\Model\Table\OrganizationSettingsTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Form\LdapConfigurationForm;
use Passbolt\DirectorySync\Test\TestCase\Utility\DirectoryOrgSettingsTest;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class LdapConfigurationFormTest extends AppTestCase
{
    use FormatValidationTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Secrets', 'app.Base/Roles',
        'app.Base/GroupsUsers', 'app.Base/Permissions', 'app.Base/Avatars',
        'app.Base/Favorites', 'app.Base/OrganizationSettings'
    ];

    public static function getDummyFormData()
    {
        return [
            'directory_type' => 'ad',
            'domain_name' => 'ldap.passbolt.local',
            'connection_type' => 'tls',
            'server' => '127.0.0.1',
            'host' => 'my host',
            'port' => 999,
            'username' => 'root',
            'password' => 'password',
            'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
            'user_path' => 'my user_path',
            'group_object_class' => 'my group_object_class',
            'user_object_class' => 'my user_object_class',
            'use_email_prefix_suffix' => true,
            'email_prefix' => 'uid',
            'email_suffix' => '@passbolt.com',
            'default_user' => UuidFactory::uuid('user.id.admin'),
            'default_group_admin_user' => UuidFactory::uuid('user.id.ada'),
            'sync_users_create' => true,
            'sync_users_delete' => false,
            'sync_groups_create' => true,
            'sync_groups_delete' => false,
            'sync_groups_update' => true
        ];
    }

    public function testLdapConfigurationFormValidateError_DirectoryType()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(['ad', 'openldap'])
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'directory_type', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_DomainName()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domain_name', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_Username()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'username', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_Password()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'password', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_BaseDn()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'base_dn', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_Server()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'server', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_Port()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'range' => self::getRangeTestCases(0, 65535)
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'port', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_ConnectionType()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(LdapConfigurationForm::$connectionTypes)
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'connection_type', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_DefaultUser()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'uuid' => [
                'rule_name' => 'uuid',
                'test_cases' => [
                    'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
                    UuidFactory::uuid('user.id.admin') => true,
                ],
            ],
            'isValidAdmin' => [
                'rule_name' => 'isValidAdmin',
                'test_cases' => [
                    UuidFactory::uuid('user.id.ada') => false,
                    UuidFactory::uuid('user.id.admin') => true,
                ],
            ]
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'default_user', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_DefaultGroupAdminUser()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'uuid' => [
                'rule_name' => 'uuid',
                'test_cases' => [
                    'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
                    UuidFactory::uuid('user.id.ada') => true,
                    UuidFactory::uuid('user.id.admin') => true,
                ],
            ],
            'isValidUser' => [
                'rule_name' => 'isValidUser',
                'test_cases' => [
                    UuidFactory::uuid('user.id.ada') => true,
                    UuidFactory::uuid('user.id.admin') => true,
                    UuidFactory::uuid('user.id.ruth') => false
                ],
            ]
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'default_group_admin_user', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_GroupObjectClass()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'group_object_class', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_UserObjectClass()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'user_object_class', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_GroupPath()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'group_path', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_UserPath()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'user_path', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_UseEmailPrefixSuffix()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getBooleanTestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'use_email_prefix_suffix', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_EmailPrefix()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'email_prefix', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_EmailSuffix()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'email_suffix', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_SyncUsersCreate()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_users_create', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_SyncUsersDelete()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_users_delete', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_SyncGroupsCreate()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_groups_create', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_SyncGroupsDelete()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_groups_delete', $ldapSettings, $testCases);
    }

    public function testLdapConfigurationFormValidateError_SyncGroupsUpdate()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases()
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_groups_update', $ldapSettings, $testCases);
    }

    public function testFormatFormDataToOrgSettings()
    {
        $data = self::getDummyFormData();
        $config = LdapConfigurationForm::formatFormDataToOrgSettings($data);

        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.ldap_type'), 'ad');
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.domain_name'), 'ldap.passbolt.local');
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.username'), 'root');
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.password'), 'password');
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.base_dn'), 'OU=PassboltUsers,DC=passbolt,DC=local');
        $this->assertFalse(isset($config['groupPath']));
        $this->assertEquals(Hash::get($config, 'jobs.users.create'), true);
        $this->assertEquals(Hash::get($config, 'jobs.users.delete'), false);
        $this->assertEquals(Hash::get($config, 'jobs.groups.create'), true);
        $this->assertEquals(Hash::get($config, 'jobs.groups.delete'), false);
        $this->assertEquals(Hash::get($config, 'jobs.groups.update'), true);
    }

    public function testFormatOrgSettingsToFormData()
    {
        $settings = DirectoryOrgSettingsTest::getDummySettings();
        $formData = LdapConfigurationForm::formatOrgSettingsToFormData($settings);

        $this->assertEquals('ad', $formData['directory_type']);
        $this->assertEquals('passbolt.local', $formData['domain_name']);
        $this->assertEquals('root', $formData['username']);
        $this->assertEquals('password', $formData['password']);
        $this->assertEquals('ssl', $formData['connection_type']);
        $this->assertEquals('OU=PassboltUsers,DC=passbolt,DC=local', $formData['base_dn']);
        $this->assertFalse(isset($formData['group_path']));
        $this->assertTrue($formData['sync_users_create']);
        $this->assertFalse($formData['sync_users_delete']);
        $this->assertTrue($formData['sync_groups_create']);
        $this->assertFalse($formData['sync_groups_delete']);
        $this->assertTrue($formData['sync_groups_update']);
    }
}
