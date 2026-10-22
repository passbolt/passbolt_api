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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\Lib;

use App\Utility\UserAccessControl;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaAuthenticationTokenFactory;

trait MfaAuthenticationTokensTestTrait
{
    /**
     * @param UserAccessControl $uac
     * @param string $provider
     */
    public function assertMfaTokenIsInactive(UserAccessControl $uac, string $provider): void
    {
        /** @var \App\Model\Table\AuthenticationTokensTable $tokens */
        $tokens = MfaAuthenticationTokenFactory::find()->where(['type' => 'mfa', 'user_id' => $uac->getId()])->all();
        foreach ($tokens as $token) {
            $data = json_decode($token->data, true);
            if ($data['provider'] === $provider) {
                $this->assertFalse($token->active);
            }
        }
    }
}
