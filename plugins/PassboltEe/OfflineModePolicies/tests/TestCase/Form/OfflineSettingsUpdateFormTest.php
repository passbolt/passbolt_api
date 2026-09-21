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
namespace Passbolt\OfflineModePolicies\Test\TestCase\Form;

use Cake\TestSuite\TestCase;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;
use Passbolt\OfflineModePolicies\Form\OfflineSettingsUpdateForm;

/**
 * @covers \Passbolt\OfflineModePolicies\Form\OfflineSettingsUpdateForm
 */
class OfflineSettingsUpdateFormTest extends TestCase
{
    protected OfflineSettingsUpdateForm $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new OfflineSettingsUpdateForm();
    }

    public function tearDown(): void
    {
        unset($this->form);
        parent::tearDown();
    }

    public static function getDefaultData(): array
    {
        return [
            'max_session_duration' => OfflineSettingsDto::DEFAULT_MAX_SESSION_DURATION,
            'data_retention_period' => OfflineSettingsDto::DEFAULT_DATA_RETENTION_PERIOD,
            'max_items' => OfflineSettingsDto::DEFAULT_MAX_ITEMS,
        ];
    }

    public function testOfflineSettingsUpdateForm_Success(): void
    {
        $this->assertTrue($this->form->execute(self::getDefaultData()));
        $this->assertSame([], $this->form->getErrors());
        $this->assertSame(self::getDefaultData(), $this->form->getSettings());
    }

    public function testOfflineSettingsUpdateForm_Success_NonDefaultInRangeValues(): void
    {
        $data = [
            'max_session_duration' => 600,
            'data_retention_period' => 14,
            'max_items' => 500,
        ];

        $this->assertTrue($this->form->execute($data), json_encode($this->form->getErrors()));
        $this->assertSame($data, $this->form->getSettings());
    }

    public function testOfflineSettingsUpdateForm_Error_Empty(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        $this->assertArrayHasKey('_empty', $errors['max_session_duration']);
        $this->assertArrayHasKey('_empty', $errors['data_retention_period']);
        $this->assertArrayHasKey('_empty', $errors['max_items']);
    }

    public static function maxSessionDurationBoundaryProvider(): array
    {
        return [
            'below lower bound' => [OfflineSettingsDto::MIN_MAX_SESSION_DURATION - 1, false],
            'lower bound' => [OfflineSettingsDto::MIN_MAX_SESSION_DURATION, true],
            'upper bound' => [OfflineSettingsDto::MAX_MAX_SESSION_DURATION, true],
            'above upper bound' => [OfflineSettingsDto::MAX_MAX_SESSION_DURATION + 1, false],
        ];
    }

    /**
     * @dataProvider maxSessionDurationBoundaryProvider
     * @param int $value Value to test.
     * @param bool $expected Expected value.
     * @return void
     */
    public function testOfflineSettingsUpdateForm_MaxSessionDurationBoundaries(int $value, bool $expected): void
    {
        $data = array_merge(self::getDefaultData(), ['max_session_duration' => $value]);

        $this->assertSame($expected, $this->form->execute($data));
    }

    public static function dataRetentionPeriodBoundaryProvider(): array
    {
        return [
            'below lower bound' => [OfflineSettingsDto::MIN_DATA_RETENTION_PERIOD - 1, false],
            'lower bound' => [OfflineSettingsDto::MIN_DATA_RETENTION_PERIOD, true],
            'upper bound' => [OfflineSettingsDto::MAX_DATA_RETENTION_PERIOD, true],
            'above upper bound' => [OfflineSettingsDto::MAX_DATA_RETENTION_PERIOD + 1, false],
        ];
    }

    /**
     * @dataProvider dataRetentionPeriodBoundaryProvider
     * @param int $value Value to test.
     * @param bool $expected Expected value.
     * @return void
     */
    public function testOfflineSettingsUpdateForm_DataRetentionPeriodBoundaries(int $value, bool $expected): void
    {
        $data = array_merge(self::getDefaultData(), ['data_retention_period' => $value]);

        $this->assertSame($expected, $this->form->execute($data));
    }

    public static function maxItemsBoundaryProvider(): array
    {
        return [
            'below lower bound' => [OfflineSettingsDto::MIN_MAX_ITEMS - 1, false],
            'lower bound' => [OfflineSettingsDto::MIN_MAX_ITEMS, true],
            'upper bound' => [OfflineSettingsDto::MAX_MAX_ITEMS, true],
            'above upper bound' => [OfflineSettingsDto::MAX_MAX_ITEMS + 1, false],
        ];
    }

    /**
     * @dataProvider maxItemsBoundaryProvider
     * @param int $value Value to test.
     * @param bool $expected Expected value.
     * @return void
     */
    public function testOfflineSettingsUpdateForm_MaxItemsBoundaries(int $value, bool $expected): void
    {
        $data = array_merge(self::getDefaultData(), ['max_items' => $value]);

        $this->assertSame($expected, $this->form->execute($data));
    }
}
