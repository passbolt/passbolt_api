<?php
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

namespace Passbolt\Folders\Test\TestCase\Model\Table;

use App\Model\Entity\Permission;
use App\Model\Table\ResourcesTable;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Fixture\PermissionsFixture;
use Passbolt\Folders\Test\Fixture\ResourcesFixture;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Model\Table\FoldersRelationsTable Test Case
 */
class PermissionsCleanupTest extends FoldersTestCase
{
    use CleanupTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GroupsFixture::class,
        PermissionsFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
    ];

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var ResourcesTable
     */
    private $resourcesTables;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $this->resourcesTables = TableRegistry::getTableLocator()->get('Resources');
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
    }

    public function testCleanupFoldersRelationsHardDeletedFoldersSuccess()
    {
        $originalCount = 4;
        $checkOptions = ['cleanupCount' => 2];
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');

        $this->addFolderFor(['name' => 'A'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        // The folder B is going to be hard deleted
        $folderB = $this->addFolderFor(['name' => 'B'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);
        $this->foldersTable->deleteAll(['id' => $folderB->id]);

        $this->runCleanupChecks('Permissions', 'cleanupHardDeletedFolders', $originalCount, $checkOptions);
    }
}
