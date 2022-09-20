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

use Cake\Log\Log;

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

        if (!is_writable($directoryName) || !is_readable($directoryName) || !self::isExecutable($directoryName)) {
            $error = __('The directory {0} cannot be deleted', $directoryName);
            Log::warning($error);

            return false;
        }

        if (!@rmdir($directoryName)) { // @codingStandardsIgnoreLine
            if (is_array(error_get_last())) {
                $error = json_encode(error_get_last());
                Log::warning($error);
            }

            return false;
        }

        return true;
    }

    /**
     * The is_executable() PHP method is not reliable to check permissions,
     * as it will return true on non executable files. The present method
     * checks bitwise the permission of a given file.
     *
     * @param string $path File or directory path
     * @return bool
     * @throws \RuntimeException if the provided file/directory does not exist
     */
    public static function isExecutable(string $path): bool
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("The file $path could not be found.");
        }
        $code = str_split(decoct(fileperms($path) & 0777));
        foreach ($code as $perm) {
            if ((int)$perm % 2 !== 0) {
                return true;
            }
        }

        return false;
    }
}
