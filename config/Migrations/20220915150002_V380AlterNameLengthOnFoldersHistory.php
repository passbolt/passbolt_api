<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V380AlterNameLengthOnFoldersHistory extends AbstractMigration
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
        $table = $this->table('folders_history');
        $table->changeColumn('name', 'string', [
            'limit' => 256,
        ]);
        $table->update();
    }
}
