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
 * @since         5.7.0
 */

namespace Passbolt\SecretRevisions\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;

class SettingsIndexControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
    }

    public function testSettingsIndexController_SecretRevisionsPlugin_Enabled_Logged_In(): void
    {
        $this->logInAsUser();
        $this->getJson('/settings.json');
        $this->assertTrue($this->_responseJsonBody->passbolt->plugins->secretRevisions->enabled);
        $this->assertTrue($this->_responseJsonBody->passbolt->plugins->secretRevisions->isInBeta);
    }

    public function testSettingsIndexController_SecretRevisionsPlugin_NotVisible_NotLoggedIn(): void
    {
        $this->getJson('/settings.json');
        $this->assertFalse(isset($this->_responseJsonBody->passbolt->plugins->secretRevisions));
    }
}
