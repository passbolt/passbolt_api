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
namespace App\Service\Avatars;

use App\Model\Entity\Avatar;
use App\Utility\AvatarProcessing;
use App\View\Helper\AvatarHelper;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Laminas\Diactoros\Stream;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;

class AvatarsCacheService
{
    use LocatorAwareTrait;

    private Filesystem $filesystem;

    /**
     * AvatarsReadCacheService constructor.
     *
     * @param \League\Flysystem\FilesystemAdapter $filesystemAdapter file system adapter used to cache the avatars
     */
    public function __construct(FilesystemAdapter $filesystemAdapter)
    {
        $this->filesystem = new Filesystem($filesystemAdapter);
    }

    /**
     * @param string|null $id Avatar id
     * @param string $format Avatar format
     * @return \Laminas\Diactoros\Stream
     */
    public function readSteamFromId(?string $id, string $format): Stream
    {
        /** @var \App\Model\Table\AvatarsTable $AvatarsTable */
        $AvatarsTable = TableRegistry::getTableLocator()->get('Avatars');
        /** @var \App\Model\Entity\Avatar|null $avatar */
        $avatar = $id ? $AvatarsTable->findById($id)->first() : null;

        if (is_null($avatar)) {
            return new Stream($this->getFallBackFileName($format));
        } else {
            $format = trim($format, AvatarHelper::IMAGE_EXTENSION);

            return $this->readStreamInCache($avatar, $format);
        }
    }

    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar.
     * @param string|null $format Format.
     * @return string
     */
    public function getAvatarFileName(Avatar $avatar, ?string $format = null): string
    {
        if (empty($avatar->data)) {
            return $this->getFallBackFileName($format);
        } elseif ($format === AvatarsConfigurationService::FORMAT_SMALL) {
            return $this->getSmallAvatarFileName($avatar);
        } else {
            return $this->getMediumAvatarFileName($avatar);
        }
    }

    /**
     * Store the image in $avatar->data in medium and small formats.
     *
     * @param \App\Model\Entity\Avatar $avatar Avatar to read the data from.
     * @return void
     */
    public function storeInCache(Avatar $avatar): void
    {
        $data = $avatar->getDataInStringFormat();

        if (empty($data)) {
            return;
        }

        try {
            $smallImage = AvatarProcessing::resizeAndCrop(
                $data,
                Configure::readOrFail('FileStorage.imageSizes.Avatar.small.thumbnail.width'),
                Configure::readOrFail('FileStorage.imageSizes.Avatar.small.thumbnail.height'),
            );
        } catch (\Throwable $e) {
            Log::error('Error while processing small image for avatar with ID ' . $avatar->id . '.');
            Log::error($e->getMessage());

            return;
        }

        $this->writeAvatarDataInFilesystem($this->getMediumAvatarFileName($avatar), $data, $avatar);
        $this->writeAvatarDataInFilesystem($this->getSmallAvatarFileName($avatar), $smallImage, $avatar);
    }

    /**
     * Write avatar on file system as non-executable.
     *
     * @param string $filename Name of the target file
     * @param string $data Image data
     * @param \App\Model\Entity\Avatar $avatar Avatar
     * @return void
     */
    protected function writeAvatarDataInFilesystem(string $filename, string $data, Avatar $avatar): void
    {
        try {
            $this->filesystem->write($filename, $data);
        } catch (\Throwable $e) {
            Log::error('Error while saving cache avatar with ID ' . $avatar->id . '.');
            Log::error($e->getMessage());
        }
    }

    /**
     * Returns the full path to the file in cache.
     * If the cache does not exist, tries to create it.
     * If no data is in the avatar, returns the default
     * avatar image.
     *
     * @param \App\Model\Entity\Avatar $avatar The avatar concerned.
     * @param string $format The format to recover.
     * @return \Laminas\Diactoros\Stream The full path to the filename.
     */
    protected function readStreamInCache(
        Avatar $avatar,
        string $format = AvatarsConfigurationService::FORMAT_SMALL
    ): Stream {
        $fileName = $this->getAvatarFileName($avatar, $format);
        try {
            $stream = $this->filesystem->readStream($fileName);
        } catch (\Throwable $e) {
            $stream = null;
        }

        if (empty($stream)) {
            try {
                $this->storeInCache($avatar);
                $stream = $this->filesystem->readStream($fileName);
            } catch (\Throwable $exception) {
                Log::warning(__('Could not save the avatar in the {0} file.', $fileName));
                $stream = $this->getFallBackFileName($format);
            }
        }

        return new Stream($stream);
    }

    /**
     * The default avatar file.
     *
     * @param ?string $format Format of the image.
     * @return string
     * @throws \RuntimeException if the avatar config is not set in config/file_storage.php
     */
    public function getFallBackFileName(?string $format = null): string
    {
        if (empty($format)) {
            $format = $this->getDefaultFormat();
        }
        try {
            $fileName = Configure::readOrFail('FileStorage.imageDefaults.Avatar.' . $format);
        } catch (\RuntimeException $e) {
            $fileName = Configure::readOrFail('FileStorage.imageDefaults.Avatar.' . $this->getDefaultFormat());
        }

        return WWW_ROOT . $fileName;
    }

    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar.
     * @return string
     */
    protected function getSmallAvatarFileName(Avatar $avatar): string
    {
        return $this->getOrCreateAvatarDirectory($avatar)
            . AvatarsConfigurationService::FORMAT_SMALL
            . AvatarHelper::IMAGE_EXTENSION;
    }

    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar concerned
     * @return string
     */
    protected function getMediumAvatarFileName(Avatar $avatar): string
    {
        return $this->getOrCreateAvatarDirectory($avatar)
            . AvatarsConfigurationService::FORMAT_MEDIUM
            . AvatarHelper::IMAGE_EXTENSION;
    }

    /**
     * Get or create the relative directory name of a given avatar.
     *
     * @param \App\Model\Entity\Avatar $avatar Avatar
     * @return string
     * @throws \League\Flysystem\FilesystemException The cache directory must be readable/writable.
     * @throws \Exception if the avatar id is not a uuid.
     */
    protected function getOrCreateAvatarDirectory(Avatar $avatar): string
    {
        $avatarId = $avatar->id;
        if (!Validation::uuid($avatarId)) {
            throw new \Exception(__('The avatar id is not valid.'));
        }
        $avatarCacheSubDirectory = $avatarId . DS;
        $this->filesystem->createDirectory($avatarCacheSubDirectory);

        return $avatarCacheSubDirectory;
    }

    /**
     * The default avatar format
     *
     * @return string
     */
    protected function getDefaultFormat(): string
    {
        return AvatarsConfigurationService::FORMAT_MEDIUM;
    }
}
