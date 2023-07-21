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

use App\Middleware\UacAwareMiddlewareTrait;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;
use Passbolt\MultiFactorAuthentication\Service\UpdateMfaTokenSessionIdService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class UpdateMfaTokenSessionIdOnRefreshTokenCreated implements EventListenerInterface
{
    use UacAwareMiddlewareTrait;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            RefreshTokenCreateService::REFRESH_TOKEN_CREATED_EVENT => 'updateMfaTokenSessionId',
        ];
    }

    /**
     * On JWT authentication, when a new refresh token is created,
     * sets the access token as session ID in the MFA authentication token.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function updateMfaTokenSessionId(EventInterface $event): void
    {
        /** @var \Cake\Http\ServerRequest $request */
        $request = $event->getData(RefreshTokenCreateService::REQUEST_DATA_KEY);
        $uac = $this->getUacInRequest($request);

        // Do nothing if the user has MFA disabled.
        $mfaSettings = MfaSettings::get($uac);
        if (!$mfaSettings->hasEnabledProviders()) {
            return;
        }

        $mfaToken = $request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (is_string($mfaToken)) {
            $accessToken = $event->getData(RefreshTokenCreateService::ACCESS_TOKEN_DATA_KEY);
            (new UpdateMfaTokenSessionIdService())->updateSessionId($mfaToken, $accessToken);
        }
    }
}
