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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Utility;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\Healthchecks;
use Cake\Core\Configure;

class HealthchecksTest extends AppIntegrationTestCase
{

    public function testHealthcheckApplication()
    {
        $check = Healthchecks::application();
        $attributes = [
            'schema', 'robotsIndexDisabled', 'sslForce', 'sslFullBaseUrl', 'seleniumDisabled',
            'registrationClosed', 'jsProd', 'emailNotificationEnabled', 'latestVersion'
        ];
        $this->assertArrayHasAttributes($attributes, $check['application']);
    }

    public function testHealthcheckAppUser()
    {
        $check = Healthchecks::appUser();
        $attributes = ['adminCount'];
        $this->assertArrayHasAttributes($attributes, $check['application']);
    }

    public function testHealthcheckConfigFiles()
    {
        $check = Healthchecks::configFiles();
        $attributes = ['app', 'passbolt'];
        $this->assertArrayHasAttributes($attributes, $check['configFile']);
    }

    public function testHealthcheckCore()
    {
        $check = Healthchecks::core();
        $attributes = ['cache', 'debugDisabled', 'salt', 'fullBaseUrl', 'validFullBaseUrl', 'fullBaseUrlReachable'];
        $this->assertArrayHasAttributes($attributes, $check['core']);
    }

    public function testDatabase()
    {
        $check = Healthchecks::database();
        $attributes = ['connect', 'supportedBackend', 'tablesCount', 'defaultContent'];
        $this->assertArrayHasAttributes($attributes, $check['database']);
    }

    public function testHealthcheckEnvironment()
    {
        $check = Healthchecks::environment();
        $attributes = ['phpVersion', 'pcre', 'tmpWritable', 'imgPublicWritable'];
        $this->assertArrayHasAttributes($attributes, $check['environment']);
    }

    public function testHealthcheckGpg()
    {
        $check = Healthchecks::gpg();
        $attributes = [
            'lib', 'gpgKey', 'gpgKeyNotDefault', 'gpgHome', 'gpgHomeWritable', 'gpgKeyPublic', 'gpgKeyPublicReadable',
            'gpgKeyPrivate', 'gpgKeyPrivateReadable', 'gpgKeyPrivateFingerprint', 'gpgKeyPublicFingerprint',
            'gpgKeyPublicEmail', 'gpgKeyPublicInKeyring', 'canEncrypt', 'canDecrypt'
        ];
        $this->assertArrayHasAttributes($attributes, $check['gpg']);
    }

    public function testSsl()
    {
        $check = Healthchecks::ssl();
        $attributes = ['peerValid', 'hostValid', 'notSelfSigned'];
        $this->assertArrayHasAttributes($attributes, $check['ssl']);
    }
}
