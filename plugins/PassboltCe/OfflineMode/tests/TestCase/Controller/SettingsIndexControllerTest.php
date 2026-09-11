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
 * @since         5.15.0
 */
namespace Passbolt\OfflineMode\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\OfflineMode\OfflineModePlugin;

class SettingsIndexControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
    }

    public function testSettingsIndexController_Success_OfflineModePluginIsInBetaByDefault(): void
    {
        $this->logInAsUser();
        $this->getJson('/settings.json');
        $this->assertTrue($this->_responseJsonBody->passbolt->plugins->offlineMode->enabled);
        $this->assertTrue($this->_responseJsonBody->passbolt->plugins->offlineMode->isInBeta);
    }

    public function testSettingsIndexController_Success_Guests(): void
    {
        $this->getJson('/settings.json');
        $this->assertSuccess();
        // 'enabled' is public; 'isInBeta' and 'version' is restricted to authenticated users.
        $this->assertTrue($this->_responseJsonBody->passbolt->plugins->offlineMode->enabled);
        $this->assertFalse(isset($this->_responseJsonBody->passbolt->plugins->offlineMode->isInBeta));
        $this->assertFalse(isset($this->_responseJsonBody->passbolt->plugins->offlineMode->version));
    }
}
