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
namespace Passbolt\MultiFactorAuthentication\Authenticator;

use Authentication\AuthenticationService;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class MfaWebauthnDelegatedAuthenticationService extends AuthenticationService
{
    public const DELEGATED_AUTHENTICATOR = 'Passbolt/MultiFactorAuthentication.DelegatedCeremonyToken';

    /**
     * @inheritDoc
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        $this->setConfig(['unauthenticatedRedirect' => null]);
        $this->loadAuthenticator(self::DELEGATED_AUTHENTICATOR);

        $result = parent::authenticate($request);
        if (!$result->isValid()) {
            if ($result->getStatus() === Result::FAILURE_CREDENTIALS_MISSING) {
                throw new BadRequestException(__('A ceremony token is required.'));
            }
            throw new NotFoundException(__('The ceremony token is invalid or has expired.'));
        }

        return $result;
    }
}
