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
 * @since         3.6.0
 */

namespace App\Model\Validation\DateTime;

use App\Model\Validation\PassboltValidationRule;
use Cake\Chronos\ChronosInterface;
use Cake\I18n\FrozenTime;

/**
 * Check if a key date is set in the future
 * Used to check key expiry date
 */
class IsDateInFutureValidationRule extends PassboltValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The key should not already be expired.');
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        if (!($value instanceof ChronosInterface)) {
            return false;
        }

        return $value->greaterThan(FrozenTime::now());
    }
}
