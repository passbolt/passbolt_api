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
namespace App\Test\TestCase\Utility;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\Healthchecks;

class HealthchecksTest extends AppIntegrationTestCase
{

    function testHealthcheckApplication()
    {
        $check = Healthchecks::application();
        $attributes = [
            'schema', 'robotsIndexDisabled', 'sslForce', 'sslFullBaseUrl', 'seleniumDisabled',
            'registrationClosed', 'jsProd', 'emailNotificationEnabled', 'latestVersion'
        ];
        $this->assertArrayHasAttributes($attributes, $check['application']);
    }

    function testHealthcheckAppUser()
    {
        $check = Healthchecks::appUser();
        $attributes = ['adminCount'];
        $this->assertArrayHasAttributes($attributes, $check['application']);
    }

    function testHealthcheckConfigFiles()
    {
        $check = Healthchecks::configFiles();
        $attributes = ['app', 'passbolt'];
        $this->assertArrayHasAttributes($attributes, $check['configFile']);
    }

    function testHealthcheckCore()
    {
        $check = Healthchecks::core();
        $attributes = ['cache', 'debugDisabled', 'salt', 'fullBaseUrl', 'validFullBaseUrl', 'fullBaseUrlReachable'];
        $this->assertArrayHasAttributes($attributes, $check['core']);
    }

    function testDatabase()
    {
        $check = Healthchecks::database();
        $attributes = ['connect', 'supportedBackend', 'tablesCount', 'defaultContent'];
        $this->assertArrayHasAttributes($attributes, $check['database']);
    }

    function testHealthcheckEnvironment()
    {
        $check = Healthchecks::environment();
        $attributes = ['phpVersion', 'pcre', 'tmpWritable', 'imgPublicWritable'];
        $this->assertArrayHasAttributes($attributes, $check['environment']);
    }

    function testHealthcheckGpg()
    {
        $check = Healthchecks::gpg();
        $attributes = [
            'lib', 'gpgKey', 'gpgKeyNotDefault', 'gpgHome', 'gpgHomeWritable', 'gpgKeyPublic', 'gpgKeyPublicReadable',
            'gpgKeyPrivate', 'gpgKeyPrivateReadable', 'gpgKeyPrivateFingerprint', 'gpgKeyPublicFingerprint',
            'gpgKeyPublicEmail', 'gpgKeyPrivateInKeyring', 'canEncrypt', 'canDecrypt'
        ];
        $this->assertArrayHasAttributes($attributes, $check['gpg']);
    }

    function testSsl()
    {
        $check = Healthchecks::ssl();
        $attributes = ['peerValid', 'hostValid', 'notSelfSigned'];
        $this->assertArrayHasAttributes($attributes, $check['ssl']);
    }
}
