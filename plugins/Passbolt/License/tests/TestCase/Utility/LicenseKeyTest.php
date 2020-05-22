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

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use Passbolt\License\Utility\LicenseKey;

class LicenseKeyTest extends TestCase
{
    protected $baseTestPath;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Favorites',
        'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Resources',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags',
    ];

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
        $license = new LicenseKey($licenseStr);
        try {
            $licenseInfo = $license->getData();
        } catch (\Exception $e) {
            return $this->fail('The license does not validate: ' . $e->getMessage());
        }

        $this->assertEquals('93cab7f6-c368-5e66-a702-571003344971', $licenseInfo['id']);
        $this->assertEquals('fdeded4c-d916-5d57-aab3-19573a20b04b', $licenseInfo['customer_id']);
        $this->assertEquals(35, $licenseInfo['users']);
        $this->assertEquals('470ce472-5063-5c7c-8f31-05470d147c8c', $licenseInfo['plan_id']);
        $this->assertEquals('2025-06-01', $licenseInfo['expiry']);
        $this->assertEquals('2018-03-26', $licenseInfo['created']);
    }

    public function testLicenseErrorGetInfo_InvalidFormat()
    {
        $licensesStr = [
            'empty license' => '',
            'not even a gpg message' => '---- invalid format ----',
            'corrupted gpg message' => '',
        ];

        foreach ($licensesStr as $licenseStr) {
            $license = new LicenseKey($licenseStr);
            try {
                $license->getData();
            } catch (\Exception $e) {
                $this->assertContains('The license format is not valid.', $e->getMessage());
            }
        }
    }

    public function testLicenseErrorGetInfo_InvalidLicenseIssuer()
    {
        $licenseStr = $this->_getDummyLicense('license_issuer_ada');
        $license = new LicenseKey($licenseStr);
        try {
            $license->getData();
        } catch (\Exception $e) {
            $this->assertContains('The license cannot be verified.', $e->getMessage());
        }
    }

    public function testLicenseSuccessValidate()
    {
        $licenseStr = $this->_getDummyLicense('license_dev');
        $license = new LicenseKey($licenseStr);

        $valid = $license->validate();

        $this->assertTrue($valid);
    }

    public function testLicenseErrorValidate_ExpiredLicense()
    {
        $licenseStr = $this->_getDummyLicense('license_expired');
        $license = new LicenseKey($licenseStr);
        $valid = $license->validate();
        $this->assertFalse($valid);
        $errors = $license->getErrors();

        $this->assertEquals('The license is expired.', Hash::get($errors, 'expiry.is_not_expired'));
    }
}
