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

class V270AddSecretsHistoryTable extends AbstractMigration
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
        $this->table('secrets_history', ['id' => false, 'primary_key' => ['id'], 'collation' => $collation])
             ->addColumn('id', 'uuid', [
                 'default' => null,
                 'null' => false,
             ])
            ->addColumn('user_id', 'uuid', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('resource_id', 'uuid', [
                'default' => null,
                'null' => false,
            ])
             ->addIndex([
                 'id',
             ])
             ->addIndex([
                'user_id',
                'resource_id',
             ])
             ->create();
    }
}
// @codingStandardsIgnoreEnd