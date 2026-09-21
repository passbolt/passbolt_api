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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Test\TestCase\Model\Dto;

use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Test\Factory\EditionOrganizationSettingFactory;

/**
 * @covers \Passbolt\Edition\Model\Dto\EditionDto
 */
class EditionDtoTest extends TestCase
{
    public function testEditionDto_FromOrgSettingRow_NullRowYieldsCeWithNoChangeTimestamp(): void
    {
        $dto = EditionDto::fromOrgSettingRow(null);

        $this->assertTrue($dto->isCe());
        $this->assertNull($dto->getLastEditionChangeDateTime());
    }

    public function testEditionDto_FromOrgSettingRow_FreshCeRowHasNoChangeTimestamp(): void
    {
        // Same exact DateTime object for both columns simulates a fresh insert
        // where TimestampBehavior wrote the same value to created and modified.
        $now = DateTime::now();
        $row = EditionOrganizationSettingFactory::make()
            ->setField('value', EditionDto::EDITION_CE)
            ->setField('created', $now)
            ->setField('modified', $now)
            ->getEntity();

        $dto = EditionDto::fromOrgSettingRow($row);

        $this->assertTrue($dto->isCe());
        $this->assertNull($dto->getLastEditionChangeDateTime());
    }

    public function testEditionDto_FromOrgSettingRow_TouchedCeRowExposesModifiedAsChangeTimestamp(): void
    {
        $created = new DateTime('2024-01-01 10:00:00');
        $modified = new DateTime('2024-06-15 12:34:56');
        $row = EditionOrganizationSettingFactory::make()
            ->setField('value', EditionDto::EDITION_CE)
            ->setField('created', $created)
            ->setField('modified', $modified)
            ->getEntity();

        $dto = EditionDto::fromOrgSettingRow($row);

        $this->assertTrue($dto->isCe());
        $this->assertNotNull($dto->getLastEditionChangeDateTime());
        $this->assertSame(
            $modified->getTimestamp(),
            $dto->getLastEditionChangeDateTime()->getTimestamp(),
        );
    }

    public function testEditionDto_FromOrgSettingRow_TouchedProRowExposesModifiedAsChangeTimestamp(): void
    {
        // A PRO row whose modified differs from created reflects a CE → PRO
        // upgrade — the change timestamp is exposed just like for downgrade.
        $created = new DateTime('2024-01-01 10:00:00');
        $modified = new DateTime('2024-06-15 12:34:56');
        $row = EditionOrganizationSettingFactory::make()
            ->setField('value', EditionDto::EDITION_PRO)
            ->setField('created', $created)
            ->setField('modified', $modified)
            ->getEntity();

        $dto = EditionDto::fromOrgSettingRow($row);

        $this->assertTrue($dto->isPro());
        $this->assertNotNull($dto->getLastEditionChangeDateTime());
        $this->assertSame(
            $modified->getTimestamp(),
            $dto->getLastEditionChangeDateTime()->getTimestamp(),
        );
    }

    public function testEditionDto_FromOrgSettingRow_MillisecondDriftWithinSameSecondIsIgnored(): void
    {
        // Two timestamps within the same wall-clock second but offset by ~500ms
        // — TimestampBehavior can produce this under DATETIME(6) columns. The
        // loose comparison via getTimestamp() (Unix epoch seconds) ignores it,
        // so no change is detected.
        $row = EditionOrganizationSettingFactory::make()
            ->setField('value', EditionDto::EDITION_CE)
            ->setField('created', DateTime::createFromFormat('Y-m-d H:i:s.u', '2024-01-01 10:00:00.000'))
            ->setField('modified', DateTime::createFromFormat('Y-m-d H:i:s.u', '2024-01-01 10:00:00.500'))
            ->getEntity();

        $dto = EditionDto::fromOrgSettingRow($row);

        $this->assertTrue($dto->isCe());
        $this->assertNull($dto->getLastEditionChangeDateTime());
    }

    public function testEditionDto_FromOrgSettingRow_OneSecondDriftIsTreatedAsChange(): void
    {
        // Boundary case: a single full second crosses the loose comparison,
        // so this counts as an edition change.
        $row = EditionOrganizationSettingFactory::make()
            ->setField('value', EditionDto::EDITION_CE)
            ->setField('created', new DateTime('2024-01-01 10:00:00'))
            ->setField('modified', new DateTime('2024-01-01 10:00:01'))
            ->getEntity();

        $dto = EditionDto::fromOrgSettingRow($row);

        $this->assertNotNull($dto->getLastEditionChangeDateTime());
    }

    public function testEditionDto_ToArray_IncludesChangeTimestampAsIso8601(): void
    {
        $modified = new DateTime('2024-06-15T12:34:56+00:00');
        $dto = new EditionDto(EditionDto::EDITION_CE, $modified);

        $array = $dto->toArray();

        $this->assertSame(EditionDto::EDITION_CE, $array['edition']);
        $this->assertSame($modified->toAtomString(), $array['lastEditionChangeDateTime']);
    }

    public function testEditionDto_ToArray_ChangeTimestampIsNullWhenAbsent(): void
    {
        $dto = new EditionDto(EditionDto::EDITION_CE);

        $this->assertNull($dto->toArray()['lastEditionChangeDateTime']);
    }
}
