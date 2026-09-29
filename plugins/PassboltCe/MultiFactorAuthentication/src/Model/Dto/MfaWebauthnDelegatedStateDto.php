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
namespace Passbolt\MultiFactorAuthentication\Model\Dto;

readonly class MfaWebauthnDelegatedStateDto
{
    /**
     * @param string $state Delegated state, published in the MFA page URL.
     * @param string $secret Delegated secret, returned to the calling application only.
     */
    final public function __construct(
        public string $state,
        public string $secret,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'mfa_delegated_state' => $this->state,
            'mfa_delegated_secret' => $this->secret,
        ];
    }
}
