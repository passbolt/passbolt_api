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
 * @since         3.3.0
 */

namespace App\Test\TestCase\Model\Table\Avatars;

use App\Model\Entity\Avatar;
use App\Model\Table\AvatarsTable;
use App\Test\Factory\AvatarFactory;
use App\Test\Lib\Model\AvatarsIntegrationTestTrait;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class AvatarsDeleteTest extends TestCase
{
    use AvatarsIntegrationTestTrait;
    use TruncateDirtyTables;

    /**
     * Test subject
     *
     * @var \App\Model\Table\AvatarsTable
     */
    public $Avatars;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Avatars = TableRegistry::getTableLocator()->get('Avatars');
        $this->loadRoutes();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Avatars);
        parent::tearDown();
    }

    public function testAvatarsDelete_Should_Clean_Filesystem()
    {
        $avatar = $this->createAvatar();
        $this->assertAvatarCachedFilesExist($avatar);

        $this->Avatars->delete($avatar, [AvatarsTable::FILESYSTEM_ADAPTER_OPTION => $this->filesystemAdapter]);
        $this->assertSame(0, AvatarFactory::count());
        $this->assertAvatarCachedFilesExist($avatar, false);
    }

    public function testAvatarsDelete_Should_Clean_Orphan_After_Saving_An_Avatar()
    {
        AvatarFactory::make()->setField('data', null)->persist();
        $avatarOriginal = $this->createAvatar();
        $this->assertAvatarCachedFilesExist($avatarOriginal);

        $avatarUpdated = $this->createAvatar(new Avatar(['profile_id' => $avatarOriginal->profile_id]));

        $this->assertSame(1, AvatarFactory::count());
        $this->assertAvatarCachedFilesExist($avatarOriginal, false);
        $this->assertAvatarCachedFilesExist($avatarUpdated, true);
    }
}
