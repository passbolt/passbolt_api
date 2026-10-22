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

namespace Passbolt\MultiFactorAuthentication\Service\MfaPolicies;

use App\Service\Webauthn\AbstractWebauthnPolicyService;

class DefaultMfaWebauthnPolicyService extends AbstractWebauthnPolicyService implements MfaWebauthnPolicyInterface
{
    /**
     * @inheritDoc
     */
    public function getMaxCredentialsPerUser(): int
    {
        return 10;
    }
}
