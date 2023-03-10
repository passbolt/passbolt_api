<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V372ImproveFoldersRelationsIndexesAddItemsToUserTreePerformance extends AbstractMigration
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
             * Will be replaced by the following index: "foreign_id", "folder_parent_id", "created"
             */
            ->removeIndex([
                'foreign_id',
                'folder_parent_id',
            ])
            /*
             * Single index decreases performance as it might be picked by the mysql optimizer instead of another
             * combined index which could do a better job.
             */
            ->removeIndex([
                'foreign_id'
            ])
            /*
             * Improves the performance of the queries which retrieve information relative to the potential folders
             * relations changes to apply to a user tree while adding new items to this same user tree.
             */
            ->addIndex([
                'foreign_id',
                'folder_parent_id',
                'created',
            ])
            ->save();

        /*
         * Index which create counter performance with the query which retrieves the potential folder parents of items
         * newly added to a user tree.
         */
        $this->table('permissions')
            ->removeIndex([
                'aco',
                'aro'
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
