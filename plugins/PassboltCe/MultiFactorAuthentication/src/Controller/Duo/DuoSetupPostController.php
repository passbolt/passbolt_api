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

use Cake\Http\Exception\GoneException;
use Passbolt\MultiFactorAuthentication\Controller\MfaSetupController;

class DuoSetupPostController extends MfaSetupController
{
    /**
     * @deprecated Inform that the Duo POST setup endpoint is not available anymore
     * @return \Cake\Http\Response
     */
    public function post()
    {
        $this->_assertRequestNotJson();

        throw new GoneException(__('This entrypoint is not available anymore.'));
    }
}
