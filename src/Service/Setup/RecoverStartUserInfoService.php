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
 * @since         3.5.0
 */

namespace App\Service\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use App\Service\Users\UserGetService;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

class RecoverStartUserInfoService implements RecoverStartInfoServiceInterface
{
    /**
     * @inheritDoc
     */
    public function getInfo(string $userId, string $token, ?array $data): array
    {
        try {
            $user = (new UserGetService())->getActiveNotDeletedNotDisabledOrFail($userId);
        } catch (NotFoundException $exception) {
            throw new BadRequestException(__('The user does not exist or is not active or is disabled.'));
        }

        $this->assertAuthToken($token, $user);

        $data['user'] = $user;

        return $data;
    }

    /**
     * Find the recover token
     *
     * @param string $token uuid of the token
     * @param \App\Model\Entity\User $user user attempting to recover
     * @return void
     * @throw Custom if the token is not valid
     * @throw BadRequestException if the token is not valid
     */
    private function assertAuthToken(string $token, User $user): void
    {
        try {
            (new AuthenticationTokenGetService())
                ->getActiveNotExpiredOrFail($token, $user->id, AuthenticationToken::TYPE_RECOVER);
        } catch (NotFoundException $exception) {
            throw new BadRequestException(__('The authentication token is not valid.'));
        }
    }
}
