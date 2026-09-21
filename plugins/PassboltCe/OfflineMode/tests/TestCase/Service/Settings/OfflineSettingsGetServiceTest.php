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
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\OfflineMode\Form\OfflineSettingsDefaultsForm;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsGetService;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;

/**
 * @covers \Passbolt\OfflineMode\Service\Settings\OfflineSettingsGetService
 */
class OfflineSettingsGetServiceTest extends AppTestCase
{
    use LocatorAwareTrait;

    private OfflineSettingsGetService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new OfflineSettingsGetService(new OfflineSettingsDefaultsForm());
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testOfflineSettingsGetService_Get_Success_ReturnsNullWhenNoRow(): void
    {
        $result = $this->service->get();

        $this->assertNull($result);
    }

    public function testOfflineSettingsGetService_Get_Success_ReturnsFromDB(): void
    {
        $setting = OfflineModeSettingFactory::make()
            ->setField('value', ['max_session_duration' => 300, 'data_retention_period' => 7, 'max_items' => 1000])
            ->persist();

        $result = $this->service->get();

        $resultArray = $result->toArray();
        $expectedResult = array_merge($resultArray, [
            'created' => $resultArray['created']->toIso8601String(),
            'modified' => $resultArray['modified']->toIso8601String(),
        ]);
        $this->assertArrayEqualsCanonicalizing(
            [
                'id' => $setting->get('id'),
                'max_session_duration' => 300,
                'data_retention_period' => 7,
                'max_items' => 1000,
                'created' => $setting->get('created')->toIso8601String(),
                'created_by' => $setting->get('created_by'),
                'modified' => $setting->get('modified')->toIso8601String(),
                'modified_by' => $setting->get('modified_by'),
            ],
            $expectedResult,
        );
    }

    public function testOfflineSettingsGetService_Get_Error_StoredValueIsNotAnArray(): void
    {
        OfflineModeSettingFactory::make()->setField('value', 'not-an-array')->persist();

        try {
            $this->service->get();
            $this->fail('A CustomValidationException should have been thrown.');
        } catch (CustomValidationException $e) {
            $this->assertArrayHasKey('invalid', $e->getErrors()['value']);
        }
    }

    public function testOfflineSettingsGetService_Get_Error_StoredValueIsNotAnInteger(): void
    {
        OfflineModeSettingFactory::make()
            ->setField('value', [
                'max_session_duration' => 'not-an-int',
                'data_retention_period' => 7,
                'max_items' => 1000,
            ])
            ->persist();

        $this->expectException(CustomValidationException::class);
        $this->service->get();
    }

    public function testOfflineSettingsGetService_Get_Error_StoredValueIsMissingAKey(): void
    {
        OfflineModeSettingFactory::make()
            ->setField('value', ['max_session_duration' => 300, 'data_retention_period' => 7])
            ->persist();

        $this->expectException(CustomValidationException::class);
        $this->service->get();
    }

    public function testOfflineSettingsGetService_Get_Error_ValidationTriggers(): void
    {
        OfflineModeSettingFactory::make()
            ->setField('value', [
                'max_session_duration' => 600,
                'data_retention_period' => 14,
                'max_items' => 500,
            ])
            ->persist();

        try {
            $this->service->get();
            $this->fail('A CustomValidationException should have been thrown.');
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertArrayHasKey('default_only', $errors['max_session_duration']);
        }
    }

    public function testOfflineSettingsGetService_ThrowExceptionIfDisabled_NoOp_WhenEnabled(): void
    {
        OfflineModeSettingFactory::make()->persist();
        $this->service->throwExceptionIfDisabled();
        $this->assertTrue(true);
    }

    public function testOfflineSettingsGetService_ThrowExceptionIfDisabled_Throws_WhenDisabled(): void
    {
        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Offline Mode is not enabled at the org level.');
        $this->service->throwExceptionIfDisabled();
    }
}
