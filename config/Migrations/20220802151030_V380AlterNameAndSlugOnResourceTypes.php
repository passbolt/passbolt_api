<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V380AlterNameAndSlugOnResourceTypes extends AbstractMigration
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
        $this
            ->table('resource_types')
            ->changeColumn('name', 'string', ['length' => 64])
            ->changeColumn('slug', 'string', ['length' => 64])
            ->update();
    }
}
