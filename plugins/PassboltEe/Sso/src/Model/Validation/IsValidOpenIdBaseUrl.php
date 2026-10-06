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
 * @since         4.4.0
 */

namespace Passbolt\Sso\Model\Validation;

use App\Model\Validation\PassboltValidationRule;
use Passbolt\Sso\Utility\Http\HostValidator;
use Passbolt\Sso\Utility\Http\SsoEgressGuard;

class IsValidOpenIdBaseUrl extends PassboltValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The URL should start with https:// and must not point to an internal address.');
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        if (!is_string($value) || substr($value, 0, 8) !== 'https://') {
            return false;
        }

        $host = parse_url($value, PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            return false;
        }

        $host = HostValidator::stripIpv6Brackets($host);

        if (!HostValidator::isSupported($host)) {
            return false;
        }

        // Reject IP-literal internal addresses at config time; hostname resolution is deferred to
        // connect time (the HTTP-layer egress guard), so no DNS lookup happens during validation.
        if (!HostValidator::isIpLiteral($host)) {
            return true;
        }

        return (new SsoEgressGuard())->getBlockReason($host) === null;
    }
}
