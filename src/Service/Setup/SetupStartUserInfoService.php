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
 * @since         4.3.0
 */

namespace App\Service\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use App\Service\Users\UserGetService;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

class SetupStartUserInfoService implements SetupStartInfoServiceInterface
{
    /**
     * @inheritDoc
     */
    public function getInfo(string $userId, string $token, ?array $data): array
    {
        try {
            $user = (new UserGetService())->getNotActiveNotDeletedNotDisabledOrFail($userId);
        } catch (NotFoundException $exception) {
            throw new BadRequestException(__('The user does not exist or is already active or is disabled.'));
        }

        $this->assertAuthToken($user, $token);

        $data['user'] = $user;

        return $data;
    }

    /**
     * Check the setup token
     *
     * @param \App\Model\Entity\User $user user attempting to recover
     * @param string $token uuid of the token
     * @throw BadRequestException if the token is not valid
     * @return void
     */
    private function assertAuthToken(User $user, string $token): void
    {
        try {
            (new AuthenticationTokenGetService())
                ->getActiveNotExpiredOrFail($token, $user->id, AuthenticationToken::TYPE_REGISTER);
        } catch (NotFoundException $exception) {
            throw new BadRequestException(__('The authentication token is not valid.'));
        }
    }
}
