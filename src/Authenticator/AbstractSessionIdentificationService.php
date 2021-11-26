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
 * @since         3.4.0
 */
namespace App\Authenticator;

use Authentication\AuthenticationService;
use Cake\Http\ServerRequest;

abstract class AbstractSessionIdentificationService implements SessionIdentificationServiceInterface
{
    /**
     * @inheritDoc
     */
    abstract public function getSessionId(ServerRequest $request): ?string;

    /**
     * @param \Cake\Http\ServerRequest $request Request
     * @return bool
     */
    protected function isAuthenticated(ServerRequest $request): bool
    {
        $authService = $request->getAttribute('authentication');
        if ($authService instanceof AuthenticationService) {
            return $authService->getResult()->isValid();
        }

        return false;
    }
}
