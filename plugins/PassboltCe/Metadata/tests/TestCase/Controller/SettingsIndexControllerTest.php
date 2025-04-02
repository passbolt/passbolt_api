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

class SettingsIndexControllerTest extends AppIntegrationTestCaseV5
{
    public function testSettingsIndexController_MetadataPlugin_Enabled_Logged_In(): void
    {
        $this->logInAsUser();
        $this->getJson('/settings.json');
        $this->assertTrue($this->_responseJsonBody->passbolt->plugins->metadata->enabled);
        $this->assertSame('1.0.0', $this->_responseJsonBody->passbolt->plugins->metadata->version);
    }

    public function testSettingsIndexController_MetadataPlugin_Enabled_Not_Logged_In(): void
    {
        $this->getJson('/settings.json');
        $this->assertFalse(isset($this->_responseJsonBody->passbolt->plugins->metadata));
    }

    public function testSettingsIndexController_MetadataPlugin_Not_Enabled_Logged_In(): void
    {
        // Disable the v5 flag
        Configure::write('passbolt.v5.enabled', false);
        $this->logInAsUser();
        $this->getJson('/settings.json');
        $this->assertFalse($this->_responseJsonBody->passbolt->plugins->metadata->enabled);
    }

    public function testSettingsIndexController_v5_Flag_Disabled_MetadataPlugin_Enabled(): void
    {
        // Disable the v5 flag
        Configure::write('passbolt.v5.enabled', false);
        // The metadata enabled flag should be overwritten by 'passbolt.v5.enabled'
        Configure::write('passbolt.plugins.metadata.enabled', true);
        $this->logInAsUser();
        $this->getJson('/settings.json');
        $this->assertFalse($this->_responseJsonBody->passbolt->plugins->metadata->enabled);
    }
}
