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
 * @since         3.1.0
 */
namespace App\Test\Lib\Utility;

trait PassboltCommandTestTrait
{
    public function assertCommandCannotBeRunAsRootUser(string $commandClassName)
    {
        /** @var \App\Command\PassboltCommand $cmd */
        $cmd = new $commandClassName();

        $cmd::$isUserRoot = true;
        $this->exec($cmd::defaultName());
        $this->assertOutputContains('Passbolt commands cannot be executed as root.');
        $this->assertExitError();
        $cmd::$isUserRoot = false;
    }

    /**
     * Delete all files in a directory.
     *
     * @param string $dir
     */
    public function emptyDirectory(string $dir)
    {
        $files = glob($dir . '*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file) && $file !== $dir . 'empty') {
                unlink($file); // delete file
            }
        }
    }
}
