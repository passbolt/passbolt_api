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

namespace App\Test\Rule\Deprecation;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Raise error if a method in a given class should not be used
 */
abstract class AbstractPHPStanDeprecatedMethodRule implements Rule
{
    /**
     * The name of the class with deprecated methods
     *
     * @return string
     */
    abstract public function getClassName(): string;

    /**
     * The list of the deprecated methods with their error messages.
     *
     * [
     *   'method1' => 'Error message for method1',
     * ]
     *
     * @return string[]
     */
    abstract public function getDeprecatedMethods(): array;

    /**
     * @inheritDoc
     */
    public function processNode(Node $node, Scope $scope): array
    {
        /**
         * @var \PhpParser\Node\Expr\StaticCall $node
         * @psalm-suppress TypeDoesNotContainType
         */
        if (!isset($node->class) || !isset($node->class->parts) || !is_array($node->class->parts)) {
            return [];
        }

        $className = implode('\\', $node->class->parts);

        if ($className !== $this->getClassName()) {
            return [];
        }

        $result = [];
        foreach ($this->getDeprecatedMethods() as $method => $errorMessage) {
            /** @var \PhpParser\Node\Expr\StaticCall $node */
            if (isset($node->name->name) && $node->name->name === $method) {
                $result[] = RuleErrorBuilder::message($errorMessage)->build();
            }
        }

        return $result;
    }
}
