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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Duo;

use Cake\Http\Exception\GoneException;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;
use Passbolt\MultiFactorAuthentication\Form\MfaFormInterface;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoSetupGetController extends MfaSetupController
{
    public const PROVIDER = MfaSettings::PROVIDER_DUO;
    public const DUO_SETUP_REDIRECT_PATH = '/app/settings/mfa/duo';

    /**
     * @deprecated Inform that the Duo GET setup endpoint is not available anymore
     * @param \Passbolt\MultiFactorAuthentication\Form\MfaFormInterface $setupForm MFA Form
     * @return void
     */
    public function get(MfaFormInterface $setupForm)
    {
        $this->_assertRequestNotJson();

        throw new GoneException(__('This entrypoint is not available anymore.'));
    }

    /**
     * Get the full Duo setup redirect URL.
     *
     * @return string
     */
    public static function getFormUrl(): string
    {
        return Router::url('/mfa/setup/duo/prompt?redirect=' . self::DUO_SETUP_REDIRECT_PATH, true);
    }
}
