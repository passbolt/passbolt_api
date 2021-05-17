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

use App\Service\Avatars\AvatarsTransferService;
use App\Test\Factory\ProfileFactory;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use League\Flysystem\Local\LocalFilesystemAdapter;

/**
 * @deprecated Will be removed with version 4
 * The service under test was used to migrate avatars from
 * version prior to 3.2 to 3.2.
 * @covers \App\Service\Avatars\AvatarsTransferService
 */
class AvatarsTransferServiceTest extends TestCase
{
    /**
     * @var \App\Model\Table\AvatarsTable
     */
    public $Avatars;

    /**
     * @var \Cake\ORM\Table
     */
    public $FileStorage;

    /**
     * @var AvatarsTransferService
     */
    public $avatarsTransferService;

    /**
     * @var string
     */
    public $cachedFileLocation = TMP . 'tests' . DS . 'avatars' . DS;

    public function setUp(): void
    {
        $this->skipIf($this->fileStorageTableDoesNotExist());

        parent::setUp();
        $this->Avatars = TableRegistry::getTableLocator()->get('Avatars');
        $this->Avatars->setFilesystem(new LocalFilesystemAdapter($this->cachedFileLocation));
        $this->FileStorage = TableRegistry::getTableLocator()->get('FileStorage')->setTable('file_storage');
        $this->avatarsTransferService = new AvatarsTransferService($this->Avatars, $this->FileStorage);

        $this->Avatars->getFilesystem()->write(
            'ada.png',
            file_get_contents(TESTS . 'Fixture' . DS . 'Avatar' . DS . 'ada.png')
        );

        $this->Avatars->getFilesystem()->write(
            'ada2.png',
            file_get_contents(TESTS . 'Fixture' . DS . 'Avatar' . DS . 'ada.png')
        );

        Configure::write('ImageStorage.basePath', $this->cachedFileLocation);
    }

    public function tearDown(): void
    {
        $this->Avatars->getFilesystem()->deleteDirectory('.');
        unset($this->Avatars);
        unset($this->FileStorage);
        unset($this->avatarsTransferService);
        Configure::write('ImageStorage.basePath', WWW_ROOT . 'img' . DS . 'public' . DS);
        parent::tearDown();
    }

    public function fileStorageTableDoesNotExist()
    {
        return !in_array('file_storage', ConnectionManager::get('test')->getSchemaCollection()->listTables());
    }

    public function testTransferAvatarsDebugMode()
    {
        // Full path
        $this->persistFileStorage($this->cachedFileLocation . 'ada.png');
        // Relative path
        $this->persistFileStorage('ada2.png');
        // Non existent file
        $this->persistFileStorage('foo.png');

        $service = new AvatarsTransferService($this->Avatars, $this->FileStorage, true);
        $result = $service->transfer();

        $this->assertSame(2, count($result['success']));
        $this->assertSame(1, count($result['error']));
        // In debug mode, no avatars get persisted, nor file storage get deleted.
        $this->assertSame(3, $this->FileStorage->find()->count());
    }

    public function testTransferAvatarsNonDebugMode()
    {
        // Full path
        $this->persistFileStorage($this->cachedFileLocation . 'ada.png');
        // Relative path
        $this->persistFileStorage('ada2.png');
        // Non existent file
        $this->persistFileStorage('foo.png');

        $service = new AvatarsTransferService($this->Avatars, $this->FileStorage);
        $service->transfer();

        // The file storage with non existent file is not deleted.
        $this->assertSame(1, $this->FileStorage->find()->count());
        $this->assertSame(2, $this->Avatars->find()->count());
    }

    public function persistFileStorage($targetFile)
    {
        $fileStorage = $this->FileStorage->newEntity([
            'fileName' => 'ada.png',
            'model' => 'Avatar',
            'path' => $targetFile,
            'foreign_key' => ProfileFactory::make()->persist()->id,
        ]);
        $this->FileStorage->saveOrFail($fileStorage);
    }
}
