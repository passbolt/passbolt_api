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

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\AvatarsModelTestTrait;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use League\Flysystem\Local\LocalFilesystemAdapter;

class CreateTest extends AppTestCase
{
    use AvatarsModelTestTrait;

    /**
     * @var \App\Model\Table\AvatarsTable
     */
    public $Avatars;

    public function setUp(): void
    {
        parent::setUp();
        $this->Avatars = TableRegistry::getTableLocator()->get('Avatars');
        $this->Avatars->setFilesystem(new LocalFilesystemAdapter(TMP . 'tests' . DS . 'avatars'));
    }

    public function tearDown(): void
    {
        $this->Avatars->getFilesystem()->deleteDirectory('.');
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
        $this->Avatars->getFilesystem()->deleteDirectory('.');
        $this->assertAvatarCachedFilesExist($avatar);
    }
}
