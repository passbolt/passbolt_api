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
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;
use Passbolt\License\Utility\LicenseKey;

class LicenseKeyNoDatabaseValidationTest extends TestCase
{
    protected $baseTestPath;

    protected $dbConfig;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->_setTestLicenseEnv();
    }

    /**
     * Set test license environment.
     */
    protected function _setTestLicenseEnv()
    {
        $this->loadPlugins(['Passbolt/License']);
        $this->baseTestPath = PLUGINS . 'Passbolt' . DS . 'License' . DS . 'tests';
        $licenseDevPublicKey = $this->baseTestPath . DS . 'data' . DS . 'gpg' . DS . 'license_dev_public.key';
        Configure::write('passbolt.plugins.license.licenseKey.public', $licenseDevPublicKey);
    }

    /**
     * Drop database config.
     */
    protected function _dropDatabaseConfig()
    {
        $this->dbConfig = ConnectionManager::get('test');
        ConnectionManager::drop('test');
    }

    /**
     * Reinstate database config.
     */
    protected function _reinstateDatabaseConfig()
    {
        ConnectionManager::setConfig('test', $this->dbConfig);
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

    /**
     * A "no database mode" validation should not break if the database is not present.
     */
    public function testLicenseValidateWithoutDatabaseSuccess()
    {
        $this->_dropDatabaseConfig();
        $licenseStr = $this->_getDummyLicense('license_dev');
        $license = new LicenseKey($licenseStr);

        $valid = false;
        try {
            $valid = $license->validate();
        } catch (\Exception $e) {
            $this->_reinstateDatabaseConfig();
            $this->fail('There should not be any exception thrown.');
        }

        $this->_reinstateDatabaseConfig();

        $this->assertTrue($valid);
    }
}
