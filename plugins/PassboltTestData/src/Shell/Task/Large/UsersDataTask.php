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
namespace PassboltTestData\Shell\Task\Large;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use PassboltTestData\Lib\DataTask;

class UsersDataTask extends DataTask
{
    public $entityName = 'Users';
    public $fixtureName = 'LargeUsers';

    protected function _getData()
    {
        $users[] = [
            'id' => UuidFactory::uuid('user.id.admin'),
            'username' => 'admin@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.admin'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.anonymous'),
            'username' => 'anonymous@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.anonymous'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        for ($i = 0; $i < Configure::read('PassboltTestData.scenarios.large.install.count'); $i++) {
            $users[] = [
                'id' => UuidFactory::uuid('user.id.user_' . $i),
                'username' => 'user_' . $i . '@passbolt.com',
                'role_id' => UuidFactory::uuid('role.id.user'),
                'active' => 1,
                'deleted' => 0,
                'created_by' => UuidFactory::uuid('user.id.admin'),
                'modified_by' => UuidFactory::uuid('user.id.admin')
            ];
        }

        return $users;
    }
}
