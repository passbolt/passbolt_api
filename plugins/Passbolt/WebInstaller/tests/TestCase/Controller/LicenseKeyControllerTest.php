<?php
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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Controller;

use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class LicenseKeyControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    protected function mockLicenseIssuerKey()
    {
        Configure::load('Passbolt/License.config', 'default', true);
        $licenseDevPublicKey = PLUGINS . DS . 'Passbolt' . DS . 'License' . DS . 'tests' . DS . 'data' . DS . 'gpg' . DS . 'license_dev_public.key';
        Configure::write('passbolt.plugins.license.licenseKey.public', $licenseDevPublicKey);
    }

    protected function checkPluginLicenseExists()
    {
        return file_exists(PLUGINS . DS . 'Passbolt' . DS . 'License');
    }

    public function testWebInstallerLicenseKeyViewSuccess()
    {
        $this->get('/install/license_key');
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('Passbolt Pro activation.', $data);
    }

    public function testWebInstallerLicenseKeyPostSuccess()
    {
        if ($this->checkPluginLicenseExists()) {
            $this->mockLicenseIssuerKey();
            $postData = [
                'license_key' => file_get_contents(PLUGINS . DS . 'Passbolt' . DS . 'License' . DS . 'tests' . DS . 'data' . DS . 'license' . DS . 'license_dev')
            ];
            $this->post('/install/license_key', $postData);
            $this->assertResponseCode(302);
            $this->assertRedirectContains('install/database');
            $this->assertSession($postData, 'webinstaller.license');
        }
        $this->assertTrue(true);
    }

    public function testWebInstallerLicenseKeyPostError_InvalidData()
    {
        if ($this->checkPluginLicenseExists()) {
            $this->mockLicenseIssuerKey();
            $postData = [
                'license_key' => 'invalid-format'
            ];
            $this->post('/install/license_key', $postData);
            $data = ($this->_getBodyAsString());
            $this->assertResponseOk();
            $this->assertContains('The license is not valid', $data);
            $this->assertContains('The license format is not valid', $data);
        }
        $this->assertTrue(true);
    }

    public function testWebInstallerLicenseKeyPostError_LicenseExpired()
    {
        if ($this->checkPluginLicenseExists()) {
            $this->mockLicenseIssuerKey();
            $postData = [
                'license_key' => file_get_contents(PLUGINS . DS . 'Passbolt' . DS . 'License' . DS . 'tests' . DS . 'data' . DS . 'license' . DS . 'license_expired')
            ];
            $this->post('/install/license_key', $postData);
            $data = ($this->_getBodyAsString());
            $this->assertResponseOk();
            $this->assertContains('The license is not valid', $data);
            $this->assertContains('The license is expired', $data);
        }
        $this->assertTrue(true);
    }
}
