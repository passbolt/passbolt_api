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
namespace Passbolt\DirectorySync\Test\TestCase\Model\Table;

use App\Model\Entity\User;
use App\Utility\UuidFactory;
use Cake\Database\Exception\QueryException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validation;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;
use Passbolt\DirectorySync\Model\Table\DirectoryEntriesTable;
use Passbolt\DirectorySync\Test\Factory\DirectoryEntryFactory;
use Passbolt\DirectorySync\Utility\Alias;
use Psr\Log\AbstractLogger;
use Psr\Log\NullLogger;
use Stringable;

/**
 * @covers \Passbolt\DirectorySync\Model\Table\DirectoryEntriesTable
 */
class DirectoryEntriesTableTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * @var \Passbolt\DirectorySync\Model\Table\DirectoryEntriesTable
     */
    protected $DirectoryEntries;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->DirectoryEntries = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryEntries');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->DirectoryEntries);
        parent::tearDown();
    }

    public function testDirectoryEntriesTable_updateOrCreate_WhenEntryDoesNotExist_CreatesNewEntry(): void
    {
        $data = [
            'id' => UuidFactory::uuid(), // not present — triggers RecordNotFoundException
            'directory_name' => 'cn=Ada Lovelace,dc=passbolt,dc=com',
        ];

        $result = $this->DirectoryEntries->updateOrCreate($data, Alias::MODEL_USERS);

        $this->assertInstanceOf(DirectoryEntry::class, $result);
        $this->assertTrue(Validation::uuid($result->id));
        $this->assertSame($data['directory_name'], $result->directory_name);
        $this->assertSame(Alias::MODEL_USERS, $result->foreign_model);
        $this->assertNull($result->foreign_key);
        // Assert database entry
        $persisted = DirectoryEntryFactory::find()->where(['id' => $result->id])->firstOrFail();
        $this->assertSame($data['directory_name'], $persisted->directory_name);
    }

    public function testDirectoryEntriesTable_updateOrCreate_WhenNameUnchanged_ReturnsEntityWithoutUpdate(): void
    {
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry */
        $entry = DirectoryEntryFactory::make([
            'directory_name' => 'cn=Ada,dc=passbolt,dc=com',
            'foreign_model' => Alias::MODEL_USERS,
        ])->withUser()->persist();
        $data = [
            'id' => $entry->id,
            'directory_name' => 'cn=Ada,dc=passbolt,dc=com',
        ];
        $directoryEntry = DirectoryEntryFactory::find()->where(['id' => $entry->id])->firstOrFail();
        $modifiedBefore = $directoryEntry->modified;

        $result = $this->DirectoryEntries->updateOrCreate($data, Alias::MODEL_USERS);

        $this->assertInstanceOf(DirectoryEntry::class, $result);
        $this->assertSame($entry->id, $result->id);
        $this->assertSame('cn=Ada,dc=passbolt,dc=com', $result->directory_name);
        $this->assertEquals($modifiedBefore, $result->modified);
    }

    public function testDirectoryEntriesTable_updateOrCreate_WhenNameChanged_UpdatesDbAndReturnsEntityWithAssociation(): void
    {
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry */
        $entry = DirectoryEntryFactory::make([
            'directory_name' => 'cn=OldName,dc=passbolt,dc=com',
            'foreign_model' => Alias::MODEL_USERS,
        ])->withUser()->persist();
        $newName = 'cn=NewName,dc=passbolt,dc=com';
        $data = [
            'id' => $entry->id,
            'directory_name' => $newName,
        ];

        $result = $this->DirectoryEntries->updateOrCreate($data, Alias::MODEL_USERS);

        $this->assertInstanceOf(DirectoryEntry::class, $result);
        $this->assertSame($newName, $result->directory_name);
        $associated = $result->getAssociatedEntity();
        $this->assertNotNull($associated);
        $this->assertInstanceOf(User::class, $associated);
        $this->assertSame($entry->foreign_key, $associated->id);
        // Assert database entry
        $persisted = DirectoryEntryFactory::find()->where(['id' => $entry->id])->firstOrFail();
        $this->assertSame($newName, $persisted->directory_name);
    }

    public function testDirectoryEntriesTable_updateOrCreate_WhenDirectoryNameIsNotString_SkipsUpdate(): void
    {
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry */
        $entry = DirectoryEntryFactory::make([
            'directory_name' => 'cn=Ada,dc=passbolt,dc=com',
            'foreign_model' => Alias::MODEL_USERS,
        ])->withUser()->persist();
        $data = [
            'id' => $entry->id,
            'directory_name' => null, // not string, set `null` for testing
        ];

        $result = $this->DirectoryEntries->updateOrCreate($data, Alias::MODEL_USERS);

        $this->assertInstanceOf(DirectoryEntry::class, $result);
        $persisted = DirectoryEntryFactory::find()->where(['id' => $entry->id])->firstOrFail();
        $this->assertSame('cn=Ada,dc=passbolt,dc=com', $persisted->directory_name);
    }

    public function testDirectoryEntriesTable_updateOrCreate_WhenNameExceedsMaxLength_TruncatesAtCharacterBoundary(): void
    {
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry */
        $entry = DirectoryEntryFactory::make([
            'directory_name' => 'cn=Short,dc=passbolt,dc=com',
            'foreign_model' => Alias::MODEL_USERS,
        ])->withUser()->persist();
        /**
         * The directory_name exceeds DN_MAX_LENGTH in multibyte characters (not bytes).
         * Must be truncated to 254 characters at a character boundary via mb_substr, not substr.
         * 'é' is a 2-byte UTF-8 character; 256 of them = 256 chars but 512 bytes.
         */
        $longName = str_repeat('é', 256);
        $data = [
            'id' => $entry->id,
            'directory_name' => $longName,
        ];

        $result = $this->DirectoryEntries->updateOrCreate($data, Alias::MODEL_USERS);

        $this->assertInstanceOf(DirectoryEntry::class, $result);
        $this->assertSame(DirectoryEntriesTable::DN_MAX_LENGTH - 1, mb_strlen($result->directory_name, 'UTF-8'));
        // Assert db entries
        $persisted = DirectoryEntryFactory::find()->where(['id' => $entry->id])->firstOrFail();
        $this->assertSame(DirectoryEntriesTable::DN_MAX_LENGTH - 1, mb_strlen($persisted->directory_name, 'UTF-8'));
        $this->assertSame(mb_substr($longName, 0, DirectoryEntriesTable::DN_MAX_LENGTH - 1, 'UTF-8'), $persisted->directory_name);
    }

    public function testDirectoryEntriesTable_updateOrCreate_WhenEntryIsOrphan_UpdatesNameAndReturnsNullAssociation(): void
    {
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry */
        $entry = DirectoryEntryFactory::make([
            'directory_name' => 'cn=Pending,dc=passbolt,dc=com',
            'foreign_model' => Alias::MODEL_USERS,
            'foreign_key' => null, // users table link missing
        ])->persist();
        $newName = 'cn=UpdatedPending,dc=passbolt,dc=com';
        $data = [
            'id' => $entry->id,
            'directory_name' => $newName,
        ];

        $result = $this->DirectoryEntries->updateOrCreate($data, Alias::MODEL_USERS);

        $this->assertInstanceOf(DirectoryEntry::class, $result);
        $this->assertSame($newName, $result->directory_name);
        $this->assertNull($result->getAssociatedEntity());
        $persisted = DirectoryEntryFactory::find()->where(['id' => $entry->id])->firstOrFail();
        $this->assertSame($newName, $persisted->directory_name);
    }

    /**
     * Scenario:
     * The SELECT inside the transaction must include a FOR UPDATE clause to acquire a row-level
     * lock, preventing the TOCTOU race condition in concurrent sync processes.
     * Verified by injecting a custom PSR-3 logger onto the driver and inspecting the executed SQL.
     */
    public function testDirectoryEntriesTable_updateOrCreate_WhenEntryExists_ExecutesSelectForUpdateQuery(): void
    {
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry */
        $entry = DirectoryEntryFactory::make([
            'directory_name' => 'cn=Old,dc=passbolt,dc=com',
            'foreign_model' => Alias::MODEL_USERS,
        ])->withUser()->persist();
        $driver = $this->DirectoryEntries->getConnection()->getDriver();
        $originalLogger = $driver->getLogger();
        $executedSql = [];
        $driver->setLogger(new class ($executedSql) extends AbstractLogger {
            public function __construct(private array &$sql)
            {
            }

            public function log(mixed $level, Stringable|string $message, array $context = []): void
            {
                $this->sql[] = (string)$message;
            }
        });

        try {
            $this->DirectoryEntries->updateOrCreate(
                ['id' => $entry->id, 'directory_name' => 'cn=New,dc=passbolt,dc=com'],
                Alias::MODEL_USERS
            );
        } finally {
            // reset original logger
            $driver->setLogger($originalLogger ?? new NullLogger());
        }

        $forUpdateQueries = array_filter($executedSql, fn (string $q) => str_contains($q, 'FOR UPDATE') !== false);
        $this->assertNotEmpty($forUpdateQueries, 'Expected at least one SELECT ... FOR UPDATE query to be executed.');
    }

    /**
     * Scenario: save() fails at the database level due to an invalid UTF-8 byte sequence.
     * MySQL rejects the value, throwing a QueryException. The transactional() wrapper catches
     * it, rolls back, and re-throws — leaving the DB unchanged.
     *
     * This test verifies transaction isolation: a mid-closure save failure does not update the DB.
     *
     * NOTE: CakePHP's utf8() validation rule does not catch bare continuation bytes like "\x80";
     * MySQL is the gatekeeper here. The critical code-review finding remains that save() returning false
     * would cause a TypeError in the : DirectoryEntry typed closure — that scenario is not easily
     * reproduced via input alone, as it requires a concurrent delete or lock failure.
     */
    public function testDirectoryEntriesTable_updateOrCreate_WhenSaveThrowsExceptionInsideTransaction_DbIsUnchanged(): void
    {
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $entry */
        $entry = DirectoryEntryFactory::make([
            'directory_name' => 'cn=Original,dc=passbolt,dc=com',
            'foreign_model' => Alias::MODEL_USERS,
        ])->withUser()->persist();
        $data = [
            'id' => $entry->id,
            'directory_name' => "\x80", // invalid UTF-8 — MySQL rejects it, QueryException thrown inside transaction
        ];

        try {
            $this->DirectoryEntries->updateOrCreate($data, Alias::MODEL_USERS);
            $this->fail('Expected QueryException was not thrown.');
        } catch (QueryException) {
            // Expected: MySQL rejected the invalid UTF-8 byte; transaction was rolled back.
        }

        $persisted = DirectoryEntryFactory::find()->where(['id' => $entry->id])->firstOrFail();
        $this->assertSame('cn=Original,dc=passbolt,dc=com', $persisted->directory_name);
    }
}
