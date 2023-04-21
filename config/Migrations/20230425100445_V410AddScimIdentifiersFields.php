<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V410AddScimIdentifiersFields extends AbstractMigration
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
            ->table('scim_entries', [
                'id' => false,
                'primary_key' => ['id'],
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('foreign_key', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('foreign_model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('external_identifier', 'string', [
                'default' => null,
                'limit' => 256,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('scim_name', 'string', [
                'default' => null,
                'limit' => 256,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(['foreign_model'])
            ->addIndex(['foreign_key'])
            ->addIndex(['external_identifier'])
            ->create();
    }
}
