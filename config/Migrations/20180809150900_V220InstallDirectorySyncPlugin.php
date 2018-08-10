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
 * @since         2.2.0
 */

use Migrations\Migrations;
use App\Utility\PluginMigration;

class V220InstallDirectorySyncPlugin extends PluginMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        parent::up();
        $migrations = new Migrations([
            'connection' => 'default',
            'plugin' => 'Passbolt/DirectorySync',
        ]);
        $migrations->migrate();
    }
}