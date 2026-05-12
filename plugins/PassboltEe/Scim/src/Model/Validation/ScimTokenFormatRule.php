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
use Cake\Core\Configure;
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
     * {@inheritDoc}
     *
     * Validates the raw format and the hash string (bcrypt or legacy SHA-256).
     */
    public function rule($value, $context): bool
    {
        if (!is_string($value)) {
            return false;
        }

        // Raw token format: pb_ prefix + 43 chars base64url
        if (
            str_starts_with($value, ScimSetSettingsService::SCIM_SECRET_TOKEN_PREFIX)
            && strlen($value) >= 46
        ) {
            return true;
        }

        // Bcrypt hash format: $2y$... or $2b$... (60 chars)
        if (preg_match('/^\$2[yb]\$\d{2}\$.{53}$/', $value)) {
            return true;
        }

        // Legacy SHA-256 hash format: 64 hex chars — only accepted if legacy support is enabled
        if (
            Configure::read('passbolt.plugins.scim.security.secretToken.legacyHashAllowed', true)
            && preg_match('/^[a-fA-F0-9]{64}$/', $value)
        ) {
            return true;
        }

        return false;
    }
}
