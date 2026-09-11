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
namespace Passbolt\OfflineMode\Test\TestCase\Service\Settings;

use App\Error\Exception\CustomValidationException;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\OfflineMode\Form\OfflineSettingsDefaultsForm;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsSetService;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;

/**
 * @covers \Passbolt\OfflineMode\Service\Settings\OfflineSettingsSetService
 */
class OfflineSettingsSetServiceTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;

    private OfflineSettingsSetService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new OfflineSettingsSetService(new OfflineSettingsDefaultsForm());
        EventManager::instance()->setEventList(new EventList());
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testOfflineSettingsSetService_Success_CreatesRow(): void
    {
        $uac = $this->mockExtendedAdminAccessControl();

        $dto = $this->service->set($uac, [
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);

        $row = OfflineModeSettingFactory::find()->firstOrFail();
        $resultArray = $dto->toArray();
        $expectedResult = array_merge($resultArray, [
            'created' => $resultArray['created']->toIso8601String(),
            'modified' => $resultArray['modified']->toIso8601String(),
        ]);
        $this->assertArrayEqualsCanonicalizing(
            [
                'id' => $row->get('id'),
                'max_session_duration' => 300,
                'data_retention_period' => 7,
                'max_items' => 1000,
                'created' => $row->get('created')->toIso8601String(),
                'created_by' => $uac->getId(),
                'modified' => $row->get('modified')->toIso8601String(),
                'modified_by' => $uac->getId(),
            ],
            $expectedResult,
        );
        $this->assertSame(
            [
                'max_session_duration' => 300,
                'data_retention_period' => 7,
                'max_items' => 1000,
            ],
            $row->get('value'),
        );
        // assert event payload
        $this->assertEventFiredWith(OfflineSettingsSetService::EVENT_SETTINGS_UPDATED, 'dto', $dto);
        $this->assertEventFiredWith(OfflineSettingsSetService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testOfflineSettingsSetService_Success_UpdatesExistingRow(): void
    {
        $original = OfflineModeSettingFactory::make()
            ->setField('value', ['max_session_duration' => 600, 'data_retention_period' => 14, 'max_items' => 500])
            ->persist();
        $uac = $this->mockExtendedAdminAccessControl();

        $dto = $this->service->set($uac, [
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);

        $this->assertSame($original->get('id'), $dto->id, 'Update preserves the row id.');
        $this->assertSame(300, $dto->max_session_duration);
        $this->assertSame(7, $dto->data_retention_period);
        $this->assertSame(1000, $dto->max_items);
        $this->assertSame($uac->getId(), $dto->modified_by);
        $this->assertSame(1, OfflineModeSettingFactory::find()->count());
    }

    public function testOfflineSettingsSetService_Error_NotAdmin(): void
    {
        $uac = $this->mockExtendedUserAccessControl();

        $this->expectException(ForbiddenException::class);

        $this->service->set($uac, [
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);
    }

    public function testOfflineSettingsSetService_Error_InvalidPayload(): void
    {
        $uac = $this->mockExtendedAdminAccessControl();

        $this->expectException(CustomValidationException::class);

        $this->service->set($uac, [
            'max_session_duration' => -1,
            'data_retention_period' => 'not-an-int',
        ]);
    }
}
