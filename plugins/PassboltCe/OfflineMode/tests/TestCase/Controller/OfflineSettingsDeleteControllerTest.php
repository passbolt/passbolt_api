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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;

/**
 * @covers \Passbolt\OfflineMode\Controller\OfflineSettingsDeleteController
 */
class OfflineSettingsDeleteControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(OfflineModePlugin::class);

        $this->mockUserAgent();
        $this->mockUserIp();
    }

    public function testOfflineSettingsDeleteController_Success_DeletesRow(): void
    {
        $row = OfflineModeSettingFactory::make()
            ->setField('value', json_encode(['max_session_duration' => 300, 'data_retention_period' => 7]))
            ->persist();
        $this->logInAsAdmin();

        $id = $row->get('id');
        $this->deleteJson("/offline/settings/$id.json");

        $this->assertResponseOk();
        $this->assertSame(0, OfflineModeSettingFactory::find()->count());
    }

    public function testOfflineSettingsDeleteController_Error_BadRequest_InvalidUuid(): void
    {
        $this->logInAsAdmin();
        $this->deleteJson('/offline/settings/not-a-uuid.json');
        $this->assertBadRequestError('The identifier should be a valid UUID.');
    }

    public function testOfflineSettingsDeleteController_Error_NotFound_IdDoesNotExist(): void
    {
        $this->logInAsAdmin();

        $missingId = UuidFactory::uuid('does-not-exist');
        $this->deleteJson("/offline/settings/$missingId.json");

        $this->assertResponseCode(404);
    }

    public function testOfflineSettingsDeleteController_Error_NotAuthenticated(): void
    {
        $row = OfflineModeSettingFactory::make()
            ->setField('value', json_encode(['max_session_duration' => 300, 'data_retention_period' => 7]))
            ->persist();

        $id = $row->get('id');
        $this->deleteJson("/offline/settings/$id.json");

        $this->assertAuthenticationError();
    }

    public function testOfflineSettingsDeleteController_Error_NotAdmin(): void
    {
        $row = OfflineModeSettingFactory::make()
            ->setField('value', json_encode(['max_session_duration' => 300, 'data_retention_period' => 7]))
            ->persist();
        $this->logInAsUser();

        $id = $row->get('id');
        $this->deleteJson("/offline/settings/$id.json");

        $this->assertForbiddenError('Access restricted to administrators');
    }

    public function testOfflineSettingsDeleteController_Error_NotJson(): void
    {
        $row = OfflineModeSettingFactory::make()
            ->setField('value', json_encode(['max_session_duration' => 300, 'data_retention_period' => 7]))
            ->persist();
        $this->logInAsAdmin();

        $id = $row->get('id');
        // `.json` extension restriction enforced at route layer; no extension → 404.
        $this->delete("/offline/settings/$id");
        $this->assertResponseCode(404);
    }
}
