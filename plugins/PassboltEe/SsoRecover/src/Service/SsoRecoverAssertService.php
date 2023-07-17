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
 * @since         3.11.0
 */

namespace Passbolt\SsoRecover\Service;

use App\Error\Exception\CustomValidationException;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\ExtendedUserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\Router;
use Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationEmailDomainsDryRunService;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Service\SsoAuthenticationTokens\SsoAuthenticationTokenSetService;
use Passbolt\Sso\Service\SsoStates\SsoStatesAssertService;
use Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface;

class SsoRecoverAssertService
{
    use LocatorAwareTrait;
    use FeaturePluginAwareTrait;

    /**
     * Fetches resource owner details from state & code and returns URL to redirect.
     *
     * @param \Passbolt\Sso\Service\Sso\AbstractSsoService $ssoService SSO service.
     * @param \Passbolt\Sso\Model\Entity\SsoState $ssoState SSO state entity.
     * @param string $code Code.
     * @param string $ip IP.
     * @param string $userAgent User agent.
     * @param string $provider SSO provider.
     * @return string URL to redirect.
     * @throws \Exception When there's an error while retrieving resource owner.
     * @throws \Cake\Http\Exception\BadRequestException When any assertions are failed.
     * @throws \Cake\Http\Exception\BadRequestException When self-registration plugin is disabled.
     * @throws \Cake\Http\Exception\BadRequestException When email domain is not allowed.
     * @throws \Cake\Http\Exception\BadRequestException When email domains doesn't exist.
     */
    public function assertAndGetRedirectUrl(
        AbstractSsoService $ssoService,
        SsoState $ssoState,
        string $code,
        string $ip,
        string $userAgent,
        string $provider
    ): string {
        try {
            $resourceOwner = $ssoService->getResourceOwner($code);

            $ssoService->assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);
        } catch (\Exception $e) {
            $msg = 'There was an error while retrieving resource owner. ';
            $msg .= "Message: {$e->getMessage()}, State ID: {$ssoState->state}";

            Log::error($msg);

            throw $e;
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var \App\Model\Entity\User|null $user */
        $user = $usersTable->findByUsername($resourceOwner->getEmail())->first();

        if ($user === null) {
            /**
             * User not found, navigate to self-registration flow.
             */
            $this->isAllowedForSelfRegister($resourceOwner);

            return Router::url("/sso/recover/error?email={$resourceOwner->getEmail()}", true);
        }

        /**
         * User found, continue with recover flow.
         */
        $uac = new ExtendedUserAccessControl($user->role->name, $user->id, $user->username, $ip, $userAgent);

        (new SsoStatesAssertService())->assertAndConsumeWithoutUser($ssoState, $ssoService->getSettings()->id, $uac);

        $ssoAuthToken = (new SsoAuthenticationTokenSetService())->createOrFail(
            $uac,
            SsoState::TYPE_SSO_RECOVER,
            $ssoService->getSettings()->id
        );

        return Router::url("/sso/recover/{$provider}/success?token={$ssoAuthToken->token}", true);
    }

    /**
     * @param \Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface $resourceOwner Resource owner.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException When self-registration plugin is disabled.
     * @throws \Cake\Http\Exception\BadRequestException When email domain is not allowed.
     * @throws \Cake\Http\Exception\BadRequestException When email domains doesn't exist.
     */
    private function isAllowedForSelfRegister(SsoResourceOwnerInterface $resourceOwner): void
    {
        if (!$this->isFeaturePluginEnabled('SelfRegistration')) {
            throw new BadRequestException(__('The user does not exist or has been deleted.'));
        }

        $selfRegistrationService = new SelfRegistrationEmailDomainsDryRunService();
        $data = ['email' => $resourceOwner->getEmail()];

        try {
            $selfRegistrationService->canGuestSelfRegister($data);
        } catch (CustomValidationException | ForbiddenException $e) {
            $msg = __('Access to this service requires an invitation. ');
            $msg .= __('Please contact your administrator to request an invitation link.');

            throw new BadRequestException($msg, null, $e);
        }
    }
}
