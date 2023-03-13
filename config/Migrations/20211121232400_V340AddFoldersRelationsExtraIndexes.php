<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V340AddFoldersRelationsExtraIndexes extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {
        $this->table('folders_relations')
            ->addIndex([
                'foreign_model',
                'foreign_id',
                'user_id',
            ])
            ->addIndex([
                'user_id',
                'foreign_id',
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
