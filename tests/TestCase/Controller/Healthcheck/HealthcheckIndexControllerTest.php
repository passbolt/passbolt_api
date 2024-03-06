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
namespace App\Test\TestCase\Controller\Healthcheck;

use App\Controller\Healthcheck\HealthcheckIndexController;
use App\Model\Validation\EmailValidationRule;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use App\Utility\Healthchecks;
use App\Utility\Healthchecks\Healthcheck;
use Cake\Core\Configure;
use Cake\Http\Client;
use Passbolt\SmtpSettings\Middleware\SmtpSettingsSecurityMiddleware;

/**
 * @covers \App\Controller\Healthcheck\HealthcheckIndexController
 */
class HealthcheckIndexControllerTest extends AppIntegrationTestCase
{
    use HealthcheckRequestTestTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles',];

    public function testHealthcheckIndexController_Success_Html(): void
    {
        $this->mockService(Client::class, function () {
            return $this->getMockedHealthcheckStatusRequest();
        });
        $this->get('/healthcheck');
        $this->assertResponseContains('Passbolt API Status');
        $this->assertResponseOk();
    }

    /**
     * Strangely, the status returned is OK, although the healthcheck failed
     * Leaving the test as documentation
     */
    public function testHealthcheckIndexController_ErrorNotReachable_Html(): void
    {
        $this->mockService(Client::class, function () {
            return $this->getMockedHealthcheckStatusRequest(400);
        });

        $this->get('/healthcheck');

        $this->assertResponseContains('Passbolt API Status');
        $this->assertResponseOk();
    }

    public function testHealthcheckIndexController_Success_Json(): void
    {
        $this->mockService(Client::class, function () {
            return $this->getMockedHealthcheckStatusRequest();
        });

        $this->getJson('/healthcheck.json');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();
        $https = strpos(Configure::read('App.fullBaseUrl'), 'https') === 0;
        $expectedResponse = [
            'ssl' => [
                'peerValid' => true,
                'hostValid' => true,
                'notSelfSigned' => true,
                //'is' => false, // TODO: Currently not in response
            ],
            'database' => [
                'tablesCount' => true,
                'info' => ['tablesCount' => 32],
                'connect' => true,
                'supportedBackend' => true,
                'defaultContent' => true,
            ],
            'application' => [
                'info' => [
                    'remoteVersion' => 'undefined',
                    'currentVersion' => Configure::read('passbolt.version'),
                ],
                'latestVersion' => null,
                'schema' => true,
                'robotsIndexDisabled' => true,
                'sslForce' => false,
                'sslFullBaseUrl' => ($https !== false),
                'configPath' => CONFIG . 'passbolt.php',
                'seleniumDisabled' => !Configure::read('passbolt.selenium.active'),
                'registrationClosed' => [
                    'isSelfRegistrationPluginEnabled' => $this->isFeaturePluginEnabled('SelfRegistration'),
                    'selfRegistrationProvider' => null,
                    'isRegistrationPublicRemovedFromPassbolt' => is_null(Configure::read('passbolt.registration.public')),
                ],
                'hostAvailabilityCheckEnabled' => Configure::read(EmailValidationRule::MX_CHECK_KEY),
                'jsProd' => (Configure::read('passbolt.js.build') === 'production'),
                'emailNotificationEnabled' => false,
            ],
            'gpg' => [
                'canDecryptVerify' => true,
                'canVerify' => true,
                'gpgKeyPublicInKeyring' => true,
                'canEncrypt' => true,
                'canDecrypt' => true,
                'canEncryptSign' => true,
                'canSign' => true,
                'gpgHome' => true,
                'gpgKeyPrivateFingerprint' => true,
                'gpgKeyPublicFingerprint' => true,
                'gpgKeyPublicEmail' => true,
                'gpgKeyPublicReadable' => true,
                'gpgKeyPrivateReadable' => true,
                'gpgKey' => true,
                'lib' => true,
                'gpgKeyNotDefault' => false,
                'info' => [
                    'gpgHome' => '/root/.gnupg',
                    'gpgKeyPrivate' => Configure::read('passbolt.gpg.serverKey.private'),
                ],
                'gpgHomeWritable' => true,
                'gpgKeyPublic' => true,
                'gpgKeyPublicBlock' => true,
                'gpgKeyPrivate' => (Configure::read('passbolt.gpg.serverKey.private') !== null),
                'gpgKeyPrivateBlock' => true,
                'isPublicServerKeyGopengpgCompatible' => true,
                'isPrivateServerKeyGopengpgCompatible' => true,
            ],
            'environment' => [
                'phpVersion' => true,
                'nextMinPhpVersion' => version_compare(
                    PHP_VERSION,
                    Configure::read(Healthchecks::PHP_NEXT_MIN_VERSION_CONFIG),
                    '>='
                ),
                'info' => ['phpVersion' => PHP_VERSION],
                'pcre' => true,
                'mbstring' => true,
                'gnupg' => true,
                'intl' => true,
                'image' => true,
                'tmpWritable' => true,
                'logWritable' => true,
            ],
            'configFile' => [
                'app' => true,
                'passbolt' => (file_exists(CONFIG . 'passbolt.php')),
            ],
            'core' => [
                'cache' => true,
                'debugDisabled' => false,
                'salt' => true,
                'fullBaseUrl' => true,
                'validFullBaseUrl' => true,
                'info' => ['fullBaseUrl' => Configure::read('App.fullBaseUrl')],
                'fullBaseUrlReachable' => true,
            ],
            'smtpSettings' => [
                'isEnabled' => $this->isFeaturePluginEnabled('SmtpSettings'),
                'areEndpointsDisabled' => Configure::read(SmtpSettingsSecurityMiddleware::PASSBOLT_SECURITY_SMTP_SETTINGS_ENDPOINTS_DISABLED),
                'errorMessage' => false,
                'source' => 'env variables',
                'isInDb' => false,
            ],
        ];
        $this->assertArrayEqualsCanonicalizing($expectedResponse, $result);
    }

    /**
     * Throw a forbidden error if the endpoint is disabled
     */
    public function testHealthcheckIndexController_ErrorEndpointDisabled_Json(): void
    {
        Configure::write(
            HealthcheckIndexController::PASSBOLT_PLUGINS_HEALTHCHECK_SECURITY_INDEX_ENDPOINT_ENABLED,
            false
        );
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json');

        $this->assertForbiddenError('Healthcheck security index endpoint disabled.');
        // Assert body
        $resultBody = $this->_responseJsonBody;
        $this->assertSame('', $resultBody);
        // Assert headers
        $resultHeader = $this->_responseJsonHeader;
        $expectedHeaderAttributes = ['id', 'status', 'servertime', 'action', 'message', 'url', 'code'];
        $this->assertObjectHasAttributes($expectedHeaderAttributes, $resultHeader);
        $this->assertSame('error', $resultHeader->status);
        $this->assertSame('/healthcheck.json', $resultHeader->url);
    }
}
