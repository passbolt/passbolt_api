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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Passbolt\Metadata\MetadataPlugin;

class SettingsIndexControllerTest extends AppIntegrationTestCaseV5
{
    public function testSettingsIndexController_MetadataPlugin_Enabled_Logged_In(): void
    {
        // Enable the v5 flag so the metadata plugin gets loaded in the Bootstrapper
        Configure::write('passbolt.v5.enabled', true);
        // Enable the plugin to the SettingsController knows about it and displays it in the response
        $this->enableFeaturePlugin(MetadataPlugin::class);
        $this->logInAsUser();
        $this->getJson('/settings.json');
        $this->assertTrue($this->_responseJsonBody->passbolt->plugins->metadata->enabled);
        $this->assertSame('1.0.0', $this->_responseJsonBody->passbolt->plugins->metadata->version);
    }

    public function testSettingsIndexController_MetadataPlugin_Enabled_Not_Logged_In(): void
    {
        // Enable the v5 flag so the metadata plugin gets loaded in the Bootstrapper
        Configure::write('passbolt.v5.enabled', true);
        // Enable the plugin to the SettingsController knows about it and displays it in the response
        $this->enableFeaturePlugin(MetadataPlugin::class);
        $this->getJson('/settings.json');
        $this->assertFalse(isset($this->_responseJsonBody->passbolt->plugins->metadata));
    }

    public function testSettingsIndexController_MetadataPlugin_Not_Enabled_Logged_In(): void
    {
        // Disable the v5 flag
        Configure::write('passbolt.v5.enabled', false);
        $this->logInAsUser();
        $this->getJson('/settings.json');
        $this->assertFalse(isset($this->_responseJsonBody->passbolt->plugins->metadata));
    }
}
