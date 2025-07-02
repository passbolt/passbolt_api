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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Model\Table\Permissions;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\CleanupTrait;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;

/**
 * Passbolt\Folders\Model\Table\FoldersRelationsTable Test Case
 */
class PermissionsCleanupTest extends FoldersTestCase
{
    use CleanupTrait;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    private $foldersTable;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
    }

    public function testCleanupFoldersRelationsHardDeletedFoldersSuccess()
    {
        $originalCount = 4;
        $checkOptions = ['cleanupCount' => 2];
        [$userA, $userB] = UserFactory::make(2)->persist();
        FolderFactory::make(['name' => 'FA'])->withPermissionsFor([$userA, $userB])->persist();
        ResourceFactory::make(['name' => 'R1'])->withPermissionsFor([$userA, $userB])->persist();
        $folderB = FolderFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        // The folder B is going to be hard deleted
        $this->foldersTable->deleteAll(['id' => $folderB->get('id')]);

        $this->runCleanupChecks('Permissions', 'cleanupHardDeletedFolders', $originalCount, $checkOptions);
    }
}
