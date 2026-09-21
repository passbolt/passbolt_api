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
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;

/**
 * @covers \Passbolt\OfflineMode\Controller\OfflineSettingsGetController
 */
class OfflineSettingsGetControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
        $this->mockUserAgent();
        $this->mockUserIp();
    }

    public function testOfflineSettingsGetController_Success_EmptyBodyWhenNoRow(): void
    {
        $this->logInAsUser();
        $this->getJson('/offline/settings.json');

        $this->assertResponseOk();
        $this->assertEmpty($this->getResponseBodyAsArray());
    }

    public function testOfflineSettingsGetController_Success_StoredValuesWithAuditFields(): void
    {
        $setting = OfflineModeSettingFactory::make()
            ->setField('value', ['max_session_duration' => 300, 'data_retention_period' => 7, 'max_items' => 1000])
            ->persist();

        $this->logInAsUser();
        $this->getJson('/offline/settings.json');

        $this->assertResponseOk();
        $body = $this->getResponseBodyAsArray();
        $this->assertSame($setting->get('id'), $body['id']);
        $this->assertSame(300, $body['max_session_duration']);
        $this->assertSame(7, $body['data_retention_period']);
        $this->assertSame(1000, $body['max_items']);
        $this->assertArrayHasKey('created', $body);
        $this->assertArrayHasKey('created_by', $body);
        $this->assertArrayHasKey('modified', $body);
        $this->assertArrayHasKey('modified_by', $body);
    }

    public function testOfflineSettingsGetController_Success_AdminSeesSameShape(): void
    {
        OfflineModeSettingFactory::make()
            ->setField('value', ['max_session_duration' => 300, 'data_retention_period' => 7, 'max_items' => 1000])
            ->persist();

        $this->logInAsAdmin();
        $this->getJson('/offline/settings.json');

        $this->assertResponseOk();
        $this->assertArrayHasKey('id', $this->getResponseBodyAsArray());
    }

    public function testOfflineSettingsGetController_Success_ServesDefaultValues(): void
    {
        OfflineModeSettingFactory::make()->default()->persist();

        $this->logInAsUser();
        $this->getJson('/offline/settings.json');

        $this->assertResponseOk();
        $body = $this->getResponseBodyAsArray();
        $this->assertSame(300, $body['max_session_duration']);
        $this->assertSame(7, $body['data_retention_period']);
        $this->assertSame(1000, $body['max_items']);
    }

    public function testOfflineSettingsGetController_Error_UnusableStoredValue(): void
    {
        OfflineModeSettingFactory::make()->setField('value', 'not-an-array')->persist();
        $this->logInAsUser();

        $this->getJson('/offline/settings.json');

        $this->assertBadRequestError('Could not validate offline settings data');
        $this->assertArrayHasKey('invalid', $this->getResponseBodyAsArray()['value']);
    }

    public function testOfflineSettingsGetController_Error_ValuesRefusedByThisEdition(): void
    {
        OfflineModeSettingFactory::make()
            ->setField('value', [
                'max_session_duration' => 600,
                'data_retention_period' => 14,
                'max_items' => 500,
            ])
            ->persist();
        $this->logInAsUser();

        $this->getJson('/offline/settings.json');

        $this->assertBadRequestError('Could not validate offline settings data');
        $this->assertArrayHasKey('default_only', $this->getResponseBodyAsArray()['max_session_duration']);
    }

    public function testOfflineSettingsGetController_Error_NotAuthenticated(): void
    {
        $this->getJson('/offline/settings.json');
        $this->assertAuthenticationError();
    }
}
