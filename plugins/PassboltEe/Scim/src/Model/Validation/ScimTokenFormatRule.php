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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Model\Validation;

use App\Model\Validation\PassboltValidationRule;
use Passbolt\Scim\Service\ScimSetSettingsService;

/**
 * ScimTokenFormatRule class
 */
class ScimTokenFormatRule extends PassboltValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The secret token format is incorrect.');
    }

    /**
     * Validates de raw format and the hash string
     *
     * {@inheritDoc}
     */
    public function rule($value, $context): bool
    {
        return is_string($value) && (
            (str_starts_with(
                    $value,
                    ScimSetSettingsService::SCIM_SECRET_TOKEN_PREFIX
                ) && strlen($value) >= 46
            ) ||
            preg_match(
                '/^[a-fA-F0-9]{64}$/m',
                $value
            )
        );
    }
}
