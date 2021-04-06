<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
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

class V270AddPermissionsHistoryTable extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {   
        $encoding= "utf8mb4";
        $collation = "utf8mb4_unicode_ci";
        switch($this->getAdapter()->getOptions()["adapter"]) {
            case "pgsql": {
                $encoding = "utf8";
                $collation = "utf8_unicode_ci";
                break;
                }
           default:
     	       $encoding= "utf8mb4";
               $collation = "utf8mb4_unicode_ci";
        }
        $this->table('permissions_history', ['id' => false, 'primary_key' => ['id'], 'collation' => $collation])
             ->addColumn('id', 'uuid', [
                 'default' => null,
                 'null' => false,
             ])
             ->addColumn('aco', 'string', [
                 'default' => null,
                 'limit' => 30,
                 'null' => false,
             ])
             ->addColumn('aco_foreign_key', 'uuid', [
                 'default' => null,
                 'null' => false,
             ])
             ->addColumn('aro', 'string', [
                 'default' => null,
                 'limit' => 30,
                 'null' => false,
             ])
             ->addColumn('aro_foreign_key', 'uuid', [
                 'default' => null,
                 'null' => true,
             ])
             ->addColumn('type', 'integer', [
                 'default' => null,
                 'limit' => 11,
                 'null' => false,
             ])
             ->addIndex(
                 [
                     'aco_foreign_key',
                 ]
             )
             ->addIndex(
                 [
                     'aro_foreign_key',
                 ]
             )
             ->addIndex(
                 [
                     'aco',
                     'aro',
                 ]
             )
             ->addIndex(
                 [
                     'type',
                 ]
             )
             ->create();
    }
}
// @codingStandardsIgnoreEnd