<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class V410AddDeletedFieldToScrimEntries extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {

        $this->table('scim_entries')
            ->addColumn('deleted', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'after' => 'scim_name',
            ])
            ->update();
    }
}
