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
use League\Flysystem\FilesystemAdapter;

trait FilesystemTrait
{
    /**
     * @return \League\Flysystem\Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return Configure::read('ImageStorage.filesystem');
    }

    /**
     * @param \League\Flysystem\FilesystemAdapter $adapter Path to the directory.
     * @return void
     * @throws \RuntimeException Will throw an exception if the image storage adapter is not configured.
     */
    public function setFilesystem(FilesystemAdapter $adapter): void
    {
        Configure::write('ImageStorage.filesystem', new Filesystem($adapter));
    }
}
