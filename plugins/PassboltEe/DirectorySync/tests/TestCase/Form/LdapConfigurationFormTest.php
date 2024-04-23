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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Form;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Core\Configure;
use Cake\Event\EventDispatcherTrait;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Form\LdapConfigurationForm;
use Passbolt\DirectorySync\Test\TestCase\Utility\DirectoryOrgSettingsTest;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class LdapConfigurationFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

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

    public function testDirectoryLdapConfigurationFormValidateError_DirectoryType()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(['ad', 'openldap']),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domains.org_domain.directory_type', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_DomainName()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domains.org_domain.domain_name', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_Username()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domains.org_domain.username', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_Password()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domains.org_domain.password', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_BaseDn()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowEmpty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domains.org_domain.base_dn', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_Hosts()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
        ];

        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domains.org_domain.hosts', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_Port()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'range' => self::getRangeTestCases(0, 65535),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domains.org_domain.port', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_ConnectionType()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(LdapConfigurationForm::$connectionTypes),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domains.org_domain.connection_type', $ldapSettings, $testCases);
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

    /**
     * Test
     * Note: using dataProvider because the value to modify is an array
     *
     * @param string $dataPath
     * @param mixed $data Data to set, can be an array or string generally.
     * @param array $expectedErrors
     * @return void
     * @dataProvider provideTestDirectoryLdapConfigurationFormValidateError_FieldsMapping
     */
    public function testDirectoryLdapConfigurationFormValidateError_FieldsMapping(string $dataPath, $data, array $expectedErrors)
    {
        $ldapSettings = self::getDummyFormData();
        $ldapSettings = Hash::insert($ldapSettings, $dataPath, $data);
        $form = new LdapConfigurationForm();
        static::assertFalse($form->validate($ldapSettings));
        $errors = $form->getErrors();
        static::assertSame($expectedErrors, $errors['fields_mapping']);
    }

    /**
     * Provider for testDirectoryLdapConfigurationFormValidateError_FieldsMapping
     *
     * @return array[]
     */
    public function provideTestDirectoryLdapConfigurationFormValidateError_FieldsMapping(): array
    {
        $dummySettingsData = self::getDummyFormData();

        return [
            [
                'dataPath' => 'fields_mapping.ad',
                'data' => [],
                'expectedErrors' => [
                    'ad' => [
                        'user' => [
                            '_required' => 'The map configuration for `user` fields is required.',
                        ],
                        'group' => [
                            '_required' => 'The map configuration for `group` fields is required.',
                        ],
                    ],
                ],
            ],
            [
                'dataPath' => 'fields_mapping.openldap',
                'data' => [],
                'expectedErrors' => [
                    'openldap' => [
                        'user' => [
                            '_required' => 'The map configuration for `user` fields is required.',
                        ],
                        'group' => [
                            '_required' => 'The map configuration for `group` fields is required.',
                        ],
                    ],
                ],
            ],
            [
                'dataPath' => 'fields_mapping.ad.user',
                'data' => [
                    'id' => 1234,
                    'firstname' => 'custom2',
                    'lastname' => 'custom3',
                    'username' => 'custom4',
                    'created' => 'custom5',
                ],
                'expectedErrors' => [
                    'ad' => [
                        'user' => [
                            'id' => [
                                'utf8' => 'The field name should be a valid BMP-UTF8 string.',
                            ],
                            'modified' => [
                                '_required' => 'The map for this field is required.',
                            ],
                            'groups' => [
                                '_required' => 'The map for this field is required.',
                            ],
                            'enabled' => [
                                '_required' => 'The map for this field is required.',
                            ],
                        ],
                    ],
                ],
            ],
            /**
             * Max length for fields mapping fields' values.
             */
            [
                'dataPath' => 'fields_mapping.openldap.user',
                'data' => array_replace(
                    $dummySettingsData['fields_mapping']['openldap']['user'],
                    [
                        'firstname' => self::getStringMask('alphaASCII', 150),
                        'username' => self::getStringMask('alphaASCII', 130),
                    ]
                ),
                'expectedErrors' => [
                    'openldap' => [
                        'user' => [
                            'firstname' => [
                                'maxLength' => 'The map value length should be maximum 128 characters.',
                            ],
                            'username' => [
                                'maxLength' => 'The map value length should be maximum 128 characters.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'dataPath' => 'fields_mapping.ad.group',
                'data' => array_replace(
                    $dummySettingsData['fields_mapping']['ad']['group'],
                    [
                        'created' => self::getStringMask('alphaASCII', 129),
                        'users' => self::getStringMask('alphaASCII', 150),
                    ]
                ),
                'expectedErrors' => [
                    'ad' => [
                        'group' => [
                            'created' => [
                                'maxLength' => 'The map value length should be maximum 128 characters.',
                            ],
                            'users' => [
                                'maxLength' => 'The map value length should be maximum 128 characters.',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function testDirectoryLdapConfigurationFormValidateError_GroupObjectClass()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(128),
            'maxLength' => self::getMaxLengthTestCases(128),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'group_object_class', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_UserObjectClass()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(128),
            'maxLength' => self::getMaxLengthTestCases(128),
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
            'maxLength' => self::getMaxLengthTestCases(10000),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'group_custom_filters', $ldapSettings, $testCases);
    }

    public function testDirectoryLdapConfigurationFormValidateError_UserCustomFilters()
    {
        $ldapSettings = self::getDummyFormData();
        $testCases = [
            'allowempty' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
            'maxLength' => self::getMaxLengthTestCases(10000),
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
        $this->assertEquals($data['domains']['org_domain']['hosts'], Hash::get($config, 'ldap.domains.org_domain.hosts'));
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
        unset($data['domains']['org_domain']['password']);
        $config = LdapConfigurationForm::formatFormDataToOrgSettings($data);

        $this->assertEquals('test-password', Hash::get($config, 'ldap.domains.org_domain.password'));
    }

    public function testDirectoryFormatOrgSettingsToFormData()
    {
        $settings = DirectoryOrgSettingsTest::getDummySettings();
        $formData = LdapConfigurationForm::formatOrgSettingsToFormData($settings);

        $this->assertEquals('ad', $formData['domains']['org_domain']['directory_type']);
        $this->assertEquals('passbolt.local', $formData['domains']['org_domain']['domain_name']);
        $this->assertEquals('root', $formData['domains']['org_domain']['username']);
        $this->assertIsArray($formData['domains']['org_domain']['hosts']);
        $this->assertEquals($settings['ldap']['domains']['org_domain']['hosts'], $formData['domains']['org_domain']['hosts']);
        $this->assertEquals('password', $formData['domains']['org_domain']['password']);
        $this->assertEquals('ssl', $formData['domains']['org_domain']['connection_type']);
        $this->assertEquals('OU=PassboltUsers,DC=passbolt,DC=local', $formData['domains']['org_domain']['base_dn']);
        $this->assertSame(LdapConfigurationForm::AUTHENTICATION_TYPE_BASIC, $formData['domains']['org_domain']['authentication_type']);
        $this->assertFalse(isset($formData['group_path']));
        $this->assertTrue($formData['sync_users_create']);
        $this->assertFalse($formData['sync_users_delete']);
        $this->assertTrue($formData['sync_users_update']);
        $this->assertTrue($formData['sync_groups_create']);
        $this->assertFalse($formData['sync_groups_delete']);
        $this->assertTrue($formData['sync_groups_update']);
    }

    /**
     * Test form data to org settings with SASL
     *
     * @return void
     */
    public function testDirectoryFormatFormDataToOrgSettings_withSasl()
    {
        $data = self::getDummyFormData();
        $data['domains']['org_domain']['authentication_type'] = LdapConfigurationForm::AUTHENTICATION_TYPE_SASL;
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

        $this->assertSame(LdapConfigurationForm::AUTHENTICATION_TYPE_SASL, $formData['domains']['org_domain']['authentication_type']);
    }

    /**
     * Test Multidomain validation success
     *
     * @return void
     */
    public function testDirectoryLdapConfigurationFormMultiDomain()
    {
        $ldapSettings = self::getDummyFormData(true);
        $form = new LdapConfigurationForm();
        static::assertTrue($form->validate($ldapSettings));
    }

    /**
     * Test
     *
     * @return void
     */
    public function testDirectoryLdapConfigurationFormValidateError_MultiDomainRequired()
    {
        $ldapSettings = self::getDummyFormData();
        $ldapSettings = Hash::insert($ldapSettings, 'domains', []);
        $form = new LdapConfigurationForm();
        static::assertFalse($form->validate($ldapSettings));
        $errors = $form->getErrors();
        static::assertSame([
            'hasAtLeast' => 'Need at least one domain configuration.',
        ], $errors['domains']);
    }

    /**
     * Test
     *
     * @return void
     */
    public function testDirectoryLdapConfigurationFormValidateError_DomainName_MultiDomain()
    {
        $ldapSettings = self::getDummyFormData(true);
        $testCases = [
            'required' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
        ];
        $this->assertFormFieldFormatValidation(LdapConfigurationForm::class, 'domains.org_domain_2.domain_name', $ldapSettings, $testCases);
    }

    /**
     * Test
     *
     * @return void
     */
    public function testDirectoryLdapConfigurationFormValidateError_MultiDomain_InvalidConnectionName()
    {
        $ldapSettings = self::getDummyFormData();
        $domainData = Hash::get($ldapSettings, 'domains.org_domain');
        unset($ldapSettings['domains']['org_domain']);
        $ldapSettings['domains']['org.domain'] = $domainData;
        $form = new LdapConfigurationForm();
        static::assertFalse($form->validate($ldapSettings));
        $errors = $form->getErrors();
        static::assertSame([
            'connection_names' => 'The connection name `org.domain` should not contain dots',
        ], $errors['domains']);
    }

    public function testDirectoryLdapConfiguration_Fields_Mapping_Is_Not_Required()
    {
        $ldapSettings = self::getDummyFormData();
        unset($ldapSettings['fields_mapping']);
        $form = new LdapConfigurationForm();
        $this->assertTrue($form->validate($ldapSettings));
    }

    public function testDirectoryLdapConfigurationForm_ThrowsValidationForbiddenFieldsActive()
    {
        $ldapSettings = self::getDummyFormData();
        // Inject sensitive field names
        $ldapSettings = Hash::insert($ldapSettings, 'fields_mapping.ad.user.username', 'userPassword');
        $ldapSettings = Hash::insert($ldapSettings, 'fields_mapping.ad.user.id', 'uniqueUserPassword');
        $ldapSettings = Hash::insert($ldapSettings, 'fields_mapping.ad.user.created', 'ms-PKI-AccountCredentials');
        $ldapSettings = Hash::insert($ldapSettings, 'fields_mapping.openldap.group.users', 'unixUserPassword');
        // Filters fields
        $ldapSettings['user_custom_filters'] = '(&(objectCategory=group)(cn=userPassword))';
        $ldapSettings['group_custom_filters'] = '(&(objectCategory=group)(cn=unixUserPassword))';
        // Object class fields
        $ldapSettings['user_object_class'] = 'userPassword';
        $ldapSettings['group_object_class'] = 'msPKI-CredentialRoamingTokens';

        $form = new LdapConfigurationForm();
        $result = $form->validate($ldapSettings);

        $this->assertFalse($result);
        $errors = $form->getErrors();
        $this->assertCount(5, $errors);
        // Fields mapping assertions
        $this->assertCount(3, $errors['fields_mapping']['ad']['user']);
        $this->assertArrayHasKey('forbiddenField', $errors['fields_mapping']['ad']['user']['username']);
        $this->assertArrayHasKey('forbiddenField', $errors['fields_mapping']['ad']['user']['id']);
        $this->assertArrayHasKey('forbiddenField', $errors['fields_mapping']['ad']['user']['created']);
        $this->assertArrayHasKey('forbiddenField', $errors['fields_mapping']['openldap']['group']['users']);
        // Filters assertions
        $this->assertArrayHasKey('containsForbiddenField', $errors['user_custom_filters']);
        $this->assertArrayHasKey('containsForbiddenField', $errors['group_custom_filters']);
        // Object class assertions
        $this->assertArrayHasKey('forbiddenField', $errors['user_object_class']);
        $this->assertArrayHasKey('forbiddenField', $errors['group_object_class']);
    }

    public function testDirectoryLdapConfigurationForm_NotThrowValidationForbiddenFieldsWhenInactive()
    {
        Configure::write('passbolt.security.directorySync.forbiddenFields.active', false);
        $ldapSettings = self::getDummyFormData();
        // Inject sensitive field names
        $ldapSettings = Hash::insert($ldapSettings, 'fields_mapping.ad.user.username', 'userPassword');
        $ldapSettings = Hash::insert($ldapSettings, 'fields_mapping.ad.user.id', 'uniqueUserPassword');
        $ldapSettings = Hash::insert($ldapSettings, 'fields_mapping.ad.user.created', 'ms-PKI-AccountCredentials');
        // Filters fields
        $ldapSettings['user_custom_filters'] = '(&(objectCategory=group)(cn=userPassword))';
        $ldapSettings['group_custom_filters'] = '(&(objectCategory=group)(cn=unixUserPassword))';
        // Object class fields
        $ldapSettings['user_object_class'] = 'userPassword';
        $ldapSettings['group_object_class'] = 'msPKI-CredentialRoamingTokens';

        $form = new LdapConfigurationForm();
        $result = $form->validate($ldapSettings);

        $this->assertTrue($result);
    }

    /**
     * To keep BC with old Bext.
     *
     * @return void
     */
    public function testDirectoryLdapConfigurationForm_Success_FieldFallbacksIsOptional(): void
    {
        $ldapSettings = self::getDummyFormData();
        unset($ldapSettings['field_fallbacks']);

        $form = new LdapConfigurationForm();
        $result = $form->validate($ldapSettings);

        $this->assertTrue($result);
    }

    /**
     * Data provider for testDirectoryLdapConfigurationForm_Error_FieldFallbacks()
     *
     * @return array
     */
    public function fieldFallbacksDataProvider(): array
    {
        return [
            [
                'value' => 1,
                'errorRule' => 'array',
            ],
            [
                'value' => '',
                'errorRule' => '_empty',
            ],
            [
                'value' => [],
                'errorRule' => '_empty',
            ],
            [
                'value' => ['ad' => ''], // invalid type, should be an array
                'errorRule' => 'ad.array',
            ],
            [
                'value' => ['ad' => ['username' => 'password']], // forbidden field for ad
                'errorRule' => 'ad.username.forbiddenField',
            ],
            [
                'value' => ['openldap' => ['username' => 'unixUserPassword']], // forbidden field for openldap
                'errorRule' => 'openldap.username.forbiddenField',
            ],
            [
                'value' => ['foo' => ['username' => '']], // invalid ldap type
                'errorRule' => 'invalidDirectoryType',
            ],
        ];
    }

    /**
     * @dataProvider fieldFallbacksDataProvider
     * @param mixed $value Value to set into the field.
     * @param string $errorRulePath Error rule path from errors array.
     * @return void
     */
    public function testDirectoryLdapConfigurationForm_Error_FieldFallbacks($value, string $errorRulePath): void
    {
        $ldapSettings = self::getDummyFormData();
        $ldapSettings['field_fallbacks'] = $value;

        $form = new LdapConfigurationForm();
        $result = $form->validate($ldapSettings);

        $this->assertFalse($result);
        $errors = $form->getErrors();
        $this->assertTrue(Hash::check($errors, "field_fallbacks.{$errorRulePath}"));
    }
}
