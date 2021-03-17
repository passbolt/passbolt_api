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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Avatars;

use App\Model\Entity\Avatar;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\AvatarsModelTrait;
use App\Utility\Filesystem\DirectoryUtility;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class CreateTest extends AppTestCase
{
    use AvatarsModelTrait;

    /**
     * @var \App\Model\Table\AvatarsTable
     */
    public $Avatars;

    public function setUp(): void
    {
        parent::setUp();
        $this->Avatars = TableRegistry::getTableLocator()->get('Avatars');
        $this->Avatars->setCacheDirectory(TMP . 'tests' . DS . 'avatars' . DS);
        DirectoryUtility::removeRecursively($this->Avatars->getCacheDirectory());
    }

    public function tearDown(): void
    {
        unset($this->Avatars);
        parent::tearDown();
    }

    public function dataProviderForCreateAvatarFile()
    {
        return [
            [false,],
            [true,],
        ];
    }

    /**
     * @dataProvider dataProviderForCreateAvatarFile
     * @param bool $withExistingAvatar Create an Avatar if true.
     * @throws \Exception
     */
    public function testCreateAvatarFile(bool $withExistingAvatar)
    {
        $this->assertNotEmpty(Configure::read('ImageStorage.publicPath'));
        if ($withExistingAvatar) {
            $avatar = $this->createAvatar();
        } else {
            $avatar = null;
        }

        $avatar = $this->createAvatar($avatar);

        $this->assertAvatarCachedFilesExist($avatar);
    }

    public function testAvatarCacheFilesCreatedAfterDirectoryDeleted()
    {
        $avatar = $this->createAvatar();
        $this->assertAvatarCachedFilesExist($avatar);
        $this->Avatars->getFilesystem()->deleteDirectory($this->Avatars->getOrCreateAvatarDirectory($avatar));
        $this->assertAvatarCachedFilesExist($avatar);
    }

    private function assertAvatarCachedFilesExist(Avatar $avatar)
    {
        $this->assertTrue(file_exists($this->Avatars->readFromCache($avatar)));
        $this->assertTrue(file_exists($this->Avatars->readFromCache($avatar, 'medium')));
        $this->assertTrue(file_exists($this->Avatars->readFromCache($avatar, 'whateverFormatWillReturnSmall')));
        $this->assertTextEndsWith('.jpg', $this->Avatars->readFromCache($avatar));
        $this->assertTextEndsWith('.jpg', $this->Avatars->readFromCache($avatar, 'medium'));
    }
}
