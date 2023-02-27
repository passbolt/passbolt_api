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
namespace Passbolt\MultiFactorAuthentication\Form\Duo;

use App\Utility\UserAccessControl;
use Passbolt\MultiFactorAuthentication\Form\MfaForm;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoVerifyForm extends MfaForm
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Utility\MfaSettings
     */
    protected $settings;

    /**
     * VerifyForm constructor.
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\MultiFactorAuthentication\Utility\MfaSettings $settings settings
     */
    public function __construct(UserAccessControl $uac, MfaSettings $settings)
    {
        parent::__construct($uac);
        $this->settings = $settings;
    }
}
