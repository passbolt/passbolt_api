<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V410ImproveFoldersRelationsIndexesShareFoldersPerformance extends AbstractMigration
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
            /*
             * Improves the performance of the queries which retrieve:
             * - all the folders which children and also parents. It is used to trigger the cycle detections algorithm.
             * - all the folders relations used to perform cycle detections.
             */
            ->addIndex([
                'foreign_model',
                'folder_parent_id',
                'user_id',
            ])
            ->addIndex([
                'foreign_model',
                'folder_parent_id',
                'foreign_id',
                'user_id',
            ])
            ->removeIndex(['foreign_model'])
            ->removeIndex(['user_id'])
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
