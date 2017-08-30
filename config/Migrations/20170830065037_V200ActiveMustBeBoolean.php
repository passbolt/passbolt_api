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
namespace App\config\Migrations;

use Migrations\AbstractMigration;

class V200ActiveMustBeBoolean extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
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
}
