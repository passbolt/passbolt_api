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
namespace PassboltDummyData\Shell\Task;

use App\Utility\Common;
use Cake\Core\Configure;
use PassboltData\Shell\Task\DataTask;

class LargeUsersDataTask extends DataTask
{
    public $entityName = 'Users';
    public $fixtureName = 'LargeUsers';

    protected function _getData()
    {
        $users[] = [
            'id' => Common::uuid('user.id.admin'),
            'username' => 'admin@passbolt.com',
            'role_id' => Common::uuid('role.id.admin'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => Common::uuid('user.id.anonymous'),
            'username' => 'anonymous@passbolt.com',
            'role_id' => Common::uuid('role.id.anonymous'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        for ($i = 0; $i < Configure::read('PassboltDummyData.scenarios.large.install.count'); $i++) {
            $users[] = [
                'id' => Common::uuid('user.id.user_' . $i),
                'username' => 'user_' . $i . '@passbolt.com',
                'role_id' => Common::uuid('role.id.user'),
                'active' => 1,
                'deleted' => 0,
                'created_by' => Common::uuid('user.id.admin'),
                'modified_by' => Common::uuid('user.id.admin')
            ];
        }

        return $users;
    }
}
