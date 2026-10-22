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
namespace Passbolt\MultiFactorAuthentication\Controller\Webauthn;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Passbolt\MultiFactorAuthentication\Authenticator\DelegatedStateAuthenticator;
use Passbolt\MultiFactorAuthentication\Form\Webauthn\WebauthnVerifyForm;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedTokenService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService;

class WebauthnDelegatedFinishPostController extends AppController
{
    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService $verifyAssertionService Assertion service.
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedTokenService $delegatedTokenService Delegated token service.
     * @return void
     */
    public function post(
        MfaWebauthnVerifyAssertionService $verifyAssertionService,
        MfaWebauthnDelegatedTokenService $delegatedTokenService,
    ): void {
        $this->assertJson();

        $state = $this->getRequest()->getData(DelegatedStateAuthenticator::STATE_PARAM);
        if (!is_string($state)) {
            throw new BadRequestException(__('A delegated state is required.'));
        }

        $verifyForm = new WebauthnVerifyForm($this->User->getAccessControl(), $verifyAssertionService);
        $verifyForm->execute($this->getRequest()->getData());

        $delegatedToken = $delegatedTokenService->create($state);

        $this->success(__('The operation was successful.'), ['mfa_delegated_token' => $delegatedToken]);
    }
}
