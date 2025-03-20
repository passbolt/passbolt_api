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

namespace App\Test\Lib\Model;

use App\Model\Entity\Avatar;
use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Factory\ProfileFactory;
use Cake\Core\TestSuite\ContainerStubTrait;
use Cake\ORM\TableRegistry;
use Laminas\Diactoros\UploadedFile;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use const UPLOAD_ERR_OK;

/**
 * @property \App\Model\Table\AvatarsTable $Avatars
 */
trait AvatarsIntegrationTestTrait
{
    use ContainerStubTrait;

    private string $cachedFileLocation;
    private FilesystemAdapter $filesystemAdapter;

    /**
     * Mocks the file system adapter to store avatars in a temporary cache
     * unique for each test in order to run parallel testing
     *
     * @before
     * @return void
     */
    protected function mockAvatarAdapter(): void
    {
        $this->cachedFileLocation = TMP . 'tests' . DS . 'avatars' . rand(0, 99999) . DS;
        $this->filesystemAdapter = new LocalFilesystemAdapter(
            $this->cachedFileLocation
        );
        $this->mockService(FilesystemAdapter::class, function () {
            return $this->filesystemAdapter;
        });
    }

    /**
     * Clean any avatars stored in the temporary cache
     *
     * @after
     * @return void
     */
    protected function cleanAvatarCache(): void
    {
        $this->filesystemAdapter->deleteDirectory('');
        unset($this->filesystemAdapter);
        unset($this->cachedFileLocation);
    }

    /**
     * @param \App\Model\Entity\Avatar|null $avatar
     * @return \App\Model\Entity\Avatar
     * @throws \Exception
     */
    public function createAvatar(?Avatar $avatar = null): Avatar
    {
        $profileId = $avatar->profile_id ?? ProfileFactory::make()->persist()->id;

        $data = [
            'file' => $this->createUploadFile(),
            'profile_id' => $profileId,
        ];

        /** @var \App\Model\Table\AvatarsTable $AvatarsTable */
        $AvatarsTable = TableRegistry::getTableLocator()->get('Avatars');
        if ($avatar) {
            $avatar = $AvatarsTable->patchEntity($avatar, $data);
        } else {
            $avatar = $AvatarsTable->newEntity($data);
        }

        return $AvatarsTable->saveOrFail(
            $avatar,
            [$AvatarsTable::FILESYSTEM_ADAPTER_OPTION => $this->filesystemAdapter]
        );
    }

    /**
     * Create a dummy upload file
     *
     * @param string $format Format
     * @return UploadedFile
     */
    public function createUploadFile(string $format = 'png'): UploadedFile
    {
        $uploadFile = FIXTURES . 'Avatar' . DS . 'ada.' . $format;

        return new UploadedFile(
            $uploadFile,
            filesize($uploadFile),
            UPLOAD_ERR_OK,
            $uploadFile,
            'image/' . $format
        );
    }

    private function assertAvatarCachedFilesExist(Avatar $avatar, $exists = true)
    {
        $this->assertSame(
            $exists,
            $this
                ->filesystemAdapter
                ->fileExists($avatar->id . DS . AvatarsConfigurationService::FORMAT_SMALL . '.jpg')
        );
        $this->assertSame(
            $exists,
            $this
                ->filesystemAdapter
                ->fileExists($avatar->id . DS . AvatarsConfigurationService::FORMAT_MEDIUM . '.jpg')
        );
    }
}
