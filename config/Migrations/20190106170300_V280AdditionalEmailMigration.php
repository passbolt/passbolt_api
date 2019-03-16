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
 * @since         2.7.0
 */

use Migrations\AbstractMigration;
use Migrations\Migrations;

class V280AdditionalEmailMigration extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        // Add error message
        $table = $this->table('email_queue');
        $table->addColumn('error', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();

        $this->table('email_queue_phinxlog')->drop()->save();
    }

    /**
     * Down
     *
     * @return void
     */
    public function down()
    {
        return;
    }
}
