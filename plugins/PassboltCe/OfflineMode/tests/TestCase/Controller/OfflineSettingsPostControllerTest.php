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
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;
use Passbolt\OfflineMode\Notification\Email\OfflineSettingsSetEmailRedactor;
use Passbolt\OfflineMode\OfflineModePlugin;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;

/**
 * @uses \Passbolt\OfflineMode\Controller\OfflineSettingsPostController
 */
class OfflineSettingsPostControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(OfflineModePlugin::class);
        $this->mockUserAgent();
        $this->mockUserIp();
    }

    public function testOfflineSettingsPostController_Success_CreatesRow(): void
    {
        $this->logInAsAdmin();

        $this->postJson('/offline/settings.json', [
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);

        $this->assertResponseOk();
        $row = OfflineModeSettingFactory::find()->firstOrFail();
        $this->assertArrayEqualsCanonicalizing(
            [
                'id' => $row->get('id'),
                'max_session_duration' => 300,
                'data_retention_period' => 7,
                'max_items' => 1000,
                'created' => $row->get('created')->toIso8601String(),
                'created_by' => $row->get('created_by'),
                'modified' => $row->get('modified')->toIso8601String(),
                'modified_by' => $row->get('modified_by'),
            ],
            $this->getResponseBodyAsArray(),
        );
        $this->assertSame(1, OfflineModeSettingFactory::find()->count());
    }

    public function testOfflineSettingsPostController_Success_UpdatesExistingRow(): void
    {
        $original = OfflineModeSettingFactory::make()
            ->setField('value', ['max_session_duration' => 600, 'data_retention_period' => 14, 'max_items' => 500])
            ->persist();
        $admin = $this->logInAsAdmin();

        $this->postJson('/offline/settings.json', [
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);

        $this->assertResponseOk();
        $row = OfflineModeSettingFactory::find()->firstOrFail();
        $body = $this->getResponseBodyAsArray();
        $this->assertSame($original->get('id'), $body['id'], 'Update preserves the row id.');
        $this->assertSame(300, $body['max_session_duration']);
        $this->assertSame(7, $body['data_retention_period']);
        $this->assertSame(1000, $body['max_items']);
        $this->assertSame($row->get('modified_by'), $body['modified_by']);
        $this->assertSame(1, OfflineModeSettingFactory::find()->count());
        // Assert email data
        $this->assertEmailQueueCount(1);
        $this->assertEmailIsInQueue([
            'email' => $admin->username,
            'template' => OfflineSettingsSetEmailRedactor::TEMPLATE,
        ]);
        // The units are not carried on the wire, so the email is where they have to be right.
        $this->assertEmailInBatchContains('Maximum session duration: 300 seconds', $admin->username);
        $this->assertEmailInBatchContains('Data retention period: 7 days', $admin->username);
        $this->assertEmailInBatchContains('Maximum number of offline items (per user): 1000', $admin->username);
    }

    public function testOfflineSettingsPostController_Error_Unauthenticated(): void
    {
        $this->postJson('/offline/settings.json', [
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);

        $this->assertAuthenticationError();
    }

    public function testOfflineSettingsPostController_Error_NotAdmin(): void
    {
        $this->logInAsUser();

        $this->postJson('/offline/settings.json', [
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);

        $this->assertForbiddenError('Access restricted to administrators');
    }

    public function testOfflineSettingsPostController_Error_EmptyBody(): void
    {
        $this->logInAsAdmin();

        $this->postJson('/offline/settings.json', []);

        $this->assertBadRequestError('The request data can not be empty');
    }

    public function testOfflineSettingsPostController_Error_InvalidPayload(): void
    {
        $this->logInAsAdmin();

        $this->postJson('/offline/settings.json', [
            'max_session_duration' => 'not-an-int',
            'data_retention_period' => -1,
            'max_items' => OfflineSettingsDto::MAX_MAX_ITEMS + 1,
        ]);

        $this->assertBadRequestError('Could not validate offline settings data');
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(3, $response);
        // Assert only CE rules are applied
        $this->assertArrayHasAttributes(['integer', 'default_only'], $response['max_session_duration']);
        $this->assertArrayHasKey('default_only', $response['data_retention_period']);
        $this->assertArrayHasKey('default_only', $response['max_items']);
    }

    public function testOfflineSettingsPostController_Error_MaxSessionDurationOutOfRange(): void
    {
        $this->logInAsAdmin();

        $this->postJson('/offline/settings.json', [
            'max_session_duration' => OfflineSettingsDto::MAX_MAX_SESSION_DURATION + 1,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);

        $this->assertBadRequestError('Could not validate offline settings data');
    }

    public function testOfflineSettingsPostController_Error_DataRetentionPeriodOutOfRange(): void
    {
        $this->logInAsAdmin();

        $this->postJson('/offline/settings.json', [
            'max_session_duration' => 300,
            'data_retention_period' => OfflineSettingsDto::MIN_DATA_RETENTION_PERIOD - 1,
            'max_items' => 1000,
        ]);

        $this->assertBadRequestError('Could not validate offline settings data');
    }
}
