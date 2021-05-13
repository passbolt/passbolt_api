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
use App\Model\Table\AvatarsTable;
use App\Utility\AvatarProcessing;
use App\View\Helper\AvatarHelper;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Log\Log;
use Laminas\Diactoros\Stream;

class AvatarsCacheService
{
    use ModelAwareTrait;

    /**
     * @var \App\Model\Table\AvatarsTable
     */
    public $Avatars;

    /**
     * AvatarsReadCacheService constructor.
     *
     * @param \App\Model\Table\AvatarsTable $AvatarsTable Injected Avatars table.
     */
    public function __construct(AvatarsTable $AvatarsTable)
    {
        $this->Avatars = $AvatarsTable;
    }

    /**
     * @param string|null $id Avatar id
     * @param string $format Avaar format
     * @return \Laminas\Diactoros\Stream
     */
    public function readSteamFromId(?string $id, string $format): Stream
    {
        /** @var \App\Model\Entity\Avatar|null $avatar */
        $avatar = $id ? $this->Avatars->findById($id)->first() : null;

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
        } elseif ($format === AvatarsTable::FORMAT_SMALL) {
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
        if (empty($avatar->data)) {
            return;
        }

        $data = $avatar->data;

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

        try {
            $this->Avatars->getFilesystem()->write($this->getMediumAvatarFileName($avatar), $data);
            $this->Avatars->getFilesystem()->write($this->getSmallAvatarFileName($avatar), $smallImage);
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
    protected function readStreamInCache(Avatar $avatar, string $format = AvatarsTable::FORMAT_SMALL): Stream
    {
        $fileName = $this->getAvatarFileName($avatar, $format);

        if (!$this->Avatars->getFilesystem()->fileExists($fileName)) {
            try {
                $this->storeInCache($avatar);
                $stream = $this->Avatars->getFilesystem()->readStream($fileName);
            } catch (\Throwable $exception) {
                Log::warning(__('Could not save the avatar in cache.'));
                $stream = $this->getFallBackFileName($format);
            }
        } else {
            try {
                $stream = $this->Avatars->getFilesystem()->readStream($fileName);
            } catch (\Throwable $exception) {
                Log::warning(__('Could not read the avatar in cache.'));
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
        return $this->getOrCreateAvatarDirectory($avatar) . AvatarsTable::FORMAT_SMALL . AvatarHelper::IMAGE_EXTENSION;
    }

    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar concerned
     * @return string
     */
    protected function getMediumAvatarFileName(Avatar $avatar): string
    {
        return $this->getOrCreateAvatarDirectory($avatar) . AvatarsTable::FORMAT_MEDIUM . AvatarHelper::IMAGE_EXTENSION;
    }

    /**
     * Get or create the relative directory name of a given avatar.
     *
     * @param \App\Model\Entity\Avatar $avatar Avatar
     * @return string
     * @throws \League\Flysystem\FilesystemException The cache directory must be readable/writable.
     */
    protected function getOrCreateAvatarDirectory(Avatar $avatar): string
    {
        $avatarCacheSubDirectory = $avatar->id . DS;
        $this->Avatars->getFilesystem()->createDirectory($avatarCacheSubDirectory);

        return $avatarCacheSubDirectory;
    }

    /**
     * The default avatar format
     *
     * @return string
     */
    protected function getDefaultFormat(): string
    {
        return AvatarsTable::FORMAT_MEDIUM;
    }
}
