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
 * @since         5.11.0
 */

namespace Passbolt\Scim\Test\TestCase\Model\Table;

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Database\Driver\Mysql;
use Cake\Database\Exception\QueryException;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\DateTime;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Test\Factory\ScimEntryFactory;

/**
 * ScimEntriesTableTest - Tests for the unique scim_name DB constraint.
 */
class ScimEntriesTableTest extends AppTestCase
{
    /**
     * @var \Passbolt\Scim\Model\Table\ScimEntriesTable
     */
    private $ScimEntries;

    public function setUp(): void
    {
        parent::setUp();
        $this->ScimEntries = TableRegistry::getTableLocator()->get('Passbolt/Scim.ScimEntries');
    }

    public function tearDown(): void
    {
        unset($this->ScimEntries);
        ConnectionManager::drop('concurrent');
        parent::tearDown();
    }

    /**
     * Test that saving two active entries with the same scim_name and foreign_model
     * fails gracefully with a validation error (not an unhandled exception).
     */
    public function testScimEntriesTable_DuplicateActiveScimName_FailsWithValidationError(): void
    {
        $scimName = 'duplicate-test@passbolt.com';
        ScimEntryFactory::make([
            'scim_name' => $scimName,
            'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
        ])->persist();

        $duplicate = $this->ScimEntries->buildEntity([
            'foreign_key' => UuidFactory::uuid(),
            'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
            'scim_name' => $scimName,
        ]);

        $result = $this->ScimEntries->save($duplicate, ['lockForUpdate' => true]);

        $this->assertFalse($result);
        $this->assertNotEmpty($duplicate->getError('scim_name'));
    }

    /**
     * Test that a soft-deleted entry does not block creation of a new entry
     * with the same scim_name and foreign_model.
     */
    public function testScimEntriesTable_DeletedEntryDoesNotBlockNewEntry(): void
    {
        $scimName = 'reuse-test@passbolt.com';
        ScimEntryFactory::make([
            'scim_name' => $scimName,
            'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
            'deleted' => DateTime::now(),
        ])->persist();

        $newEntry = $this->ScimEntries->buildEntity([
            'foreign_key' => UuidFactory::uuid(),
            'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
            'scim_name' => $scimName,
        ]);

        $result = $this->ScimEntries->save($newEntry, ['lockForUpdate' => true]);

        $this->assertNotFalse($result);
        $this->assertEmpty($newEntry->getErrors());
    }

    /**
     * Test that entries with NULL scim_name can coexist (multiple NULLs
     * are allowed by the unique index).
     */
    public function testScimEntriesTable_NullScimName_AllowsMultiple(): void
    {
        ScimEntryFactory::make([
            'scim_name' => null,
            'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
        ])->persist();

        $second = $this->ScimEntries->buildEntity([
            'foreign_key' => UuidFactory::uuid(),
            'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
            'scim_name' => null,
        ]);

        $result = $this->ScimEntries->save($second, ['lockForUpdate' => true]);

        $this->assertNotFalse($result);
        $this->assertEmpty($second->getErrors());
    }

    /**
     * Test that the DB unique constraint catches duplicates when application rules
     * are bypassed (simulates the TOCTOU race condition).
     *
     * This test verifies that even if two concurrent requests reach the application-level
     * uniqueness check (`isUniqueScimName`), the `SELECT ... FOR UPDATE` lock acquired
     * during that check prevents a concurrent insert from sneaking in.
     *
     * How it works:
     * 1. A listener is attached to the `Model.afterRules` event, which fires right after
     *    the application rules have passed but before the actual INSERT is committed.
     *    At this point, the first connection already holds a `FOR UPDATE` lock on the
     *    matching rows from `isUniqueScimName`.
     * 2. Inside this listener, a second (concurrent) database connection attempts to insert
     *    a row with the same `scim_name`. This simulates a race condition where another
     *    request slips in between the uniqueness check and the insert.
     * 3. Because the first connection holds a `FOR UPDATE` lock (acquired in
     *    `isUniqueScimName`), the concurrent insert is blocked by InnoDB's row-level
     *    locking. With `innodb_lock timeout` set to 0, it immediately throws a
     *    `QueryException` (lock wait timeout) instead of waiting.
     * 4. The test asserts that:
     *    - The concurrent insert was indeed blocked (`$timeoutLockoutTriggered` is true).
     *    - Only one row exists in the table after the save.
     *    - The surviving row is the one from the original save, not the concurrent insert.
     */
    public function testScimEntriesTable_DBConstraint_RejectsDuplicate(): void
    {
        $connection = ConnectionManager::get('default');
        // Create a second DB connection that simulates a concurrent request.
        // Setting innodb_lock_wait_timeout to 0 ensures the concurrent insert fails
        // immediately if it encounters a lock, rather than waiting.
        $config = $connection->config();
        $config['className'] = 'Cake\Database\Connection';
        if ($connection->getDriver() instanceof Mysql) {
            $config['init'] = ['SET SESSION innodb_lock_wait_timeout = 0;'];
        } else {
            $this->markTestSkipped('Skipping the tests in Postgres to reduce maintenance.');
        }
        ConnectionManager::setConfig('concurrent', $config);
        /** @var \Cake\Database\Connection $concurrentConnection */
        $concurrentConnection = ConnectionManager::get('concurrent');

        $scimName = 'race-test@passbolt.com';
        $concurrentUuid = UuidFactory::uuid();

        // Attach a listener that fires after application rules pass but before the INSERT is committed.
        // This is the window where a TOCTOU race condition can occur.
        $this->ScimEntries->getEventManager()->on('Model.afterRules', function ($event, $entity) use ($scimName, $concurrentUuid, $concurrentConnection) {
            $timeoutLockoutTriggered = false;
            try {
                // Attempt a raw INSERT on a separate connection, bypassing CakePHP's application rules.
                // This simulates a concurrent request that also passed the uniqueness check.
                $concurrentConnection->insert('scim_entries', [
                    'id' => $concurrentUuid,
                    'scim_name' => $scimName,
                    'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
                    'created' => FrozenTime::now(),
                    'modified' => FrozenTime::now(),
                ], ['created' => 'datetime', 'modified' => 'datetime']);
            } catch (QueryException) {
                // The concurrent insert was blocked by the FOR UPDATE lock held by isUniqueScimName — expected behavior.
                $timeoutLockoutTriggered = true;
            }
            // Assert the concurrent insert was rejected due to locking.
            $this->assertTrue($timeoutLockoutTriggered);
        });

        // Perform the "legitimate" save that should succeed.
        $duplicate = $this->ScimEntries->buildEntity([
            'foreign_key' => UuidFactory::uuid(),
            'foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
            'scim_name' => $scimName,
        ]);
        $this->ScimEntries->save($duplicate, ['lockForUpdate' => true]);

        // Verify only one entry exists and it's the one from the original save, not the concurrent attempt.
        $this->assertSame(1, ScimEntryFactory::count());
        $this->assertNotSame($concurrentUuid, ScimEntryFactory::firstOrFail()->get('id'));
    }
}
