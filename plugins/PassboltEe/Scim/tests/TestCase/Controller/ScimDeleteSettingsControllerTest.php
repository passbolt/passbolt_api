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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller;

use App\Model\Entity\OrganizationSetting;
use App\Utility\UuidFactory;
use Passbolt\Scim\ScimPlugin;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;
use Passbolt\Scim\Test\Utility\ScimSettingsIntegrationTestCase;
use Throwable;

/**
 * @covers \Passbolt\Scim\Controller\ScimDeleteSettingsController
 */
class ScimDeleteSettingsControllerTest extends ScimSettingsIntegrationTestCase
{
    protected OrganizationSetting $current;

    public function setUp(): void
    {
        parent::setUp();
        $this->current = ScimSettingFactory::make()->default()->persist();
    }

    /**
     * Test deleteSettings method: plugin disabled
     *
     * @return void
     */
    public function testDeleteSettings_Error_PluginDisabled(): void
    {
        $this->disableFeaturePlugin(ScimPlugin::class);

        try {
            $this->deleteJson("/scim/settings/{$this->current->id}.json");
        } catch (Throwable $t) {
        }

        $this->assertResponseCode(404);
    }

    /**
     * Test deleteSettings method: guest forbidden
     *
     * @return void
     */
    public function testDeleteSettings_Error_GuestForbidden(): void
    {
        $this->logInAsUser();

        $this->deleteJson("/scim/settings/{$this->current->id}.json");

        $this->assertResponseCode(403);
    }

    /**
     * Test deleteSettings method: unauthenticated
     *
     * @return void
     */
    public function testDeleteSettings_Error_Unauthenticated()
    {
        $this->deleteJson("/scim/settings/{$this->current->id}.json");

        $this->assertResponseCode(401);
    }

    /**
     * Test deleteSettings method: not a json request
     *
     * @return void
     */
    public function testDeleteSettings_Error_NotJson()
    {
        $this->logInAsAdmin();
        $this->delete("/scim/settings/{$this->current->id}");
        $this->assertResponseCode(404);
    }

    /**
     * Test deleteSettings method: wrong UUID
     *
     * @return void
     */
    public function testDeleteSettings_WrongUUID()
    {
        $this->logInAsAdmin();

        $wrongUuid = UuidFactory::uuid();

        $this->deleteJson("/scim/settings/{$wrongUuid}.json");

        $this->assertResponseCode(404);
    }

    /**
     * Test deleteSettings method: success
     *
     * @return void
     */
    public function testDeleteSettings_Success()
    {
        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $scimSettingsTable */
        $scimSettingsTable = $this->fetchTable('Passbolt/Scim.ScimSettings');
        $existingOrganizationSetting = $scimSettingsTable->find()->first();
        $this->assertNotNull($existingOrganizationSetting);

        $this->logInAsAdmin();
        $this->deleteJson("/scim/settings/{$this->current->id}.json");

        $this->assertSuccess();
        $existingOrganizationSetting = $scimSettingsTable->find()->first();
        $this->assertNull($existingOrganizationSetting);
    }
}
