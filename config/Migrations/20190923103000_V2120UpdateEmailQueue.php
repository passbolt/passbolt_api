<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.12.0
 */
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class V2120UpdateEmailQueue extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('email_queue')
            ->changeColumn('template', 'string', [
                'limit' => 100,
            ])
            ->changeColumn('template_vars', 'text', [
                'limit' => MysqlAdapter::TEXT_LONG,
                'default' => null,
                'null' => false,
            ])
            ->update();
    }
}
