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
namespace Passbolt\MultiFactorAuthentication\Test\Lib\Utility;

use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedCeremonyService;

class DelegatedCeremonyTestUtility
{
    /**
     * @return string
     */
    public static function validState(): string
    {
        return rtrim(
            strtr(base64_encode(random_bytes(MfaWebauthnDelegatedCeremonyService::STATE_BYTES)), '+/', '-_'),
            '=',
        );
    }
}
