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

namespace App\Test\PhpstanRule\Deprecation;

use Cake\Validation\Validation;
use PhpParser\Node\Expr\StaticCall;

/**
 * Raise error if `Cake\Validation\Validation::email()` is used.
 */
class DeprecatedEmailValidationStaticRule extends AbstractPhpstanDeprecatedMethodRule
{
    /**
     * @inheritDoc
     */
    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    /**
     * @inheritDoc
     */
    public function getClassName(): string
    {
        return Validation::class;
    }

    /**
     * @inheritDoc
     */
    public function getDeprecatedMethods(): array
    {
        return [
            'email' => 'Cake\Validation\Validation::email() is forbidden. Use App\Model\Validation\EmailValidationRule instead',
        ];
    }
}
