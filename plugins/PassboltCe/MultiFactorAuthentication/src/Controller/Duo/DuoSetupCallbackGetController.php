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
namespace Passbolt\MultiFactorAuthentication\Controller\Duo;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Error\Exception\FormValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Utility\UserAccessControl;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Form\Duo\DuoCallbackForm;
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaDuoCallbackDto;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoEnableService;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoStateCookieService;
use Passbolt\MultiFactorAuthentication\Service\MfaVerifiedCookieService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoSetupCallbackGetController extends MfaSetupController
{
    /**
     * Handle Duo setup callback GET request. We return a Response to avoid using templates to respond:
     * - 200 OK (for mobile)
     * - 302 FOUND (otherwise)
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @param \Duo\DuoUniversal\Client|null $duoSdkClient Duo SDK Client
     * @return void
     */
    public function get(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        ?Client $duoSdkClient = null
    ): void {
        $this->_assertRequestNotJson();
        $this->_orgAllowProviderOrFail(MfaSettings::PROVIDER_DUO);
        $this->_notAlreadySetupOrFail(MfaSettings::PROVIDER_DUO);

        $uac = $this->User->getAccessControl();
        $mfaDuoCallbackDto = $this->getAndAssertMfaDuoCallbackData();
        $cookieToken = $this->consumeAndAssertCookieToken();

        $authenticationToken = (new MfaDuoEnableService($duoSdkClient))->enable(
            $uac,
            $mfaDuoCallbackDto,
            $cookieToken
        );
        $this->addMfaVerifiedCookieToResponse($uac, $sessionIdentificationService);

        $this->disableAutoRender();
        $this->redirectIfDefinedInToken($authenticationToken);
    }

    /**
     * Get the Mfa Duo Callback data from the query and assert them.
     *
     * @throws \App\Error\Exception\FormValidationException If the data provided on the query does not validate
     * @throws \Cake\Http\Exception\BadRequestException If Duo was not able to authenticate the user and provided error details
     * @return \Passbolt\MultiFactorAuthentication\Model\Dto\MfaDuoCallbackDto
     */
    private function getAndAssertMfaDuoCallbackData(): MfaDuoCallbackDto
    {
        $mfaDuoCallbackData = $this->getRequest()->getQueryParams();
        $mfaDuoCallbackForm = new DuoCallbackForm();
        $isValid = $mfaDuoCallbackForm->execute($mfaDuoCallbackData);
        $mfaDuoCallbackDto = new MfaDuoCallbackDto($mfaDuoCallbackForm->getData());

        if ($mfaDuoCallbackDto->hasError()) {
            $msg = __('Unable to authenticate to Duo.');
            $msg .= " {$mfaDuoCallbackDto->formatError()}";
            throw new BadRequestException($msg);
        }

        if (!$isValid) {
            $msg = __('Unable to validate the Duo callback data.');
            throw new FormValidationException($msg, $mfaDuoCallbackForm);
        }

        return $mfaDuoCallbackDto;
    }

    /**
     * Consume the duo state cookie containing the user authentication token id and assert the format this one.
     *
     * @return string The token id stored in the cookie
     * @throws \Cake\Http\Exception\BadRequestException if the cookie is not defined
     * @throws \Cake\Http\Exception\BadRequestException if the cookie value is not a string
     * @throws \Cake\Http\Exception\BadRequestException if the cookie value is not a valid uuid
     */
    private function consumeAndAssertCookieToken(): string
    {
        $cookieToken = (new MfaDuoStateCookieService())->readDuoStateCookieValue($this->getRequest());
        if (is_null($cookieToken)) {
            throw new BadRequestException(__('A Duo state cookie is required.'));
        }
        $cookieToExpire = new Cookie(MfaDuoStateCookieService::MFA_COOKIE_DUO_STATE);
        $this->setResponse($this->getResponse()->withExpiredCookie($cookieToExpire));

        if (!is_string($cookieToken)) {
            throw new BadRequestException(__('The Duo state cookie value should be a string.'));
        } elseif (!Validation::uuid($cookieToken)) {
            throw new BadRequestException(__('The Duo state cookie should be a valid UUID.'));
        }

        return $cookieToken;
    }

    /**
     * Add to the response the MFA verified cookie.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException if it cannot create MFA cookie
     */
    private function addMfaVerifiedCookieToResponse(
        UserAccessControl $uac,
        SessionIdentificationServiceInterface $sessionIdentificationService
    ): void {
        try {
            $cookie = (new MfaVerifiedCookieService())->createDuoMfaVerifiedCookie(
                $uac,
                $sessionIdentificationService,
                $this->getRequest()
            );
        } catch (\Throwable $e) {
            throw new InternalErrorException('Could not create MFA verified cookie.', null, $e);
        }

        $this->setResponse($this->getResponse()->withCookie($cookie));
    }

    /**
     * Redirect the user if the authentication token contains a redirect path.
     *
     * @param \App\Model\Entity\AuthenticationToken $authenticationToken The authentication token
     * @return void
     */
    private function redirectIfDefinedInToken(AuthenticationToken $authenticationToken): void
    {
        $redirect = $authenticationToken->getDataValue('redirect');
        if (!empty($redirect)) {
            $this->redirect($redirect);
        }
    }
}
