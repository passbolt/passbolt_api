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
}
