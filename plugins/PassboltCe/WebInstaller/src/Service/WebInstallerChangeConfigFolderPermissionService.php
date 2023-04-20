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
 * @since         3.8.0
 */
namespace Passbolt\WebInstaller\Service;

class WebInstallerChangeConfigFolderPermissionService
{
    /**
     * @var string
     */
    protected $configPath;

    /**
     * @param string $configPath The path to the config directory
     */
    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * Changes the permissions of the config directory
     *
     * @return void
     */
    public function changeConfigFolderPermission(): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->configPath),
            \RecursiveIteratorIterator::SELF_FIRST,
            \RecursiveIteratorIterator::CATCH_GET_CHILD // Don't throw an error if one child cannot be opened
        );
        foreach ($iterator as $name => $fileInfo) {
            if ($fileInfo->getFilename() == '..') {
                continue;
            }
            if (is_writable($name)) {
                if (is_dir($name)) {
                    chmod($name, 0550);
                } else {
                    chmod($name, 0440);
                }
            }
        }
    }
}
