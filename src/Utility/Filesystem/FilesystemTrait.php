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

use Cake\Core\Configure;
use League\Flysystem\Filesystem;

trait FilesystemTrait
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    /**
     * @param string $path Path to the directory.
     * @return void
     */
    public function setFilesystem(string $path): void
    {
        $filesystemAdapterClass = Configure::readOrFail('ImageStorage.adapter');
        $adapter = new $filesystemAdapterClass($path);
        $this->filesystem = new Filesystem($adapter);
    }

    /**
     * @param string $directoryPath Nam of the directory.
     * @return string
     */
    public function createDirectoryIfNotExists(string $directoryPath): string
    {
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }

        return $directoryPath;
    }
}
