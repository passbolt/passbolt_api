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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Duo;

use App\Authenticator\SessionIdentificationServiceInterface;
use Cake\Http\Exception\GoneException;
use Passbolt\MultiFactorAuthentication\Controller\MfaVerifyController;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;

class DuoVerifyGetController extends MfaVerifyController
{
    /**
     * @deprecated Inform that the Duo GET verify endpoint is not available anymore
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService session ID service
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $verifyForm MFA Form
     * @return void
     */
    public function get(
        SessionIdentificationServiceInterface $sessionIdentificationService,
        MfaFormInterface $verifyForm
    ) {
        $this->_assertRequestNotJson();

        throw new GoneException(__('This entrypoint is not available anymore.'));
    }
}
