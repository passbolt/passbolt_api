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
 * @since         3.11.0
 */

namespace App\Service\AuthenticationTokens;

use App\Model\Entity\AuthenticationToken;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Class AuthenticationTokenGetService
 */
class AuthenticationTokenConsumeService
{
    use LocatorAwareTrait;

    /**
     * Consume active and not expired token or fail
     *
     * @param string $token token value uuid - user provided data
     * @param string $userId user id uuid (should be checked separately before use)
     * @param string $type see AuthenticationToken entity types (should be checked separately before use)
     * @param string|null $expiry expiry, expressed in max duration, ex. "30 days". Default to values in config.
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Http\Exception\NotFoundException if token is not found
     * @throws \App\Error\Exception\CustomValidationException if the token is expired or inactive
     * @throws \Cake\Http\Exception\BadRequestException if token id is not a valid uuid
     */
    public function consumeActiveNotExpiredOrFail(
        string $token,
        string $userId,
        string $type,
        ?string $expiry = null
    ): AuthenticationToken {
        $authenticationToken = (new AuthenticationTokenGetService())
            ->getActiveNotExpiredOrFail($token, $userId, $type, $expiry);

        if ($authenticationToken->isActive()) {
            /** @var \App\Model\Table\AuthenticationTokensTable $authTokensTable */
            $authTokensTable = $this->fetchTable('AuthenticationTokens');
            $authTokensTable->setInactive($authenticationToken->token);
        }

        return $authenticationToken;
    }
}
