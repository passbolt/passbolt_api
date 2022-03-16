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

namespace App\Model\Validation;

use Cake\Validation\ValidationRule;

abstract class PassboltValidationRule extends ValidationRule
{
    /**
     * The rule
     *
     * @param mixed $value Value to validate
     * @param mixed $context Context
     * @return bool
     */
    abstract public function rule($value, $context): bool;

    /**
     * The error displayed
     *
     * @param mixed $value Value to validate
     * @param mixed $context Context
     * @return string
     */
    abstract public function getErrorMessage($value, $context): string;

    /**
     * Construct
     *
     * @param array $validator Validation parameters
     */
    public function __construct(array $validator = [])
    {
        $validator['rule'] = function ($value, $context) use ($validator) {
            $msg = $validator['message'] ?? $this->getErrorMessage($value, $context);

            return $this->rule($value, $context) ? true : $msg;
        };

        parent::__construct($validator);
    }
}
