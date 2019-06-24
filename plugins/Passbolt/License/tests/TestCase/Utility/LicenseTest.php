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
 * @since         2.0.0
 */

namespace Passbolt\License\Test\TestCase\Utility;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\License\Utility\License;

class LicenseTest extends TestCase
{
    protected $baseTestPath;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/License']);
        $this->baseTestPath = PLUGINS . 'Passbolt' . DS . 'License' . DS . 'tests';
        $licenseDevPublicKey = $this->baseTestPath . DS . 'data' . DS . 'gpg' . DS . 'license_dev_public.key';
        Configure::write('passbolt.plugins.license.licenseKey.public', $licenseDevPublicKey);
    }

    /**
     * Get a dummy license file.
     * See tests/data/license
     *
     * @param string $scenario
     * @return string
     */
    protected function _getDummyLicense(string $scenario = '')
    {
        $testDataPath = $this->baseTestPath . DS . 'data' . DS . 'license' . DS;

        return file_get_contents($testDataPath . $scenario);
    }

    public function testLicenseSuccessGetInfo()
    {
        $licenseStr = $this->_getDummyLicense('license_dev');
        $license = new License($licenseStr);
        try {
            $licenseInfo = $license->getInfo();
        } catch (\Exception $e) {
            return $this->fail('The license does not validate: ' . $e->getMessage());
        }

        $this->assertEquals('93c6987e-084f-4c22-b6f1-56dd8b2989b4', $licenseInfo['id']);
        $this->assertEquals('a175f567-1474-4580-be8f-fb21afb031ea', $licenseInfo['customer_id']);
        $this->assertEquals(35, $licenseInfo['users']);
        $this->assertEquals('1161946b-b300-5119-9409-7b9246a3a5ab', $licenseInfo['plan_id']);
        $this->assertEquals('2020-03-26T00:00:00+00:00', $licenseInfo['expiry']);
        $this->assertEquals('2019-03-27T00:00:00+00:00', $licenseInfo['created']);
    }

    public function testLicenseErrorGetInfo_InvalidFormat()
    {
        $licensesStr = [
            'empty license' => '',
            'not even a gpg message' => '---- invalid format ----',
            'corrupted gpg message' => '',
        ];

        foreach ($licensesStr as $licenseStr) {
            $license = new License($licenseStr);
            try {
                $license->getInfo();
            } catch (\Exception $e) {
                $this->assertContains('The license format is not valid.', $e->getMessage());
            }
        }
    }

    public function testLicenseErrorGetInfo_InvalidLicenseIssuer()
    {
        $licenseStr = $this->_getDummyLicense('license_issuer_ada');
        $license = new License($licenseStr);
        try {
            $license->getInfo();
        } catch (\Exception $e) {
            $this->assertContains('The license cannot be verified.', $e->getMessage());
        }
    }

    public function testLicenseSuccessValidate()
    {
        $licenseStr = $this->_getDummyLicense('license_dev');
        $license = new License($licenseStr);
        try {
            $license->validate();
        } catch (\Exception $e) {
            $this->assertTrue(false);
        }
        $this->assertTrue(true);
    }

    public function testLicenseErrorValidate_ExpiredLicense()
    {
        $licenseStr = $this->_getDummyLicense('license_expired');
        $license = new License($licenseStr);
        try {
            $license->validate();
        } catch (\Exception $e) {
            $this->assertEquals('The license is expired.', $e->getMessage());
        }
    }
}
