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
 * @since         3.0.0
 */
namespace App\Utility\Filesystem;

class DirectoryUtility
{
    /**
     * Remove directory and its content recursively
     *
     * @param string $directoryName Name of the directory to delete
     * @return bool
     */
    public static function removeRecursively(string $directoryName): bool
    {
        if (!file_exists($directoryName)) {
            return true;
        }

        if (!is_dir($directoryName)) {
            return unlink($directoryName);
        }

        foreach (scandir($directoryName) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::removeRecursively($directoryName . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($directoryName);
    }
}
