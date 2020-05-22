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

namespace Passbolt\License\Test\TestCase\Form;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use Passbolt\License\Form\LicenseKeyForm;

class LicenseKeyFormTest extends TestCase
{
    protected $baseTestPath;

    protected $_licenseKeyForm;

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
        $this->_licenseKeyForm = new LicenseKeyForm();
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

    public function testLicenseKeyParseSuccess()
    {
        $licenseStr = $this->_getDummyLicense('license_dev');
        $this->_licenseKeyForm->setData(['key_ascii' => $licenseStr]);
        try {
            $licenseInfo = $this->_licenseKeyForm->parse();
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

    public function testLicenseKeyValidate_ErrorInvalidFormat()
    {
        $licensesStr = [
            'empty license' => '',
            'not even a gpg message' => '---- invalid format ----',
            'corrupted gpg message' => 'sqSQSQsqsqqSQsqSQsqssqsqSQsq',
        ];

        foreach ($licensesStr as $licenseStr) {
            $data = ['key_ascii' => $licenseStr];
            $result = $this->_licenseKeyForm->execute($data);

            $this->assertFalse($result);
        }
    }

    public function testLicenseKeyErrorGetInfo_InvalidLicenseIssuer()
    {
        $licenseStr = $this->_getDummyLicense('license_issuer_ada');
        $data = ['key_ascii' => $licenseStr];
        $result = $this->_licenseKeyForm->execute($data);
        $errors = $this->_licenseKeyForm->getErrors();

        $this->assertFalse($result);
        $this->assertTrue(isset($errors['key_ascii']));
        $this->assertNotEmpty(Hash::get($errors, 'key_ascii.is_valid_license'));
        $this->assertEquals(Hash::get($errors, 'key_ascii.is_valid_license'), 'The license content or signature is not valid.');
    }

    public function testLicenseKeyValidate_Success()
    {
        $licenseStr = $this->_getDummyLicense('license_dev');

        $data = ['key_ascii' => $licenseStr];
        $result = $this->_licenseKeyForm->execute($data);

        $this->assertTrue($result);
    }
}
