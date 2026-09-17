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

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsDeleteService;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;

/**
 * @covers \Passbolt\OfflineMode\Service\Settings\OfflineSettingsDeleteService
 */
class OfflineSettingsDeleteServiceTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;
    use LocatorAwareTrait;

    private OfflineSettingsDeleteService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new OfflineSettingsDeleteService();
        EventManager::instance()->setEventList(new EventList());
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testOfflineSettingsDeleteService_Success_DeletesRowAndDispatchesEvent(): void
    {
        $row = OfflineModeSettingFactory::make()
            ->setField('value', ['max_session_duration' => 300, 'data_retention_period' => 7])
            ->persist();
        $uac = $this->mockExtendedAdminAccessControl();

        $this->service->delete($uac, $row->get('id'));

        $this->assertSame(0, OfflineModeSettingFactory::find()->count());
        $this->assertEventFired(OfflineSettingsDeleteService::EVENT_SETTINGS_DELETED);
        $this->assertEventFiredWith(OfflineSettingsDeleteService::EVENT_SETTINGS_DELETED, 'uac', $uac);
    }

    public function testOfflineSettingsDeleteService_Error_NotAdmin(): void
    {
        $row = OfflineModeSettingFactory::make()
            ->setField('value', ['max_session_duration' => 300, 'data_retention_period' => 7])
            ->persist();
        $uac = $this->mockExtendedUserAccessControl();

        $this->expectException(ForbiddenException::class);

        $this->service->delete($uac, $row->get('id'));
    }

    public function testOfflineSettingsDeleteService_Error_RowDoesNotExist(): void
    {
        $uac = $this->mockExtendedAdminAccessControl();

        $this->expectException(RecordNotFoundException::class);

        $this->service->delete($uac, '00000000-0000-0000-0000-000000000000');
    }

    public function testOfflineSettingsDeleteService_Error_RowBelongsToDifferentProperty(): void
    {
        $foreignId = $this->createForeignPropertyRow();
        $uac = $this->mockExtendedAdminAccessControl();

        $this->expectException(RecordNotFoundException::class);

        $this->service->delete($uac, $foreignId);
    }

    private function createForeignPropertyRow(): string
    {
        $generic = $this->fetchTable('OrganizationSettings');
        $entity = $generic->newEntity(
            [
                'property' => 'someOtherProperty',
                'property_id' => UuidFactory::uuid('someOtherProperty'),
                'value' => '{}',
                'created_by' => UuidFactory::uuid('user.id.foreign'),
                'modified_by' => UuidFactory::uuid('user.id.foreign'),
            ],
            [
                'accessibleFields' => [
                    'property' => true,
                    'property_id' => true,
                    'value' => true,
                    'created_by' => true,
                    'modified_by' => true,
                ],
            ],
        );
        $generic->saveOrFail($entity);

        return $entity->get('id');
    }
}
