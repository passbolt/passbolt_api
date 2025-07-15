<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class V410MakeScimFieldsNullable extends BaseMigration
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
        if ($this->isMigratingUp()) {
            $this->table('scim_entries')
                ->changeColumn('external_identifier', 'string', [
                    'default' => null,
                    'null' => true,
                ])
                ->changeColumn('scim_name', 'string', [
                    'default' => null,
                    'null' => true,
                ])
                ->update();
        }

        $this->table('scim_entries')
            ->addIndex('created')
            ->update();
    }
}
