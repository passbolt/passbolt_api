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
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

class SmtpSettingsPostControllerTest extends AppIntegrationTestCase
{
    use SmtpSettingsTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SmtpSettingsPlugin::class);
    }

    public function testSmtpSettingsPostController_Success()
    {
        $this->gpgSetup();
        $this->logInAsAdmin();

        $data = $this->getSmtpSettingsData();
        $this->postJson('/smtp/settings.json', $data);
        $this->assertSuccess();
        $this->assertSame(1, SmtpSettingFactory::count());

        /** @var \App\Model\Entity\OrganizationSetting $savedSettings */
        $savedSettings = SmtpSettingFactory::find()->firstOrFail();

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
        $expectedData['tls'] = true;

        $this->assertSame($expectedData, $retrievedData);
    }

    public function testSmtpSettingsPostController_Invalid()
    {
        $this->gpgSetup();
        $this->logInAsAdmin();

        $data = $this->getSmtpSettingsData('sender_email', 'foo');
        $this->postJson('/smtp/settings.json', $data);
        $this->assertBadRequestError('Could not validate the smtp settings.');
        $this->assertSame(
            'The sender email should be a valid email address.',
            $this->_responseJsonBody->sender_email->email
        );

        $this->assertSame(0, SmtpSettingFactory::count());
    }

    public function testSmtpSettingsPostController_Not_Admin_Should_Have_No_Access()
    {
        $this->logInAsUser();
        $this->postJson('/smtp/settings.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testSmtpSettingsPostController_Guest_Should_Have_No_Access()
    {
        $this->postJson('/smtp/settings.json');
        $this->assertAuthenticationError();
    }

    public function testSmtpSettingsPostController_Should_Be_Forbidden_If_Security_Enabled()
    {
        $this->disableSmtpSettingsEndpoints();

        $this->postJson('/smtp/settings.json');
        $this->assertForbiddenError('SMTP settings endpoints disabled.');

        $this->enableSmtpSettingsEndpoints();
    }
}
