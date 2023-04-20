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
 * @since         3.10.0
 */

namespace Passbolt\SmtpSettings\Model\Validation;

use App\Model\Validation\EmailValidationRule;
use App\Model\Validation\PassboltValidationRule;
use Cake\Validation\Validation;

class SmtpSettingsClientValidationRule extends PassboltValidationRule
{
    /**
     * @inheritDoc
     */
    public function defaultErrorMessage($value, $context): string
    {
        return __('The client should be a valid IP or a valid domain.');
    }

    /**
     * @inheritDoc
     */
    public function rule($value, $context): bool
    {
        if (!is_string($value)) {
            return false;
        }

        if ($this->isEmailValid($value)) {
            return true;
        }

        if ($this->isValidIp($value)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $client Client to validate
     * @return bool
     */
    private function isEmailValid(string $client): bool
    {
        return EmailValidationRule::check('no-reply@' . $client);
    }

    /**
     * @param string $client Client to validate
     * @return bool
     */
    private function isValidIp(string $client): bool
    {
        return Validation::ip($client);
    }
}
