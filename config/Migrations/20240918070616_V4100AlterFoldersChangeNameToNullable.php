<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V4100AlterFoldersChangeNameToNullable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('folders');
        $table->changeColumn('name', 'string', [
            'null' => true,
            'limit' => 256,
        ]);
        $table->update();
    }
}
