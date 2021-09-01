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
namespace App\Test\TestCase\Utility\Filesystem;

use App\Utility\Filesystem\DirectoryUtility;
use Cake\TestSuite\TestCase;

class DirectoryUtilityTest extends TestCase
{
    /**
     * @see DirectoryUtility::removeRecursively()
     */
    public function testDirectoryUtilityRemoveRecursively()
    {
        $folderToDelete = TMP . 'test_folder';
        $subFolder = $folderToDelete . DS . 'sub_folder';
        $fileName = $subFolder . DS . 'FooFile';
        $fileContent = 'FooContent';

        // Create a folder, a sub-folder and a file
        mkdir($folderToDelete);
        mkdir($subFolder);
        file_put_contents($fileName, $fileContent);

        // Make sure that that the creation was successful
        $this->assertSame($fileContent, file_get_contents($fileName), "The file $fileName could not be read or created");

        // Delete the parent folder
        DirectoryUtility::removeRecursively($folderToDelete);

        // The parent folder should have disappeared
        $this->assertSame(false, is_dir($folderToDelete));
    }

    public function testDirectoryUtilityIsFileExecutable_OnNonExistingFile()
    {
        $this->expectException(\RuntimeException::class);
        $file = 'Foo';
        DirectoryUtility::isExecutable($file);
    }

    public function testDirectoryUtilityIsFileExecutable_OnNonExecutableFile()
    {
        $file = TMP . 'tests' . DS . 'directory.test';
        file_put_contents($file, 'foo');

        $perms = [0666, 0662, 0422, 626, 0242];
        foreach ($perms as $perm) {
            chmod($file, $perm);
            $res = DirectoryUtility::isExecutable($file);
            $this->assertFalse($res);
        }

        unlink($file);
    }

    public function testDirectoryUtilityIsFileExecutable_OnExecutableFile()
    {
        $file = TMP . 'tests' . DS . 'directory.test';
        file_put_contents($file, 'foo');

        $perms = [0755, 0535, 0661, 677, 0777];
        foreach ($perms as $perm) {
            chmod($file, $perm);
            $res = DirectoryUtility::isExecutable($file);
            $this->assertTrue($res);
        }

        unlink($file);
    }
}
