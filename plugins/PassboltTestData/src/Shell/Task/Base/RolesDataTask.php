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
namespace PassboltTestData\Shell\Task\Base;

use App\Utility\Common;
use PassboltTestData\Lib\DataTask;

class RolesDataTask extends DataTask
{
    public $entityName = 'Roles';

    protected function _getData()
    {
        $roles[] = [
            'id' => Common::uuid('role.id.anonymous'),
            'name' => 'guest',
            'description' => 'Non logged in user',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25'
        ];
        $roles[] = [
            'id' => Common::uuid('role.id.user'),
            'name' => 'user',
            'description' => 'Logged in user',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25',
        ];
        $roles[] = [
            'id' => Common::uuid('role.id.admin'),
            'name' => 'admin',
            'description' => 'Organization administrator',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25',
        ];
        $roles[] = [
            'id' => Common::uuid('role.id.root'),
            'name' => 'root',
            'description' => 'Super Administrator',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25',
        ];
        return $roles;
    }
}
