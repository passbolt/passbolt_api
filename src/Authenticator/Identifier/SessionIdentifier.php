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
 * @since         3.3.1
 */
namespace App\Authenticator\Identifier;

use Authentication\Identifier\AbstractIdentifier;
use Authentication\Identifier\Resolver\ResolverAwareTrait;

/**
 * Session Identifier
 */
class SessionIdentifier extends AbstractIdentifier
{
    use ResolverAwareTrait;

    /**
     * @inheritDoc
     */
    public function identify(array $credentials)
    {
        if (!isset($credentials[self::CREDENTIAL_USERNAME])) {
            return null;
        }

        return $this->getResolver()->find([self::CREDENTIAL_USERNAME => $credentials[self::CREDENTIAL_USERNAME],]);
    }
}
