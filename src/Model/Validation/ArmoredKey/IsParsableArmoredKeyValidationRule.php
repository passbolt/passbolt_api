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

namespace App\Model\Validation\ArmoredKey;

use App\Model\Validation\PassboltValidationRule;
use App\Service\OpenPGP\PublicKeyValidationService;

class IsParsableArmoredKeyValidationRule extends PassboltValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The armored key should be a valid ASCII-armored OpenPGP key.');
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return PublicKeyValidationService::isParsableArmoredPublicKey($value);
    }
}
