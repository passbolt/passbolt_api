<?php
use Migrations\AbstractMigration;

class V200ActiveMustBeBoolean extends AbstractMigration
{

    public function up()
    {

        $this->table('authentication_tokens')
            ->changeColumn('active', 'boolean', [
                'default' => '1',
                'limit' => null,
                'null' => false,
            ])
            ->update();

        $this->table('users')
            ->changeColumn('active', 'boolean', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->update();
    }

    public function down()
    {

        $this->table('authentication_tokens')
            ->changeColumn('active', 'integer', [
                'default' => '1',
                'length' => 1,
                'null' => false,
            ])
            ->update();

        $this->table('users')
            ->changeColumn('active', 'integer', [
                'default' => '0',
                'length' => 1,
                'null' => false,
            ])
            ->update();
    }
}

