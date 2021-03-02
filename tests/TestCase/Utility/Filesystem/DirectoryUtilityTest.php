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

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\Filesystem\DirectoryUtility;

class DirectoryUtilityTest extends AppIntegrationTestCase
{
    /**
     * @see DirectoryUtility::removeRecursively()
     */
    public function testRemoveRecursively()
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
}
