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
     * Find an active user registration token.
     *
     * @param \Cake\ORM\Query $query The query to decorate
     * @param array $options The finder options
     *   [userId: string, token: string]
     * @return \Cake\ORM\Query
     */
    public function findActiveUserRegistrationToken(Query $query, array $options): Query
    {
        return $this->findActiveByType($query, AuthenticationToken::TYPE_REGISTER, $options);
    }

    /**
     * Find an active user recovery token.
     *
     * @param \Cake\ORM\Query $query The query to decorate
     * @param array $options The finder options
     *   [userId: string, token: string]
     * @return \Cake\ORM\Query
     */
    public function findActiveUserRecoveryToken(Query $query, array $options): Query
    {
        return $this->findActiveByType($query, AuthenticationToken::TYPE_RECOVER, $options);
    }

    /**
     * @param \Cake\ORM\Query $query The query to decorate
     * @param string $type The token type
     * @param array $options The finder options
     *   [userId: string, token: string]
     * @return \Cake\ORM\Query
     */
    public function findActiveByType(Query $query, string $type, array $options): Query
    {
        $where = [
            'type' => $type,
            'token' => Hash::get($options, 'token', ''),
            'user_id' => Hash::get($options, 'userId', ''),
            'active' => true,
        ];

        return $query->where($where);
    }
}
