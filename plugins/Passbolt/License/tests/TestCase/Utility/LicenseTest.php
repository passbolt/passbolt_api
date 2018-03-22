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

use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\License\Utility\License;

class LicenseTest extends AppTestCase
{

    public function setUp()
    {
        $licenseDevPublicKey = __DIR__ . DS . '..' . DS . '..' . DS . 'data' . DS . 'gpg' . DS . 'license_dev_public.key';
        Configure::write('passbolt.plugins.license.licenseKey.public', $licenseDevPublicKey);
        parent::setUp();
    }

    protected function _getDummyLicense(string $scenario = '')
    {
        $testDataPath = __DIR__ . '/../../data/license/';

        return file_get_contents($testDataPath . $scenario);
    }

    public function testSuccessValidate()
    {
        $licenseStr = $this->_getDummyLicense('license_dev');
        $license = new License($licenseStr);
        try {
            $license->validate();
        } catch (\Exception $e) {
            $this->fail('The license does not validate');
        }

        return $this->assertTrue(true);
    }

    public function testErrorValidate_InvalidFormat()
    {
        $licensesStr = [
            'empty license' => '',
            'not even a gpg message' => '---- invalid format ----',
            'corrupted gpg message' => '',
        ];

        foreach ($licensesStr as $licenseStr) {
            $license = new License($licenseStr);
            try {
                $license->validate();
            } catch (\Exception $e) {
                $this->assertEquals('The license format is not valid.', $e->getMessage());
            }
        }
    }

    public function testErrorValidate_InvalidLicenseIssuer()
    {
        $licenseStr = $this->_getDummyLicense('license_issuer_ada');
        $license = new License($licenseStr);
        try {
            $license->validate();
        } catch (\Exception $e) {
            $this->assertEquals('The license cannot be verified.', $e->getMessage());
        }
    }

    public function testErrorValidate_ExpiredLicense()
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
