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
 * @since         2.6.0
 */

namespace Passbolt\DirectorySync\Test\TestCase\Controller;

use App\Model\Entity\Role;

use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Test\TestCase\Form\LdapConfigurationFormTest;
use Passbolt\DirectorySync\Test\TestCase\Utility\DirectoryOrgSettingsTest;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class DirectorySettingsControllerTest extends DirectorySyncIntegrationTestCase
{
    use UserAccessControlTrait;

    public $fixtures = [
        'app.Base/OrganizationSettings',
        'app.Base/AuthenticationTokens', 'app.Base/Users',
        'app.Base/Roles'
    ];

    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.directorySync.test', 'Nested');
        $this->disableDirectoryIntegration();
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_View
     */
    public function testDirectorySync_DirectorySettingsController_ViewEmpty_Success()
    {
        $this->disableDirectoryIntegration();
        $this->authenticateAs('admin');
        $this->getJson("/directorysync/settings.json?api-version=2");
        $this->assertSuccess();
        $settings = $this->_responseJsonBody;
        $this->assertEmpty($settings);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_View
     */
    public function testDirectorySync_DirectorySettingsController_View_Success()
    {
        // Populate the settings for the test
        $uac = $this->mockUserAccessControl('admin', Role::ADMIN);
        $settings = DirectoryOrgSettingsTest::getDummySettings();
        $directoryOrgSettings = new DirectoryOrgSettings($settings);
        $directoryOrgSettings->save($uac);

        $this->authenticateAs('admin');
        $this->getJson("/directorysync/settings.json?api-version=2");
        $this->assertSuccess();
        $settings = $this->_responseJsonBody;
        $this->assertNotEmpty($settings);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_View
     */
    public function testDirectorySync_DirectorySettingsController_View_AccessDenied()
    {
        $this->authenticateAs('dame');
        $this->getJson("/directorysync/settings.json?api-version=2");
        $this->assertError(403);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_Update
     */
    public function testDirectorySync_DirectorySettingsController_Update_Success()
    {
        $directoryOrgSettings = DirectoryOrgSettings::get();
        $this->assertFalse($directoryOrgSettings->isEnabled());

        $formData = LdapConfigurationFormTest::getDummyFormData();
        $this->authenticateAs('admin');
        $this->putJson("/directorysync/settings.json?api-version=2", $formData);
        $this->assertSuccess();

        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $settings = json_decode($OrganizationSettings->getFirstSettingOrFail(DirectoryOrgSettings::ORG_SETTINGS_PROPERTY)->value, true);
        $this->assertNotEmpty($settings);

        $directoryOrgSettings = DirectoryOrgSettings::get();
        $this->assertTrue($directoryOrgSettings->isEnabled());
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_Update
     */
    public function testDirectorySync_DirectorySettingsController_Update_Empty_Username_Password_Success()
    {
        $directoryOrgSettings = DirectoryOrgSettings::get();
        $this->assertFalse($directoryOrgSettings->isEnabled());

        $formData = LdapConfigurationFormTest::getDummyFormData();
        $formData['username'] = '';
        $formData['password'] = '';
        $this->authenticateAs('admin');
        $this->putJson("/directorysync/settings.json?api-version=2", $formData);
        $this->assertSuccess();

        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $settings = json_decode($OrganizationSettings->getFirstSettingOrFail(DirectoryOrgSettings::ORG_SETTINGS_PROPERTY)->value, true);
        $this->assertNotEmpty($settings);

        $directoryOrgSettings = DirectoryOrgSettings::get();
        $ldapSettings = $directoryOrgSettings->getLdapSettings();
        $this->assertFalse(isset($ldapSettings['domains']['org_domain']['username']));
        $this->assertFalse(isset($ldapSettings['domains']['org_domain']['password']));
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_Update
     */
    public function testDirectorySync_DirectorySettingsController_Update_InvalidSettings()
    {
        $formData = LdapConfigurationFormTest::getDummyFormData();
        $formData['domain_name'] = '';
        $this->authenticateAs('admin');
        $this->putJson("/directorysync/settings.json?api-version=2", $formData);
        $this->assertError(400);
        $errors = json_decode(json_encode($this->_responseJsonBody), true);
        $this->assertNotEmpty($errors);
        $error = Hash::get($errors, 'domain_name._empty');
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_Update
     */
    public function testDirectorySync_DirectorySettingsController_Update_AccessDenied()
    {
        $this->authenticateAs('dame');
        $this->putJson("/directorysync/settings.json?api-version=2", []);
        $this->assertError(403);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_Disable
     */
    public function testDirectorySync_DirectorySettingsController_Disable_Success()
    {
        $this->authenticateAs('admin');

        // Enable the directory integration
        $formData = LdapConfigurationFormTest::getDummyFormData();
        $this->putJson("/directorysync/settings.json?api-version=2", $formData);
        $this->assertSuccess();
        $directoryOrgSettings = DirectoryOrgSettings::get();
        $this->assertTrue($directoryOrgSettings->isEnabled());

        // Disable the directory integration
        $this->deleteJson("/directorysync/settings.json?api-version=2");
        $this->assertSuccess();
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $this->expectException(RecordNotFoundException::class);
        $settings = json_decode($OrganizationSettings->getFirstSettingOrFail(DirectoryOrgSettings::ORG_SETTINGS_PROPERTY)->value, true);
        $directoryOrgSettings = DirectoryOrgSettings::get();
        $this->assertFalse($directoryOrgSettings->isEnabled());
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_Disable
     */
    public function testDirectorySync_DirectorySettingsController_Disable_AccessDenied()
    {
        $this->authenticateAs('dame');
        $this->deleteJson("/directorysync/settings.json?api-version=2");
        $this->assertError(403);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_Test
     */
    public function testDirectorySync_DirectorySettingsController_Test_InvalidSettings()
    {
        $formData = LdapConfigurationFormTest::getDummyFormData();
        $formData['domain_name'] = '';
        $this->authenticateAs('admin');
        $this->postJson("/directorysync/settings/test.json?api-version=2", $formData);
        $this->assertError(400);
        $errors = json_decode(json_encode($this->_responseJsonBody), true);
        $this->assertNotEmpty($errors);
    }

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     * @group DirectorySyncController_DirectorySettingsController
     * @group DirectorySyncController_DirectorySettingsController_Test
     */
    public function testDirectorySync_DirectorySettingsController_Test_Success()
    {
        $directoryOrgSettings = DirectoryOrgSettings::get();
        $this->assertFalse($directoryOrgSettings->isEnabled());

        $formData = LdapConfigurationFormTest::getDummyFormData();
        $this->authenticateAs('admin');
        $this->postJson("/directorysync/settings/test.json?api-version=2", $formData);
        $this->assertSuccess();
        $this->assertTrue(isset($this->_responseJsonBody->users));
        $this->assertEquals(6, count($this->_responseJsonBody->users));
        $this->assertTrue(isset($this->_responseJsonBody->groups));
        $this->assertEquals(5, count($this->_responseJsonBody->groups));
        $this->assertTrue(isset($this->_responseJsonBody->tree));
        $this->assertEquals(1, count($this->_responseJsonBody->tree));
    }
}
