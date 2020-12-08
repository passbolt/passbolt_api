<?php
declare(strict_types=1);

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
 * @since         3.0
 */

namespace App\Model\Traits\AuthenticationTokens;

use App\Model\Entity\AuthenticationToken;
use Cake\ORM\Query;
use Cake\Utility\Hash;

trait AuthenticationTokensFindersTrait
{
    /**
     * Find a user registration token.
     *
     * @param \Cake\ORM\Query $query The query to decorate
     * @param array $options The finder options
     *   [userId: string, token: string]
     * @return \Cake\ORM\Query
     */
    public function findUserRegistrationToken(Query $query, array $options): Query
    {
        $where = [
            'type' => AuthenticationToken::TYPE_REGISTER,
            'token' => Hash::get($options, 'token'),
            'user_id' => Hash::get($options, 'userId'),
        ];

        return $query->where($where);
    }

    /**
     * Find a user recovery token.
     *
     * @param \Cake\ORM\Query $query The query to decorate
     * @param array $options The finder options
     *   [userId: string, token: string]
     * @return \Cake\ORM\Query
     */
    public function findUserRecoveryToken(Query $query, array $options): Query
    {
        $where = [
            'type' => AuthenticationToken::TYPE_RECOVER,
            'token' => Hash::get($options, 'token'),
            'user_id' => Hash::get($options, 'userId'),
        ];

        return $query->where($where);
    }
}
