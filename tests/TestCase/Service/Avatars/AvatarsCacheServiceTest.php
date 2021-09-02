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
 * @since         3.2.0
 */

namespace App\Test\TestCase\Service\Avatars;

use App\Model\Entity\Avatar;
use App\Service\Avatars\AvatarsCacheService;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Laminas\Diactoros\Stream;
use League\Flysystem\Local\LocalFilesystemAdapter;

/**
 * @covers \App\Service\Avatars\AvatarsCacheService
 */
class AvatarsCacheServiceTest extends TestCase
{
    /**
     * @var \App\Model\Table\AvatarsTable
     */
    public $Avatars;

    /**
     * @var AvatarsCacheService
     */
    public $avatarsCacheService;

    /**
     * @var string
     */
    public $cachedFileLocation = TMP . 'tests' . DS . 'avatars' . DS;

    public function setUp(): void
    {
        parent::setUp();
        $this->Avatars = TableRegistry::getTableLocator()->get('Avatars');
        $this->Avatars->setFilesystem(new LocalFilesystemAdapter($this->cachedFileLocation));
        $this->avatarsCacheService = new AvatarsCacheService($this->Avatars);
    }

    public function tearDown(): void
    {
        $this->Avatars->getFilesystem()->deleteDirectory('.');
        unset($this->Avatars);
        unset($this->avatarsCacheService);
        parent::tearDown();
    }

    public function dataForTestAvatarsCacheServiceStore(): array
    {
        return [
            [file_get_contents(FIXTURES . 'Avatar' . DS . 'ada.png')],
            [(new Stream(FIXTURES . 'Avatar' . DS . 'ada.png'))->getContents()],
        ];
    }

    public function dataForTestAvatarsCacheServiceStoreFail(): array
    {
        return [
            ['1234'],
            [FIXTURES . 'Avatar' . DS . 'ada.png'],
            [(new Stream(FIXTURES . 'Avatar' . DS . 'ada.png'))],
        ];
    }

    /**
     * @dataProvider dataForTestAvatarsCacheServiceStore
     */
    public function testAvatarsCacheServiceStore($data)
    {
        $id = UuidFactory::uuid('foo');
        $avatar = new Avatar(compact('id', 'data'));

        $this->avatarsCacheService->storeInCache($avatar);

        $this->assertFileExists($this->cachedFileLocation . $id . DS . 'medium.jpg');
        $this->assertFileExists($this->cachedFileLocation . $id . DS . 'small.jpg');

        // Perform the action twice to ensure that no overwriting issues occur
        $this->avatarsCacheService->storeInCache($avatar);

        $this->assertFileExists($this->cachedFileLocation . $id . DS . 'medium.jpg');
        $this->assertFileExists($this->cachedFileLocation . $id . DS . 'small.jpg');

        $this->assertSame(
            file_get_contents(FIXTURES . 'Avatar' . DS . 'ada.png'),
            file_get_contents($this->cachedFileLocation . $id . DS . 'medium.jpg')
        );
    }

    /**
     * @dataProvider dataForTestAvatarsCacheServiceStoreFail
     */
    public function testAvatarsCacheServiceStoreFail($data)
    {
        $id = UuidFactory::uuid('foo');
        $avatar = new Avatar(compact('id', 'data'));

        $this->avatarsCacheService->storeInCache($avatar);

        $this->assertFileDoesNotExist($this->cachedFileLocation . $id . DS . 'medium.jpg');
        $this->assertFileDoesNotExist($this->cachedFileLocation . $id . DS . 'small.jpg');
    }
}
