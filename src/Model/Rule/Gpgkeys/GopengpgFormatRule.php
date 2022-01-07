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
 * @since         3.5.0
 */

namespace App\Model\Rule\Gpgkeys;

use App\Model\Entity\Gpgkey;

class GopengpgFormatRule
{
    /**
     * Performs the check
     *
     * @param \App\Model\Entity\Gpgkey $entity The entity to check
     * @param ?array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Gpgkey $entity, ?array $options = []): bool
    {
        $key = $entity->armored_key;
        if (empty($key)) {
            return false;
        }

        return $this->assertHasNoDoubleReturnLineInTheLastThreeLines($key);
    }

    /**
     * @param string $string String to check
     * @return bool
     */
    protected function assertHasNoDoubleReturnLineInTheLastThreeLines(string $string): bool
    {
        $string = trim($string);
        $array = explode("\n", $string);
        $size = count($array);
        if ($size < 2) {
            return false;
        }
        // If the line before the last line is empty
        if (empty($array[$size - 2])) {
            return false;
        }

        return true;
    }
}
