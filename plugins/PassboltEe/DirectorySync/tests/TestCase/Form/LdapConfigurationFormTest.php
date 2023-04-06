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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Form;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Form\LdapConfigurationForm;
use Passbolt\DirectorySync\Test\TestCase\Utility\DirectoryOrgSettingsTest;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class LdapConfigurationFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    public static function getDummyFormData()
    {
        return [
            'enabled' => true,
            'hosts' => ['127.0.0.1'],
            'directory_type' => 'ad',
            'domain_name' => 'ldap.passbolt.local',
            'authentication_type' => 'basic',
            'connection_type' => 'tls',
            'host' => 'my host',
            'port' => 999,
            'username' => 'root',
            'password' => 'password',
            'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
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
            'fields_mapping' => [
                'ad' => [
                    'user' => [
                        'id' => 'custom1',
                        'firstname' => 'custom2',
                        'lastname' => 'custom3',
                        'username' => 'custom4',
                        'created' => 'custom5',
                        'modified' => 'custom6',
                        'groups' => 'custom7',
                        'enabled' => 'custom8',
                    ],
                    'group' => [
                        'id' => 'custom9',
                        'name' => 'custom10',
                        'created' => 'custom11',
                        'modified' => 'custom12',
                        'users' => 'custom13',
                    ],
                ],
            ],
        ];
    }

    public function testDirectoryLdapConfigurationFormValidateError_DirectoryType()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(['ad', 'openldap']),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'directory_type', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_DomainName()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domain_name', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_Username()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'username', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_Password()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'password', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_BaseDn()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'base_dn', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_Hosts()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
        ];

        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'hosts', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_Port()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'range' => self::getRangeTestCases(0, 65535),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'port', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_ConnectionType()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(LdapConfigurationForm::$connectionTypes),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'connection_type', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_DefaultUser()
    {
        $userId = UserFactory::make()->user()->persist()->get('id');
        $adminId = UserFactory::make()->admin()->persist()->get('id');
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'uuid' => [
                'rule_name' => 'uuid',
                'test_cases' => [
                    'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
                    $adminId => true,
                ],
            ],
            'isValidAdmin' => [
                'rule_name' => 'isValidAdmin',
                'test_cases' => [
                    $userId => false,
                    $adminId => true,
                ],
            ],
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'default_user', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_DefaultGroupAdminUser()
    {
        $adminId = UserFactory::make()->admin()->persist()->get('id');
        $activeUserId = UserFactory::make()->user()->persist()->get('id');
        $inactiveUserId = UserFactory::make()->inactive()->user()->persist()->get('id');

        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'uuid' => [
                'rule_name' => 'uuid',
                'test_cases' => [
                    'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
                    $activeUserId => true,
                    $adminId => true,
                ],
            ],
            'isValidUser' => [
                'rule_name' => 'isValidUser',
                'test_cases' => [
                    $activeUserId => true,
                    $adminId => true,
                    $inactiveUserId => false,
                ],
            ],
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'default_group_admin_user', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_GroupObjectClass()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'group_object_class', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_UserObjectClass()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'user_object_class', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_GroupPath()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'group_path', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_UserPath()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'user_path', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_GroupCustomFilters()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'group_custom_filters', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_UserCustomFilters()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'user_custom_filters', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_UseEmailPrefixSuffix()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getBooleanTestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'use_email_prefix_suffix', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_EmailPrefix()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'email_prefix', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_EmailSuffix()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'email_suffix', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_SyncUsersCreate()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_users_create', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_SyncUsersDelete()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_users_delete', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_SyncUsersUpdate()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_users_update', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_SyncGroupsCreate()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_groups_create', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_SyncGroupsDelete()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_groups_delete', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_SyncGroupsUpdate()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'boolean' => self::getBooleanTestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'sync_groups_update', $ldapSettings, $testCases);
    }

    public function testDirectoryFormatFormDataToOrgSettings()
    {
        $data = self::getDummyFormData();
        $config = LdapConfigurationForm::formatFormDataToOrgSettings($data);

        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.ldap_type'), 'ad');
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.domain_name'), 'ldap.passbolt.local');
        $this->assertEquals($data['hosts'], Hash::get($config, 'ldap.domains.org_domain.hosts'));
        $this->assertIsArray(Hash::get($config, 'ldap.domains.org_domain.hosts'));
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.username'), 'root');
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.password'), 'password');
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.use_sasl'), false);
        $this->assertEquals(Hash::get($config, 'ldap.domains.org_domain.base_dn'), 'OU=PassboltUsers,DC=passbolt,DC=local');
        $this->assertFalse(isset($config['groupPath']));
        $this->assertEquals(Hash::get($config, 'jobs.users.create'), true);
        $this->assertEquals(Hash::get($config, 'jobs.users.delete'), false);
        $this->assertEquals(Hash::get($config, 'jobs.users.update'), true);
        $this->assertEquals(Hash::get($config, 'jobs.groups.create'), true);
        $this->assertEquals(Hash::get($config, 'jobs.groups.delete'), false);
        $this->assertEquals(Hash::get($config, 'jobs.groups.update'), true);

        $this->assertEquals($data['group_custom_filters'], Hash::get($config, 'groupCustomFilters'));
        $this->assertEquals($data['user_custom_filters'], Hash::get($config, 'userCustomFilters'));
        $this->assertEquals($data['fields_mapping'], Hash::get($config, 'fieldsMapping'));
    }

    /**
     * Test formatFormDataToOrgSettings when password
     * has been removed from data.
     *
     * @return void
     */
    public function testDirectoryFormatFormDataToOrgSettings_emptyPassword()
    {
        $uac = $this->mockUserAccessControl('admin', Role::ADMIN);
        $settings = DirectoryOrgSettingsTest::getDummySettings();
        $settings['ldap']['domains']['org_domain']['password'] = 'test-password';
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);

        $data = self::getDummyFormData();
        unset($data['password']);
        $config = LdapConfigurationForm::formatFormDataToOrgSettings($data);

        $this->assertEquals('test-password', Hash::get($config, 'ldap.domains.org_domain.password'));
    }

    public function testDirectoryFormatOrgSettingsToFormData()
    {
        $settings = DirectoryOrgSettingsTest::getDummySettings();
        $formData = LdapConfigurationForm::formatOrgSettingsToFormData($settings);

        $this->assertEquals('ad', $formData['directory_type']);
        $this->assertEquals('passbolt.local', $formData['domain_name']);
        $this->assertEquals('root', $formData['username']);
        $this->assertIsArray($formData['hosts']);
        $this->assertEquals($settings['ldap']['domains']['org_domain']['hosts'], $formData['hosts']);
        $this->assertEquals('password', $formData['password']);
        $this->assertEquals('ssl', $formData['connection_type']);
        $this->assertEquals('OU=PassboltUsers,DC=passbolt,DC=local', $formData['base_dn']);
        $this->assertFalse(isset($formData['group_path']));
        $this->assertTrue($formData['sync_users_create']);
        $this->assertFalse($formData['sync_users_delete']);
        $this->assertTrue($formData['sync_users_update']);
        $this->assertTrue($formData['sync_groups_create']);
        $this->assertFalse($formData['sync_groups_delete']);
        $this->assertTrue($formData['sync_groups_update']);
        $this->assertSame(LdapConfigurationForm::AUTHENTICATION_TYPE_BASIC, $formData['authentication_type']);
    }

    /**
     * Test form data to org settings with SASL
     *
     * @return void
     */
    public function testDirectoryFormatFormDataToOrgSettings_withSasl()
    {
        $data = self::getDummyFormData();
        $data['authentication_type'] = LdapConfigurationForm::AUTHENTICATION_TYPE_SASL;
        $config = LdapConfigurationForm::formatFormDataToOrgSettings($data);
        $this->assertSame(1, Hash::get($config, 'ldap.domains.org_domain.use_sasl'));
    }

    /**
     * Test org settings to form data with SASL
     *
     * @return void
     */
    public function testDirectoryFormatOrgSettingsToFormData_withSasl()
    {
        $settings = DirectoryOrgSettingsTest::getDummySettings();
        $settings['ldap']['domains']['org_domain']['use_sasl'] = true;

        $formData = LdapConfigurationForm::formatOrgSettingsToFormData($settings);

        $this->assertSame(LdapConfigurationForm::AUTHENTICATION_TYPE_SASL, $formData['authentication_type']);
    }
}
