<?php
declare(strict_types=1);

use App\Utility\Migrations\MigrationsAwareTrait;
use Migrations\AbstractMigration;

/**
 * The migration is a split of the original V340MigrateASCIIFieldsEncodingPro.
 *
 *  It aims to improve the performance by using the correct encoding and collation for columns only containing
 * ASCII characters.
 */
class V3120MigrateASCIIFieldsEncodingDirectorySync extends AbstractMigration
{
    use MigrationsAwareTrait;

    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {
        // If the migration V340MigrateASCIIFieldsEncodingPro was already played prior to the migration split and the
        // directory sync plugin tables columns encoding and collation were already updated.
        if ($this->isMigrationAlreadyRun('V340MigrateASCIIFieldsEncodingPro')) {
            \Cake\Log\Log::info('Directory sync plugin tables columns encoding and collation already updated.');
            return;
        }

        $this->table('directory_entries')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('foreign_model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('foreign_key', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('directory_ignore')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('foreign_model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('directory_relations')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('parent_key', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('child_key', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('directory_reports')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('parent_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('status', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('directory_reports_items')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('report_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('status', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();
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
