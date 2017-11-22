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

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class UsersDataTask extends DataTask
{
    public $entityName = 'Users';

    /**
     * Get user data
     *
     * @return array
     */
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
        $users[] = [
            'id' => UuidFactory::uuid('user.id.ada'),
            'username' => 'ada@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created' => date('Y-m-d H:i:s', strtotime('-2 months')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 months')),
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.betty'),
            'username' => 'betty@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created' => date('Y-m-d H:i:s', strtotime('-2 weeks')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 weeks')),
            'created_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.carol'),
            'username' => 'carol@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 days')),
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.dame'),
            'username' => 'dame@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 hours')),
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.edith'),
            'username' => 'edith@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1,
            'deleted' => 0,
            'created' => date('Y-m-d H:i:s', strtotime('-2 minutes')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 minutes')),
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.frances'),
            'username' => 'frances@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.grace'),
            'username' => 'grace@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.hedy'),
            'username' => 'hedy@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.irene'),
            'username' => 'irene@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.jean'),
            'username' => 'jean@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.kathleen'),
            'username' => 'kathleen@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.lynne'),
            'username' => 'lynne@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.marlyn'),
            'username' => 'marlyn@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.nancy'),
            'username' => 'nancy@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.orna'),
            'username' => 'orna@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.ping'),
            'username' => 'ping@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.ruth'),
            'username' => 'ruth@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 0,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.sofia'),
            'username' => 'sofia@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 1,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.thelma'),
            'username' => 'thelma@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.ursula'),
            'username' => 'ursula@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];
        $users[] = [
            'id' => UuidFactory::uuid('user.id.wang'),
            'username' => 'wang@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'active' => 1,
            'deleted' => 0,
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin')
        ];

        return $users;
    }
}
