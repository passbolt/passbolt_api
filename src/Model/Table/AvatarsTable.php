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
namespace App\Model\Table;

use App\Model\Entity\Avatar;
use App\Utility\AvatarProcessing;
use App\Utility\Filesystem\FilesystemTrait;
use App\View\Helper\AvatarHelper;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\UploadedFileInterface;

class AvatarsTable extends Table
{
    use FilesystemTrait;

    public const FORMAT_SMALL = 'small';
    public const FORMAT_MEDIUM = 'medium';
    public const MAX_SIZE = '5MB';

    /**
     * @var string
     */
    protected $cacheDirectory;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setCacheDirectory(TMP . 'avatars' . DS);

        $this->belongsTo('Profiles');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('file', __('A file is required'))
            ->allowEmptyString('file', __('File should not be empty'), false)
            ->add('file', 'validMimeType', [
                'rule' => ['mimeType', ['image/jpeg', 'image/png', 'image/gif']],
            ])
            ->add('file', 'validExtension', [
                'rule' => ['extension', ['png', 'jpg', 'gif']],
            ])
            ->add('file', 'validUploadedFile', [
                'rule' => ['uploadedFile', ['maxSize' => self::MAX_SIZE]], // Max size in bytes
                'message' => __(
                    'Uploaded file is not valid, or exceeds max size of {0} bytes',
                    self::MAX_SIZE
                ),
            ]);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['profile_id'], 'Profiles'));

        // Add a rule checking that either the url or some image data are set

        return $rules;
    }

    /**
     * Implements beforeSave() callback.
     * Convert in resized jpeg format.
     *
     * @param \Cake\Event\Event $event the event
     * @param \App\Model\Entity\Avatar $avatar entity
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeSave(Event $event, Avatar $avatar, \ArrayObject $options)
    {
        if (!$this->setData($avatar)) {
            $avatar->setError('data', __('The data could not be saved in {0} format.', AvatarHelper::IMAGE_EXTENSION));
            $event->stopPropagation();
        }
    }

    /**
     * Implements afterSave() callback.
     * Mainly used to delete former versions of avatars.
     * Store the avatar in the cache.
     *
     * @param \Cake\Event\Event $event the event
     * @param \App\Model\Entity\Avatar $avatar entity
     * @param \ArrayObject $options options
     * @return void
     */
    public function afterSave(Event $event, Avatar $avatar, \ArrayObject $options)
    {
        $this->storeInCache($avatar);

        $this->deleteMany($this->find()->where([
            $this->aliasField('profile_id') => $avatar->get('profile_id'),
            $this->aliasField('id') . ' <>' => $avatar->get('id'),
        ]));
    }

    /**
     * After an avatar was deleted, its caching directory gets deleted as well.
     *
     * @param \Cake\Event\Event $event the event
     * @param \App\Model\Entity\Avatar $avatar entity
     * @param \ArrayObject $options options
     * @return void
     */
    public function afterDelete(Event $event, Avatar $avatar, \ArrayObject $options)
    {
        try {
            $this->getFilesystem()->deleteDirectory($this->getOrCreateAvatarDirectory($avatar));
        } catch (FilesystemException $exception) {
            Log::warning($exception->getMessage());
        }
    }

    /**
     * Formatter for Avatar entities.
     * Used mainly to populate an avatar when no there is no result with the default avatar url.
     *
     * @param array $avatars list of avatars (\App\Model\Entity\Avatar)
     * @return mixed
     */
    public static function formatResults(CollectionInterface $avatars)
    {
        return $avatars->map(function ($avatar) {
            if (empty($avatar)) {
                // If avatar is empty, we instantiate one.
                // The virtual field will take care of retrieving the default avatar.
                $avatar = new Avatar();
            }

            return $avatar;
        });
    }

    /**
     * Generate an Avatar contain clause to be inserted in a contain table.
     *
     * @return array
     */
    public static function addContainAvatar(): array
    {
        return [
            'Avatars' => function (Query $q) {
                // Formatter for empty avatars.
                return $q->formatResults(function (CollectionInterface $avatars) {
                    return AvatarsTable::formatResults($avatars);
                });
            },
        ];
    }

    /**
     * Returns the full path to the file in cache.
     * If the cache does not exist, tries to create it.
     * If no data is in the avatar, returns the default
     * avatar image.
     *
     * @param \App\Model\Entity\Avatar $avatar The avatar concerned.
     * @param string $format The format to recover.
     * @return string The full path to the filename.
     */
    public function readFromCache(Avatar $avatar, string $format = self::FORMAT_SMALL): string
    {
        $fileName = $this->getAvatarFileName($avatar, $format);

        if (!$this->getFilesystem()->fileExists($fileName)) {
            try {
                $this->storeInCache($avatar);
            } catch (\Exception $exception) {
                Log::warning(__('The avatar could not be saved in cache.'));

                return $this->getFallBackFileName($format);
            }
        }

        return $this->getCacheDirectory() . $fileName;
    }

    /**
     * Store the image in $avatar->data in medium and small formats.
     *
     * @param \App\Model\Entity\Avatar $avatar Avatar to read the data from.
     * @return void
     * @throws \RuntimeException The avatar sizes are not set in configs.
     * @throws \League\Flysystem\FilesystemException The cache directory is not writable.
     */
    public function storeInCache(Avatar $avatar): void
    {
        if (empty($avatar->get('data'))) {
            return;
        }

        try {
            $this->getFilesystem()->write($this->getMediumAvatarFileName($avatar), $avatar->get('data'));
        } catch (\Exception $e) {
            Log::error(__('Error while saving medium avatar with ID {0}', $avatar->get('id')));
            Log::error($e->getMessage());
        }

        try {
            $content = $this->getFilesystem()->read($this->getMediumAvatarFileName($avatar));
            $smallImage = AvatarProcessing::resizeAndCrop(
                $content,
                Configure::readOrFail('FileStorage.imageSizes.Avatar.small.thumbnail.width'),
                Configure::readOrFail('FileStorage.imageSizes.Avatar.small.thumbnail.height'),
            );
            $this->getFilesystem()->write($this->getSmallAvatarFileName($avatar), $smallImage);
        } catch (\Exception $e) {
            Log::error(__('Error while saving small avatar with ID {0}', $avatar->get('id')));
            Log::error($e->getMessage());
        }
    }

    /**
     * Parse the file provided, resize it and store it in the
     * data property of the avatar.
     *
     * @param \App\Model\Entity\Avatar $avatar Avatar concerned.
     * @return bool
     */
    public function setData(Avatar $avatar): bool
    {
        $file = $avatar->get('file');

        if ($file === null) {
            return true;
        } elseif (is_array($file)) {
            $content = file_get_contents($file['tmp_name']);
        } elseif ($file instanceof UploadedFileInterface) {
            $content = $file->getStream()->getContents();
        } else {
            return false;
        }

        try {
            $img = AvatarProcessing::resizeAndCrop(
                $content,
                Configure::readOrFail('FileStorage.imageSizes.Avatar.medium.thumbnail.width'),
                Configure::readOrFail('FileStorage.imageSizes.Avatar.medium.thumbnail.height')
            );
            $avatar->set('data', $img);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getCacheDirectory(): string
    {
        return $this->cacheDirectory;
    }

    /**
     * Defines where the avatars are cached.
     *
     * @param string $cacheDirectory Cache path.
     * @return void
     */
    public function setCacheDirectory(string $cacheDirectory): void
    {
        if (substr($cacheDirectory, -1) !== DS) {
            $cacheDirectory .= DS;
        }
        $this->cacheDirectory = $cacheDirectory;
        $this->setFilesystem($cacheDirectory);
        if (!is_writable($cacheDirectory)) {
            Log::warning(__(
                'The directory {0} is not writable. Avatars cannot be cached nor read.',
                $cacheDirectory
            ));
        }
    }

    /**
     * Get or create the relative directory name of a given avatar.
     *
     * @param \App\Model\Entity\Avatar $avatar Avatar
     * @return string
     * @throws \League\Flysystem\FilesystemException The cache directory must be readable/writable.
     */
    public function getOrCreateAvatarDirectory(Avatar $avatar): string
    {
        $avatarCacheDirectory = $avatar->get('id') . DS;
        $this->getFilesystem()->createDirectory($avatarCacheDirectory);

        return $avatarCacheDirectory;
    }

    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar.
     * @param string $format Format.
     * @return string
     */
    public function getAvatarFileName(Avatar $avatar, string $format): string
    {
        if (empty($avatar->get('data'))) {
            return $this->getFallBackFileName($format);
        } elseif ($format === self::FORMAT_SMALL) {
            return $this->getSmallAvatarFileName($avatar);
        } else {
            return $this->getMediumAvatarFileName($avatar);
        }
    }

    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar.
     * @return string
     */
    public function getSmallAvatarFileName(Avatar $avatar): string
    {
        return $this->getOrCreateAvatarDirectory($avatar) . self::FORMAT_SMALL . AvatarHelper::IMAGE_EXTENSION;
    }

    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar concerned
     * @return string
     */
    public function getMediumAvatarFileName(Avatar $avatar): string
    {
        return $this->getOrCreateAvatarDirectory($avatar) . self::FORMAT_MEDIUM . AvatarHelper::IMAGE_EXTENSION;
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
     * The default avatar format
     *
     * @return string
     */
    public function getDefaultFormat(): string
    {
        return self::FORMAT_MEDIUM;
    }
}
