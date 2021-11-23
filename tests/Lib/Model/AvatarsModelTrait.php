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
use App\Service\Avatars\AvatarsCacheService;
use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Factory\AvatarFactory;
use App\Test\Factory\ProfileFactory;
use Cake\ORM\TableRegistry;
use Laminas\Diactoros\Stream;
use Laminas\Diactoros\UploadedFile;
use League\Flysystem\Local\LocalFilesystemAdapter;

/**
 * @property \App\Model\Table\AvatarsTable $Avatars
 */
trait AvatarsModelTrait
{
    /**
     * Asserts that an object has all the attributes an avatar should have.
     *
     * @param object $avatar
     */
    protected function assertAvatarAttributes($avatar)
    {
        $this->assertObjectHasAttributes(['url'], $avatar);
        $this->assertObjectHasAttributes(['small', 'medium'], $avatar->url);
    }

    /**
     * Asserts that an object has the urls required.
     *
     * @param object $avatar
     */
    protected function assertAvatarUrlAttributes($avatar)
    {
        $this->assertObjectHasAttributes(['url'], $avatar);
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

        /** @var AvatarsTable $AvatarsTable */
        $AvatarsTable = TableRegistry::getTableLocator()->get('Avatars');
        if ($avatar) {
            $avatar = $AvatarsTable->patchEntity($avatar, $data);
        } else {
            $avatar = $AvatarsTable->newEntity($data);
        }

        return $AvatarsTable->saveOrFail($avatar);
    }

    /**
     * Create a dummy upload file
     *
     * @param string $format Format
     * @return UploadedFile
     */
    public function createUploadFile(string $format = 'png')
    {
        $uploadFile = FIXTURES . 'Avatar' . DS . 'ada' . '.' . $format;

        return new UploadedFile(
            $uploadFile,
            filesize($uploadFile),
            \UPLOAD_ERR_OK,
            $uploadFile,
            'image/' . $format
        );
    }

    private function assertAvatarCachedFilesExist(Avatar $avatar)
    {
        $service = new AvatarsCacheService($this->Avatars);
        $this->assertInstanceOf(Stream::class, $service->readSteamFromId($avatar->id, AvatarsConfigurationService::FORMAT_SMALL));
        $this->assertInstanceOf(Stream::class, $service->readSteamFromId($avatar->id, AvatarsConfigurationService::FORMAT_MEDIUM));
        $this->assertInstanceOf(Stream::class, $service->readSteamFromId($avatar->id, 'whateverFormatWillReturnSmall'));
        $this->assertTextEndsWith('.jpg', $service->getAvatarFileName($avatar));
        $this->assertTextEndsWith('.jpg', $service->getAvatarFileName($avatar, 'medium'));
    }

    /**
     * Set the avatar directory to tmp/tests/avatars
     * The file system of both the regular table and avatar factory
     * table need to be set
     */
    protected function setTestLocalFilesystemAdapter(): void
    {
        $testFileSystem = new LocalFilesystemAdapter(TMP . 'tests' . DS . 'avatars');
        TableRegistry::getTableLocator()->get('Avatars')->setFilesystem($testFileSystem);
        AvatarFactory::make()->getTable()->setFilesystem($testFileSystem);
    }
}
