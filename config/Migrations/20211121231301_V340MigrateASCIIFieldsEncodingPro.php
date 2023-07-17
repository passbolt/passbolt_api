<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * The migration was split into multiple migrations:
 * - V3120MigrateASCIIFieldsEncodingFolders
 * - V3120MigrateASCIIFieldsEncodingDirectorySync
 * - V3120MigrateASCIIFieldsEncodingTags
 *
 * It aims to improve the performance by using the correct encoding and collation for columns only containing
 * ASCII characters.
 */
class V340MigrateASCIIFieldsEncodingPro extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up()
    {
//        $this->table('directory_entries')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('foreign_model', 'string', [
//                'default' => null,
//                'limit' => 36,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('foreign_key', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => true,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('directory_ignore')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('foreign_model', 'string', [
//                'default' => null,
//                'limit' => 36,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('directory_relations')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('parent_key', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => true,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('child_key', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => true,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('directory_reports')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('parent_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => true,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('status', 'string', [
//                'default' => null,
//                'limit' => 36,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('directory_reports_items')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('report_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('status', 'string', [
//                'default' => null,
//                'limit' => 36,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('model', 'string', [
//                'default' => null,
//                'limit' => 36,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('folders')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('created_by', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('modified_by', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('folders_history')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('folder_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('folders_relations')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('foreign_model', 'string', [
//                'default' => null,
//                'limit' => 30,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('foreign_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('user_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('folder_parent_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => true,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('folders_relations_history')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('foreign_model', 'string', [
//                'default' => null,
//                'limit' => 30,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('foreign_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('user_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('folder_parent_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => true,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('resources_tags')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('resource_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('tag_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->changeColumn('user_id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => true,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
//
//        $this->table('tags')
//            ->changeColumn('id', 'uuid', [
//                'default' => null,
//                'limit' => null,
//                'null' => false,
//                'encoding' => 'ascii',
//                'collation' => 'ascii_general_ci'
//            ])
//            ->save();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down()
    {
    }
}
