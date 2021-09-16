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
 * @since         3.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Event;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;
use Passbolt\MultiFactorAuthentication\Service\UpdateMfaTokenSessionIdService;

class UpdateSessionIdInMfaToken implements EventListenerInterface
{
    /**
     * @var string
     */
    private $mfaToken;

    /**
     * UpdateSessionIdInMfaToken constructor.
     *
     * @param string $mfaToken MFA Token
     */
    public function __construct(string $mfaToken)
    {
        $this->mfaToken = $mfaToken;
    }

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            RefreshTokenCreateService::REFRESH_TOKEN_CREATED_EVENT => 'updateSessionIdInMfaToken',
        ];
    }

    /**
     * On login with JWT, sets the access token as sesion ID in the MFA authentication token.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function updateSessionIdInMfaToken(EventInterface $event): void
    {
        $accessToken = $event->getData(RefreshTokenCreateService::ACCESS_TOKEN_DATA_KEY);
        (new UpdateMfaTokenSessionIdService())->updateSessionId($this->mfaToken, $accessToken);
    }
}
