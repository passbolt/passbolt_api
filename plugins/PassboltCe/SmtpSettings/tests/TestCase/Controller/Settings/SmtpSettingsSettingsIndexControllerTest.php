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

namespace Passbolt\SmtpSettings\Test\TestCase\Controller\Settings;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\SmtpSettings\SmtpSettingsPlugin;

class SmtpSettingsSettingsIndexControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SmtpSettingsPlugin::class);
    }

    public function testSmtpSettingsSettingsIndexControllerTest()
    {
        $url = '/settings.json?api-version=2';
        $this->getJson($url);
        $this->assertSuccess();
        // Assert that the plugin setting is not visible to non-logged users
        $this->assertFalse(isset($this->_responseJsonBody->passbolt->plugins->smtpSettings));

        $this->logInAsUser();
        $this->getJson($url);
        $this->assertSuccess();

        // Assert that the plugin is enabled by default
        $this->assertTrue(isset($this->_responseJsonBody->passbolt->plugins->smtpSettings->enabled));
    }
}
