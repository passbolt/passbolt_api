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
 * @since         2.11.0
 */
use Migrations\AbstractMigration;

class V2110ExtendKeyIdSizeField extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('gpgkeys')
            ->changeColumn('key_id', 'string', ['limit' => 16])
            ->update();

        // Migrate existing keys if using short id
        $results = $this->query("SELECT id,key_id,fingerprint FROM gpgkeys");
        $keys = $results->fetchAll();
        foreach($keys as $key) {
            if (strlen($key['key_id']) < 16) {
                $keyId = substr($key['fingerprint'], -16);
                $id = $key['id'];
                $this->execute("UPDATE gpgkeys SET key_id='$keyId' WHERE id='$id'");
            }
        }
    }
}
