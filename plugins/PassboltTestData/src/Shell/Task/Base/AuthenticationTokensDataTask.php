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

class AuthenticationTokensDataTask extends DataTask
{
    public $entityName = 'AuthenticationTokens';

    protected function _getData()
    {
        $tokens[] = [
            'id' => UuidFactory::uuid('token.id.expired'),
            'user_id' => UuidFactory::uuid('user.id.ruth'),
            'token' => UuidFactory::uuid(),
            'active' => 1,
            'created' => date('Y-m-d H:i:s', strtotime('-10 days')),
            'modified' => date('Y-m-d H:i:s', strtotime('-10 days')),
        ];
        $tokens[] = [
            'id' => UuidFactory::uuid('token.id.expired_inactive'),
            'user_id' => UuidFactory::uuid('user.id.ruth'),
            'token' => UuidFactory::uuid(),
            'active' => 0,
            'created' => date('Y-m-d H:i:s', strtotime('-10 days')),
            'modified' => date('Y-m-d H:i:s', strtotime('-10 days')),
        ];
        $tokens[] = [
            'id' => UuidFactory::uuid('token.id.inactive'),
            'user_id' => UuidFactory::uuid('user.id.ruth'),
            'token' => UuidFactory::uuid(),
            'active' => 0,
        ];
        $tokens[] = [
            'id' => UuidFactory::uuid('token.id.ruth'),
            'user_id' => UuidFactory::uuid('user.id.ruth'),
            'token' => UuidFactory::uuid(),
            'active' => 1,
        ];
        $tokens[] = [
            'id' => UuidFactory::uuid('token.id.ada'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'token' => UuidFactory::uuid(),
            'active' => 1,
        ];
        $tokens[] = [
            'id' => UuidFactory::uuid('token.id.sophia'),
            'user_id' => UuidFactory::uuid('user.id.sophia'),
            'token' => UuidFactory::uuid(),
            'active' => 1,
        ];

        return $tokens;
    }
}
