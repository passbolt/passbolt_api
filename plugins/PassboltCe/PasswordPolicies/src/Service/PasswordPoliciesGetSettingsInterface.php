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
 * @since         4.2.0
 */
namespace Passbolt\PasswordPolicies\Service;

use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;

interface PasswordPoliciesGetSettingsInterface
{
    /**
     * Returns passwords policies settings.
     *
     * @return \Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto
     * @throw FormValidationException If the settings does not validate.
     */
    public function get(): PasswordPoliciesSettingsDto;
}
