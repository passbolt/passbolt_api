<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V3120DropActionLogsDuplicateIndexes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {

        $this->table('action_logs')
            ->removeIndex([
                'user_id', 'action_id', 'status'
            ])
            ->removeIndex([
                'action_id',
            ])
            ->save();
    }
}
