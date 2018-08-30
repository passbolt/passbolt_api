<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
// @codingStandardsIgnoreStart
use Migrations\AbstractMigration;

class V220DirectorySyncReportMigration extends AbstractMigration
{

    protected function init() {
        parent::init();
    }

    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('directory_reports', [
                'id' => false, 'primary_key' => ['id'],
                'collation' => 'utf8mb4_unicode_ci']
            )
            ->addColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('parent_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
            ])
            ->addColumn('status', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
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
            ->addIndex([
                'id',
                'parent_id'
            ])
            ->create();

        $this->table('directory_reports_items', [
                'id' => false, 'primary_key' => ['id'],
                'collation' => 'utf8mb4_unicode_ci']
             )
            ->addColumn('report_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
             ->addColumn('id', 'char', [
                 'default' => null,
                 'limit' => 36,
                 'null' => false,
             ])
             ->addColumn('status', 'string', [
                 'default' => null,
                 'limit' => 36,
                 'null' => false,
             ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('action', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'null' => true,
            ])
             ->addColumn('created', 'datetime', [
                 'default' => null,
                 'limit' => null,
                 'null' => false,
             ])
             ->addIndex([
                 'id',
                 'status',
                 'model',
                 'action'
             ])
             ->create();
    }
}
// @codingStandardsIgnoreEnd