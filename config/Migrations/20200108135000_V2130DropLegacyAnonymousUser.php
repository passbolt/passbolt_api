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
use Cake\Validation\Validation;
use Cake\Http\Exception\InternalErrorException;

class V2130DropLegacyAnonymousUser extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        // Some instances coming from v1 still have this unused user and should be dropped
        $user = $this->fetchRow("SELECT id from users where username='anonymous@passbolt.com'");
        if(isset($user['id'])) {
            $id = $user['id'];
            if (!Validation::uuid($id)) {
                throw new InternalErrorException('The anonymous user id should be a UUID');
            }
            $this->execute("DELETE from users where id='$id'");
            $this->execute("DELETE from gpgkeys where user_id='$id'");
            $this->execute("DELETE from profiles where user_id='$id'");
            $this->execute("DELETE from secrets where user_id='$id'");
            $this->execute("DELETE from authentication_tokens where user_id='$id'");
        }
    }
}
