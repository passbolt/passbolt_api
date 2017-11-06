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
namespace App\Test\Lib\Model;

trait GpgkeysModelTrait
{
    /**
     * Asserts that an object has all the attributes a gpgkey should have.
     *
     * @param object $gpgkey
     */
    protected function assertGpgkeyAttributes($gpgkey)
    {
        $attributes = [
            'id', 'user_id', 'armored_key', 'bits', 'uid', 'key_id',
            'fingerprint', 'type', 'expires', 'key_created', 'deleted', 'created', 'modified'
        ];
        $this->assertObjectHasAttributes($attributes, $gpgkey);
    }
}
