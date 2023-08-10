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

namespace Passbolt\Sso\Model\Validation;

use App\Model\Validation\PassboltValidationRule;
use Passbolt\Sso\Model\Entity\SsoState;

class IsValidStateValidationRule extends PassboltValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The provided state is invalid.');
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        return SsoState::isValidState($value);
    }
}
