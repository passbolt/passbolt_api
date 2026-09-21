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
namespace Passbolt\OfflineMode\Test\TestCase\Model\Dto;

use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;
use Passbolt\OfflineMode\Model\Entity\OfflineModeSetting;

/**
 * @covers \Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto
 */
class OfflineSettingsDtoTest extends TestCase
{
    public function testOfflineSettingsDto_CreateFromArray_Success(): void
    {
        $dto = OfflineSettingsDto::createFromArray([
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);

        $this->assertSame(300, $dto->max_session_duration);
        $this->assertSame(7, $dto->data_retention_period);
        $this->assertSame(1000, $dto->max_items);
        $this->assertNull($dto->id);
        $this->assertNull($dto->created);
        $this->assertNull($dto->created_by);
        $this->assertNull($dto->modified);
        $this->assertNull($dto->modified_by);
    }

    public function testOfflineSettingsDto_CreateFromArray_Success_PopulatesAuditFields(): void
    {
        $created = new DateTime('2026-04-23T09:00:00+00:00');
        $modified = new DateTime('2026-04-24T09:00:00+00:00');
        $userId = UuidFactory::uuid('user.admin');

        $dto = OfflineSettingsDto::createFromArray([
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
            'id' => UuidFactory::uuid('row'),
            'created' => $created,
            'created_by' => $userId,
            'modified' => $modified,
            'modified_by' => $userId,
        ]);

        $this->assertSame(UuidFactory::uuid('row'), $dto->id);
        $this->assertSame($created, $dto->created);
        $this->assertSame($userId, $dto->created_by);
        $this->assertSame($modified, $dto->modified);
        $this->assertSame($userId, $dto->modified_by);
    }

    public static function invalidMaxSessionDurationValuesProvider(): array
    {
        return [
            [null],
            ['string'],
            [[]],
        ];
    }

    /**
     * @dataProvider invalidMaxSessionDurationValuesProvider
     * @param mixed $invalidValue Invalid value.
     * @return void
     */
    public function testOfflineSettingsDto_CreateFromArray_Error_InvalidMaxSessionDuration(mixed $invalidValue): void
    {
        $data = [
            'data_retention_period' => 7,
            'max_items' => 1000,
            'max_session_duration' => $invalidValue,
        ];
        if (is_null($invalidValue)) {
            unset($data['max_session_duration']);
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/max_session_duration/');

        OfflineSettingsDto::createFromArray($data);
    }

    public static function invalidDataRetentionPeriodValuesProvider(): array
    {
        return [
            [null],
            ['string'],
            [[]],
        ];
    }

    /**
     * @dataProvider invalidDataRetentionPeriodValuesProvider
     * @param mixed $invalidValue Invalid value.
     * @return void
     */
    public function testOfflineSettingsDto_CreateFromArray_Error_InvalidDataRetentionPeriod(mixed $invalidValue): void
    {
        $data = [
            'max_session_duration' => 300,
            'max_items' => 1000,
            'data_retention_period' => $invalidValue,
        ];
        if (is_null($invalidValue)) {
            unset($data['data_retention_period']);
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/data_retention_period/');

        OfflineSettingsDto::createFromArray($data);
    }

    public static function invalidMaxItemsValuesProvider(): array
    {
        return [
            [null],
            ['string'],
            [[]],
        ];
    }

    /**
     * @dataProvider invalidMaxItemsValuesProvider
     * @param mixed $invalidValue Invalid value.
     * @return void
     */
    public function testOfflineSettingsDto_CreateFromArray_Error_InvalidMaxItems(mixed $invalidValue): void
    {
        $data = [
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => $invalidValue,
        ];
        if (is_null($invalidValue)) {
            unset($data['max_items']);
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/max_items/');

        OfflineSettingsDto::createFromArray($data);
    }

    public function testOfflineSettingsDto_CreateFromDefault_Success(): void
    {
        $dto = OfflineSettingsDto::createFromDefault();

        $this->assertSame(OfflineSettingsDto::DEFAULT_MAX_SESSION_DURATION, $dto->max_session_duration);
        $this->assertSame(OfflineSettingsDto::DEFAULT_DATA_RETENTION_PERIOD, $dto->data_retention_period);
        $this->assertSame(OfflineSettingsDto::DEFAULT_MAX_ITEMS, $dto->max_items);
        $this->assertNull($dto->id);
    }

    public function testOfflineSettingsDto_CreateFromEntity_Success(): void
    {
        $created = new DateTime('2026-04-23T09:00:00+00:00');
        $modified = new DateTime('2026-04-24T09:00:00+00:00');
        $userId = UuidFactory::uuid('user.admin');
        $rowId = UuidFactory::uuid('row');

        $entity = new OfflineModeSetting([
            'id' => $rowId,
            'value' => ['max_session_duration' => 600, 'data_retention_period' => 14, 'max_items' => 500],
            'created' => $created,
            'created_by' => $userId,
            'modified' => $modified,
            'modified_by' => $userId,
        ]);

        $dto = OfflineSettingsDto::createFromEntity($entity);

        $this->assertSame(600, $dto->max_session_duration);
        $this->assertSame(14, $dto->data_retention_period);
        $this->assertSame(500, $dto->max_items);
        $this->assertSame($rowId, $dto->id);
        $this->assertSame($created, $dto->created);
        $this->assertSame($userId, $dto->created_by);
        $this->assertSame($modified, $dto->modified);
        $this->assertSame($userId, $dto->modified_by);
    }

    public function testOfflineSettingsDto_CreateFromEntity_Error_ValueNotArray(): void
    {
        $entity = new OfflineModeSetting(['value' => 'not-an-array']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/must be an array/');

        OfflineSettingsDto::createFromEntity($entity);
    }
}
