<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V340MigrateASCIIFieldsEncoding extends AbstractMigration
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
        $this->table('account_settings')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('property_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('action_logs')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('action_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('actions')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('authentication_tokens')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('token', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('type', 'string', [
                'default' => null,
                'limit' => 16,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('avatars')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('profile_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('comments')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('parent_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('foreign_key', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('foreign_model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('created_by', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('modified_by', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('entities_history')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('action_log_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('foreign_model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('foreign_key', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('crud', 'char', [
                'default' => null,
                'limit' => 1,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('favorites')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('foreign_key', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('foreign_model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('gpgkeys')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])

            ->save();

        $this->table('groups')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('created_by', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('modified_by', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('groups_users')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('group_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('organization_settings')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('property_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('created_by', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('modified_by', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('permissions')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('aco', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('aco_foreign_key', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('aro', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('aro_foreign_key', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('permissions_history')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('aco', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('aco_foreign_key', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('aro', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('aro_foreign_key', 'string', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('profiles')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('resource_types')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('resources')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('created_by', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('modified_by', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('resource_type_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('roles')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('secret_accesses')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('resource_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('secret_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('secrets')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('resource_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('secrets_history')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('resource_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('transfers')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('authentication_token_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('status', 'string', [
                'default' => null,
                'limit' => 16,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('hash', 'char', [
                'default' => null,
                'limit' => 128,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('user_agents')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->save();

        $this->table('users')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->changeColumn('role_id', 'uuid', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
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
