<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.12.1
 */
namespace Passbolt\Tags\Test\TestCase\Model\Table\Tags;

use Cake\Core\Configure;
use Cake\Database\Driver\Mysql;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Migrations\Migrations;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Model\Table\TagsTableBackupAwareTrait
 */
class TagsTableBackupTest extends TestCase
{
    use TruncateDirtyTables;

    public function testTagsTableBackup_Backup_Disabled()
    {
        Configure::write('passbolt.plugins.tags.backupMode', false);

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $this->assertSame('tags', $Tags->getTable());
        $this->assertSame('resources_tags', $Tags->ResourcesTags->getTable());
    }

    public function testTagsTableBackup_Backup_Enabled_Backup_Tables_Do_Not_Exist()
    {
        Configure::write('passbolt.plugins.tags.backupMode', true);

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        $this->assertSame('tags', $Tags->getTable());
        $this->assertSame('resources_tags', $Tags->ResourcesTags->getTable());
    }

    public function testTagsTableBackup_Backup_Enabled_Create_And_Drop_Tables()
    {
        /** @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get('test');
        if (!($connection->getDriver() instanceof Mysql)) {
            $this->markTestSkipped('Backup of tags table is for cloud only, this MySQL only');
        }

        Configure::write('passbolt.plugins.tags.backupMode', false);

        $tags = TagFactory::make(2)->persist();
        $resourcesTags = ResourcesTagFactory::make(2)->persist();

        // create backup tables
        (new Migrations())->migrate([
            'source' => '../plugins/PassboltEe/Tags/tests/Fixture/BackupMigrations/',
            'connection' => 'test',
        ]);

        // Assert that backup tables and indexes exist
        $query = $connection->execute('SHOW TABLES LIKE "backup_%"');
        $this->assertSame(2, $query->rowCount());
        $query = $connection->execute('SHOW INDEX FROM backup_tags');
        $this->assertSame(3, $query->rowCount());
        $query = $connection->execute('SHOW INDEX FROM backup_resources_tags');
        $this->assertSame(4, $query->rowCount());

        // Now that the backup tables are created, activate the backup mode
        Configure::write('passbolt.plugins.tags.backupMode', true);

        /** @var \Passbolt\Tags\Model\Table\TagsTable $Tags */
        $Tags = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        /** @var \Passbolt\Tags\Model\Table\ResourcesTagsTable $ResourcesTags */
        $ResourcesTags = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');

        $this->assertSame('backup_tags', $Tags->getTable());
        $this->assertSame('backup_resources_tags', $ResourcesTags->getTable());

        $this->assertSame(count($tags), $Tags->find()->all()->count());
        $this->assertSame(count($resourcesTags), $ResourcesTags->find()->all()->count());

        foreach ($tags as $tag) {
            $copiedTag = $Tags->get($tag->id);
            $this->assertSame($tag->slug, $copiedTag->slug);
            $this->assertFalse($copiedTag->is_shared);
            $this->assertSame($tag->get('metadata'), $copiedTag->get('metadata'));
            $this->assertSame($tag->get('metadata_key_id'), $copiedTag->get('metadata_key_id'));
            $this->assertSame($tag->get('metadata_key_type'), $copiedTag->get('metadata_key_type'));
        }

        foreach ($resourcesTags as $resourcesTag) {
            $copiedResourcesTag = $ResourcesTags->get($resourcesTag->id);
            $this->assertSame($resourcesTag->resource_id, $copiedResourcesTag->resource_id);
            $this->assertSame($resourcesTag->tag_id, $copiedResourcesTag->tag_id);
            $this->assertSame($resourcesTag->user_id, $copiedResourcesTag->user_id);
            $this->assertEquals($resourcesTag->created->toIso8601String(), $copiedResourcesTag->created->toIso8601String());
        }

        // drop backup tables
        (new Migrations())->migrate([
            'source' => '../plugins/PassboltEe/Tags/tests/Fixture/DropBackupMigrations/',
            'connection' => 'test',
        ]);

        $query = $connection->execute('SHOW TABLES LIKE "backup_%";');
        $this->assertSame(0, $query->rowCount());
        $this->assertSame(2, $connection->selectQuery('*')->from('tags')->rowCountAndClose());
        $this->assertSame(2, $connection->selectQuery('*')->from('resources_tags')->rowCountAndClose());
    }
}
