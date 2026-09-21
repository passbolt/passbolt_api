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
namespace Passbolt\OfflineModePolicies\Test\TestCase\Service\Edition;

use App\Test\Lib\AppTestCase;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;
use Passbolt\OfflineModePolicies\Service\Edition\OfflineModePoliciesDowngradeCleanupService;

/**
 * @covers \Passbolt\OfflineModePolicies\Service\Edition\OfflineModePoliciesDowngradeCleanupService
 */
class OfflineModePoliciesDowngradeCleanupServiceTest extends AppTestCase
{
    private ?OfflineModePoliciesDowngradeCleanupService $sut = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new OfflineModePoliciesDowngradeCleanupService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testOfflineModePoliciesDowngradeCleanupService_Cleanup_UpdatesCustomValuesToDefaults(): void
    {
        /** @var \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting $setting */
        $setting = OfflineModeSettingFactory::make()->setField('value', [
            'max_session_duration' => OfflineSettingsDto::MAX_MAX_SESSION_DURATION,
            'data_retention_period' => OfflineSettingsDto::MAX_DATA_RETENTION_PERIOD,
            'max_items' => OfflineSettingsDto::MAX_MAX_ITEMS,
        ])->persist();
        OfflineItemFactory::make(3)->persist();

        $this->sut->cleanup();

        $this->assertSame(1, OfflineModeSettingFactory::count());
        $this->assertSame($setting->get('id'), OfflineModeSettingFactory::firstOrFail()->get('id'));
        $this->assertSame(
            OfflineSettingsDto::createFromDefault()->toSettingsArray(),
            OfflineModeSettingFactory::firstOrFail()->get('value'),
        );
        $this->assertSame(3, OfflineItemFactory::count());
    }

    public function testOfflineModePoliciesDowngradeCleanupService_Cleanup_NoOpWhenNoSettingExists(): void
    {
        $this->sut->cleanup();
        $this->assertSame(0, OfflineModeSettingFactory::count());
    }

    public function testOfflineModePoliciesDowngradeCleanupService_Cleanup_InvalidValuesChangesToDefaults(): void
    {
        OfflineModeSettingFactory::make()->setField('value', ['max_items' => 'not-an-int'])->persist();

        $this->sut->cleanup();

        $this->assertSame(
            OfflineSettingsDto::createFromDefault()->toSettingsArray(),
            OfflineModeSettingFactory::firstOrFail()->get('value'),
        );
    }
}
