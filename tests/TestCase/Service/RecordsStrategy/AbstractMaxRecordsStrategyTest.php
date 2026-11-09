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

namespace App\Test\TestCase\Service\RecordsStrategy;

use App\Model\Entity\Role;
use App\Service\RecordsStrategy\AbstractMaxRecordsStrategy;
use App\Service\RecordsStrategy\DeletedGroupsMaxRecordsStrategy;
use App\Service\RecordsStrategy\DeletedResourcesMaxRecordsStrategy;
use App\Service\RecordsStrategy\DeletedUsersMaxRecordsStrategy;
use App\Service\RecordsStrategy\NonDeletedGroupsMaxRecordsStrategy;
use App\Service\RecordsStrategy\NonDeletedResourcesMaxRecordsStrategy;
use App\Service\RecordsStrategy\NonDeletedUsersMaxRecordsStrategy;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Http\Exception\ConflictException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(AbstractMaxRecordsStrategy::class)]
#[CoversClass(NonDeletedUsersMaxRecordsStrategy::class)]
#[CoversClass(DeletedUsersMaxRecordsStrategy::class)]
#[CoversClass(NonDeletedGroupsMaxRecordsStrategy::class)]
#[CoversClass(DeletedGroupsMaxRecordsStrategy::class)]
#[CoversClass(NonDeletedResourcesMaxRecordsStrategy::class)]
#[CoversClass(DeletedResourcesMaxRecordsStrategy::class)]
class AbstractMaxRecordsStrategyTest extends AppTestCase
{
    private UserAccessControl $uac;

    public function setUp(): void
    {
        parent::setUp();
        $this->uac = new UserAccessControl(Role::ADMIN);
    }

    public function tearDown(): void
    {
        unset($this->uac);
        parent::tearDown();
    }

    private function isAllowed(AbstractMaxRecordsStrategy $strategy): bool
    {
        try {
            $strategy->assertAllowed($this->uac);
        } catch (ConflictException) {
            return false;
        }

        return true;
    }

    public function testAbstractMaxRecordsStrategy_NoMaxByDefault(): void
    {
        UserFactory::make(2)->persist();

        $this->assertTrue($this->isAllowed(new NonDeletedUsersMaxRecordsStrategy()));
    }

    public function testAbstractMaxRecordsStrategy_BelowMax(): void
    {
        Configure::write(NonDeletedUsersMaxRecordsStrategy::CONFIG_KEY, 2);
        UserFactory::make()->persist();

        $this->assertTrue($this->isAllowed(new NonDeletedUsersMaxRecordsStrategy()));
    }

    public function testAbstractMaxRecordsStrategy_MaxReached(): void
    {
        Configure::write(NonDeletedUsersMaxRecordsStrategy::CONFIG_KEY, 2);
        UserFactory::make(2)->persist();

        $this->expectException(ConflictException::class);
        $this->expectExceptionMessage('The maximum number of users for this organization has been reached.');
        (new NonDeletedUsersMaxRecordsStrategy())->assertAllowed($this->uac);
    }

    public function testAbstractMaxRecordsStrategy_MaxReadWhenChecking(): void
    {
        UserFactory::make()->persist();
        $strategy = new NonDeletedUsersMaxRecordsStrategy();
        $this->assertTrue($this->isAllowed($strategy));

        // E.g. overwritten by the cloud after the strategy was created
        Configure::write(NonDeletedUsersMaxRecordsStrategy::CONFIG_KEY, 1);
        $this->assertFalse($this->isAllowed($strategy));
    }

    public function testAbstractMaxRecordsStrategy_CountDeletedAndNonDeletedRecordsSeparately(): void
    {
        UserFactory::make(1)->persist();
        UserFactory::make(2)->setField('deleted', true)->persist();
        GroupFactory::make(3)->persist();
        GroupFactory::make(4)->setField('deleted', true)->persist();
        ResourceFactory::make(5)->persist();
        ResourceFactory::make(6)->setField('deleted', true)->persist();

        $strategiesCount = [
            NonDeletedUsersMaxRecordsStrategy::class => 1,
            DeletedUsersMaxRecordsStrategy::class => 2,
            NonDeletedGroupsMaxRecordsStrategy::class => 3,
            DeletedGroupsMaxRecordsStrategy::class => 4,
            NonDeletedResourcesMaxRecordsStrategy::class => 5,
            DeletedResourcesMaxRecordsStrategy::class => 6,
        ];
        foreach ($strategiesCount as $strategyClass => $count) {
            Configure::write($strategyClass::CONFIG_KEY, $count + 1);
            $this->assertTrue($this->isAllowed(new $strategyClass()), $strategyClass);
            Configure::write($strategyClass::CONFIG_KEY, $count);
            $this->assertFalse($this->isAllowed(new $strategyClass()), $strategyClass);
        }
    }

    public static function configuredMaxProvider(): array
    {
        return [
            'integer reached' => [1, false],
            'integer not reached' => [2, true],
            'zero' => [0, false],
            'not set' => [null, true],
            'negative' => [-1, true],
            'not parsed' => ['0', true],
        ];
    }

    #[DataProvider('configuredMaxProvider')]
    public function testAbstractMaxRecordsStrategy_ConfiguredMax(mixed $configuredValue, bool $expectedAllowed): void
    {
        UserFactory::make()->persist();
        Configure::write(NonDeletedUsersMaxRecordsStrategy::CONFIG_KEY, $configuredValue);

        $this->assertSame($expectedAllowed, $this->isAllowed(new NonDeletedUsersMaxRecordsStrategy()));
    }

    public static function configKeyProvider(): array
    {
        return [
            [NonDeletedUsersMaxRecordsStrategy::class, 'passbolt.security.maxRecords.usersAdd'],
            [DeletedUsersMaxRecordsStrategy::class, 'passbolt.security.maxRecords.usersDelete'],
            [NonDeletedGroupsMaxRecordsStrategy::class, 'passbolt.security.maxRecords.groupsAdd'],
            [DeletedGroupsMaxRecordsStrategy::class, 'passbolt.security.maxRecords.groupsDelete'],
            [NonDeletedResourcesMaxRecordsStrategy::class, 'passbolt.security.maxRecords.resourcesAdd'],
            [DeletedResourcesMaxRecordsStrategy::class, 'passbolt.security.maxRecords.resourcesDelete'],
        ];
    }

    #[DataProvider('configKeyProvider')]
    public function testAbstractMaxRecordsStrategy_EachStrategyReadsItsConfiguredMax(string $strategyClass, string $key): void
    {
        $this->assertTrue($this->isAllowed(new $strategyClass()));

        Configure::write($key, 0);
        $this->assertFalse($this->isAllowed(new $strategyClass()));
    }
}
