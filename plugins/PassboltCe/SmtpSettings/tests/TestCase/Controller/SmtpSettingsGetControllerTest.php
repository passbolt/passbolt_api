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
 * @since         3.8.0
 */

namespace Passbolt\SmtpSettings\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Utility\Text;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetService;
use Passbolt\SmtpSettings\SmtpSettingsPlugin;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

class SmtpSettingsGetControllerTest extends AppIntegrationTestCase
{
    use SmtpSettingsTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SmtpSettingsPlugin::class);
    }

    public function testSmtpSettingsGetController_Success()
    {
        $this->logInAsAdmin();
        $data = $this->getSmtpSettingsData();
        $savedSettings = $this->encryptAndPersistSmtpSettings($data);

        $this->getJson('/smtp/settings.json');
        $this->assertSuccess();
        $retrievedData = (array)$this->_responseJsonBody;
        $expectedData = array_merge(
            ['id' => $savedSettings->id,],
            $data,
            [
                'created' => $savedSettings->created->toAtomString(),
                'modified' => $savedSettings->modified->toAtomString(),
                'created_by' => $savedSettings->created_by,
                'modified_by' => $savedSettings->modified_by,
            ],
            ['source' => 'db']
        );

        $this->assertSame($expectedData, $retrievedData);
    }

    public function testSmtpSettingsGetController_Success_EmptyUsernamePassword()
    {
        $this->reconfigureTransportFactory([
            'host' => 'test',
            'username' => '',
            'password' => '',
        ]);

        $this->logInAsAdmin();
        $this->getJson('/smtp/settings.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertSame(SmtpSettingsGetService::SMTP_SETTINGS_SOURCE_ENV, $response['source']);
        $this->assertSame('test', $response['host']);
        $this->assertNull($response['username']);
        $this->assertNull($response['password']);
    }

    public function testSmtpSettingsGetController_Validation_Failure()
    {
        $this->logInAsAdmin();
        $data = $this->getSmtpSettingsData('port', 0);
        $this->encryptAndPersistSmtpSettings($data);

        $this->getJson('/smtp/settings.json');
        $this->assertResponseError('Could not validate the smtp settings.');
        $errorMessageInBody = $this->_responseJsonBody->port->range;

        $this->assertSame($errorMessageInBody, 'The port number should be between 1 and 65535.');
    }

    public function testSmtpSettingsGetController_Not_Admin_Should_Have_No_Access()
    {
        $this->logInAsUser();
        $this->getJson('/smtp/settings.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testSmtpSettingsGetController_Guest_Should_Have_No_Access()
    {
        $this->getJson('/smtp/settings.json');
        $this->assertAuthenticationError();
    }

    public function testSmtpSettingsGetController_Should_Be_Forbidden_If_Security_Enabled()
    {
        $this->disableSmtpSettingsEndpoints();

        $this->getJson('/smtp/settings.json');
        $this->assertForbiddenError('SMTP settings endpoints disabled.');

        $this->enableSmtpSettingsEndpoints();
    }

    public function testSmtpSettingsGetController_Success_WithOauth2Settings(): void
    {
        $this->logInAsAdmin();
        $data = $this->getSmtpSettingsData();
        $data['tenant_id'] = Text::uuid();
        $data['client_id'] = Text::uuid();
        $data['client_secret'] = 'my-secret';
        $data['oauth_username'] = 'user@example.com';
        $this->encryptAndPersistSmtpSettings($data);

        $this->getJson('/smtp/settings.json');
        $this->assertSuccess();
        $response = (array)$this->_responseJsonBody;
        $this->assertSame($data['tenant_id'], $response['tenant_id']);
        $this->assertSame($data['client_id'], $response['client_id']);
        $this->assertSame($data['client_secret'], $response['client_secret']);
        $this->assertSame($data['oauth_username'], $response['oauth_username']);
    }

    public function testSmtpSettingsGetController_BackwardCompat_LegacySettingsNoOauth2(): void
    {
        $this->logInAsAdmin();
        $data = $this->getSmtpSettingsData();
        // No OAuth2 fields - legacy settings
        $this->encryptAndPersistSmtpSettings($data);

        $this->getJson('/smtp/settings.json');
        $this->assertSuccess();
        $response = (array)$this->_responseJsonBody;
        // OAuth2 fields should be null or absent
        $this->assertTrue(
            !isset($response['tenant_id']) || $response['tenant_id'] === null,
            'tenant_id should be absent or null for legacy settings'
        );
    }
}
