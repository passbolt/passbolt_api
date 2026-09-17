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
namespace Passbolt\OfflineMode\Test\TestCase\Form;

use Cake\TestSuite\TestCase;
use Passbolt\OfflineMode\Form\OfflineSettingsDefaultsForm;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;

/**
 * @covers \Passbolt\OfflineMode\Form\OfflineSettingsDefaultsForm
 */
class OfflineSettingsDefaultsFormTest extends TestCase
{
    protected OfflineSettingsDefaultsForm $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new OfflineSettingsDefaultsForm();
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

    public function testOfflineSettingsDefaultsForm_Success(): void
    {
        $this->assertTrue($this->form->execute(self::getDefaultData()));
        $this->assertSame([], $this->form->getErrors());
        $this->assertSame(self::getDefaultData(), $this->form->getSettings());
    }

    public function testOfflineSettingsDefaultsForm_Error_Empty(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        $this->assertArrayHasKey('_empty', $errors['max_session_duration']);
        $this->assertArrayHasKey('_empty', $errors['data_retention_period']);
        $this->assertArrayHasKey('_empty', $errors['max_items']);
    }

    public static function invalidValueProvider(): array
    {
        return [
            'string' => ['string'],
            'array' => [[]],
            'zero' => [0],
            'negative' => [-1],
        ];
    }

    /**
     * @dataProvider invalidValueProvider
     * @param mixed $value Invalid value.
     * @return void
     */
    public function testOfflineSettingsDefaultsForm_Error_InvalidMaxSessionDuration(mixed $value): void
    {
        $data = array_merge(self::getDefaultData(), ['max_session_duration' => $value]);

        $this->assertFalse($this->form->execute($data));
        $this->assertNotEmpty($this->form->getError('max_session_duration'));
    }

    /**
     * @dataProvider invalidValueProvider
     * @param mixed $value Invalid value.
     * @return void
     */
    public function testOfflineSettingsDefaultsForm_Error_InvalidDataRetentionPeriod(mixed $value): void
    {
        $data = array_merge(self::getDefaultData(), ['data_retention_period' => $value]);

        $this->assertFalse($this->form->execute($data));
        $this->assertNotEmpty($this->form->getError('data_retention_period'));
    }

    /**
     * @dataProvider invalidValueProvider
     * @param mixed $value Invalid value.
     * @return void
     */
    public function testOfflineSettingsDefaultsForm_Error_InvalidMaxItems(mixed $value): void
    {
        $data = array_merge(self::getDefaultData(), ['max_items' => $value]);

        $this->assertFalse($this->form->execute($data));
        $this->assertNotEmpty($this->form->getError('max_items'));
    }

    /**
     * @return array
     */
    public static function nonDefaultInValuesProvider(): array
    {
        return [
            'max_session_duration' => ['max_session_duration', 600],
            'data_retention_period' => ['data_retention_period', 14],
            'max_items' => ['max_items', 500],
        ];
    }

    /**
     * @dataProvider nonDefaultInValuesProvider
     * @param string $field Field under test.
     * @param int $value An in-bounds value that is not the default.
     * @return void
     */
    public function testOfflineSettingsDefaultsForm_Error_NonDefaultValueRejected(string $field, int $value): void
    {
        $data = array_merge(self::getDefaultData(), [$field => $value]);

        $this->assertFalse($this->form->execute($data));
        $this->assertArrayHasKey('default_only', $this->form->getErrors()[$field]);
    }

    public function testOfflineSettingsDefaultsForm_Error_OutOfBoundsValueRejected(): void
    {
        $data = array_merge(self::getDefaultData(), [
            'max_items' => OfflineSettingsDto::MAX_MAX_ITEMS + 1,
        ]);

        $this->assertFalse($this->form->execute($data));
        $this->assertArrayHasKey('default_only', $this->form->getErrors()['max_items']);
    }
}
