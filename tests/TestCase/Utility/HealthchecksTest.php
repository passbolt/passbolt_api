<?php
declare(strict_types=1);

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
use App\Utility\Filesystem\DirectoryUtility;
use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;

class HealthchecksTest extends AppIntegrationTestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        $this->disableFeaturePlugin('JwtAuthentication');
    }

    public function testHealthcheckApplication()
    {
        $check = Healthchecks::application();
        $attributes = [
            'schema', 'robotsIndexDisabled', 'sslForce', 'sslFullBaseUrl', 'seleniumDisabled',
            'registrationClosed', 'jsProd', 'emailNotificationEnabled', 'latestVersion',
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
        $check = Healthchecks::database('test');
        $attributes = ['connect', 'supportedBackend', 'tablesCount', 'defaultContent'];
        $this->assertArrayHasAttributes($attributes, $check['database']);
    }

    public function testHealthcheckEnvironment()
    {
        $check = Healthchecks::environment();
        $expectedCheck = [ 'environment' => [
            'phpVersion' => true,
            'pcre' => true,
            'mbstring' => true,
            'gnupg' => true,
            'intl' => true,
            'image' => true,
            'tmpWritable' => true,
            'logWritable' => true,
            //'allow_url_fopen' => true,
        ]];
        $this->assertSame($expectedCheck, $check);
    }

    public function testHealthcheckGpg()
    {
        $check = Healthchecks::gpg();
        $attributes = [
            'canDecrypt',
            'canDecryptVerify',
            'canEncrypt',
            'canEncryptSign',
            'canSign',
            'canVerify',
            'gpgHome',
            'gpgHomeWritable',
            'gpgKey',
            'gpgKeyNotDefault',
            'gpgKeyPrivate',
            'gpgKeyPrivateBlock',
            'gpgKeyPrivateFingerprint',
            'gpgKeyPrivateReadable',
            'gpgKeyPublic',
            'gpgKeyPublicBlock',
            'gpgKeyPublicEmail',
            'gpgKeyPublicFingerprint',
            'gpgKeyPublicInKeyring',
            'gpgKeyPublicReadable',
            'info',
            'isPrivateServerKeyGopengpgCompatible',
            'isPublicServerKeyGopengpgCompatible',
            'lib',
        ];
        $this->assertArrayHasExactAttributes($attributes, $check['gpg']);
    }

    public function testSsl()
    {
        $check = Healthchecks::ssl();
        $attributes = ['peerValid', 'hostValid', 'notSelfSigned'];
        $this->assertArrayHasAttributes($attributes, $check['ssl']);
    }

    public function testJwt()
    {
        DirectoryUtility::removeRecursively(JwtTokenCreateService::JWT_SECRET_KEY_PATH);
        DirectoryUtility::removeRecursively(JwksGetService::PUBLIC_KEY_PATH);
        Configure::delete('passbolt.plugins.jwtAuthentication.enabled');
        $check = Healthchecks::jwt();
        $attributes = ['isEnabled',];
        $this->assertArrayHasAttributes($attributes, $check['jwt']);

        (new JwtKeyPairService())->createKeyPair();
        $this->enableFeaturePlugin('JwtAuthentication');
        $check = Healthchecks::jwt();
        $attributes = ['isEnabled', 'keyPairValid',];
        $this->assertArrayHasAttributes($attributes, $check['jwt']);
    }
}
