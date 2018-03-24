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
namespace Passbolt\License\Test\Lib;

use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;

abstract class LicenseTestCase extends AppTestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Configure::load('Passbolt/License.config', 'default', true);
        $licenseDevPublicKey = __DIR__ . DS . '..' . DS . 'data' . DS . 'gpg' . DS . 'license_dev_public.key';
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
        $testDataPath = __DIR__ . DS . '..' . DS . 'data' . DS . 'license' . DS;

        return file_get_contents($testDataPath . $scenario);
    }
}
