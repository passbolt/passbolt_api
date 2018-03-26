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
use Passbolt\License\Test\Lib\LicenseTestCase;
use Passbolt\License\Utility\License;

class LicenseTest extends LicenseTestCase
{

    public function testSuccessGetInfo()
    {
        $licenseStr = $this->_getDummyLicense('license_dev');
        $license = new License($licenseStr);
        try {
            $licenseInfo = $license->getInfo();
        } catch (\Exception $e) {
            return $this->fail('The license does not validate: ' . $e->getMessage());
        }

        $this->assertEquals(UuidFactory::uuid('license.id.passbolt-dev'), $licenseInfo['id']);
        $this->assertEquals(UuidFactory::uuid('customer.id.passbolt-dev'), $licenseInfo['customer_id']);
        $this->assertEquals(35, $licenseInfo['users']);
        $this->assertEquals(UuidFactory::uuid('plan.id.passbolt-dev'), $licenseInfo['plan_id']);
        $this->assertEquals('2019-03-25', $licenseInfo['expiry']);
        $this->assertEquals('2018-03-26', $licenseInfo['created']);
    }

    public function testErrorGetInfo_InvalidFormat()
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
                $this->assertEquals('The license format is not valid.', $e->getMessage());
            }
        }
    }

    public function testErrorGetInfo_InvalidLicenseIssuer()
    {
        $licenseStr = $this->_getDummyLicense('license_issuer_ada');
        $license = new License($licenseStr);
        try {
            $license->getInfo();
        } catch (\Exception $e) {
            $this->assertEquals('The license cannot be verified.', $e->getMessage());
        }
    }

    public function testSuccessValidate()
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
