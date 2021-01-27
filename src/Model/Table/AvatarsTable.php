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
use App\Utility\Filesystem\FilesystemTrait;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Psr\Http\Message\UploadedFileInterface;

class AvatarsTable extends Table
{
    use FilesystemTrait;

    public const JPEG_QUALITY = 100;
    public const FORMAT_SMALL = 'small';
    public const FORMAT_MEDIUM = 'medium';

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
                'rule' => ['uploadedFile', ['optional' => false]],
                'message' => 'File is no valid uploaded file',
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
            $avatar->setError('data', __('The data could not be saved in jpg format.'));
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
    }

    /**
     * Formatter for Avatar entities.
     * Used mainly to populate an avatar when no there is no result with the default avatar url.
     *
     * @param array $avatars list of avatars (\App\Model\Entity\Avatar)
     * @return mixed
     */
    public static function formatResults($avatars)
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
            'Avatars' => function ($q) {
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
            $this->storeInCache($avatar);
        }

        return $this->getCacheDirectory() . $fileName;
    }

    /**
     * Store the image in $avatar->data in medium and small formats.
     *
     * @param \App\Model\Entity\Avatar $avatar Avatar to read the data from.
     * @return void
     */
    public function storeInCache(Avatar $avatar): void
    {
        if (empty($avatar->get('data'))) {
            return;
        }

        $this->getFilesystem()->write($this->getMediumAvatarFileName($avatar), $avatar->get('data'));

        $smallImage = $this->getImagine()
            ->load($avatar->get('data'))
            ->resize(new Box(
                Configure::readOrFail('FileStorage.imageSizes.Avatar.small.thumbnail.width'),
                Configure::readOrFail('FileStorage.imageSizes.Avatar.small.thumbnail.height'),
            ))
            ->get('jpeg');

        $this->getFilesystem()->write($this->getSmallAvatarFileName($avatar), $smallImage);
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
            $tmpName = $file['tmp_name'];
        } elseif ($file instanceof UploadedFileInterface) {
            $tmpName = $file->getClientFilename();
        } else {
            return false;
        }

        $tmpNameJpg = $tmpName . '.jpg';

        try {
            $this->getImagine()
                ->open($tmpName)
                ->resize(new Box(
                    Configure::readOrFail('FileStorage.imageSizes.Avatar.medium.thumbnail.width'),
                    Configure::readOrFail('FileStorage.imageSizes.Avatar.medium.thumbnail.height'),
                ))
                ->save($tmpNameJpg, [
                    'quality' => self::JPEG_QUALITY,
                ]);
            $avatar->set('data', file_get_contents($tmpNameJpg));
        } catch (\Exception $exception) {
            return false;
        } finally {
            if (file_exists($tmpNameJpg)) {
                unlink($tmpNameJpg);
            }
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
    }

    /**
     * Get the relative directory name of a given avatar.
     *
     * @param \App\Model\Entity\Avatar $avatar Avatar
     * @return string
     */
    public function getAvatarDirectory(Avatar $avatar): string
    {
        $avatarCacheDirectory = $avatar->get('id') . DS;
        $this->getFilesystem()->createDirectory($avatarCacheDirectory);

        return $avatarCacheDirectory;
    }

    /**
     * @return \Imagine\Gd\Imagine
     */
    public function getImagine(): Imagine
    {
        return new Imagine();
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
        } elseif ($format === self::FORMAT_MEDIUM) {
            return $this->getMediumAvatarFileName($avatar);
        } else {
            return $this->getSmallAvatarFileName($avatar);
        }
    }

    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar.
     * @return string
     */
    public function getSmallAvatarFileName(Avatar $avatar): string
    {
        return $this->getAvatarDirectory($avatar) . self::FORMAT_SMALL . '.jpg';
    }

    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar concerned
     * @return string
     */
    public function getMediumAvatarFileName(Avatar $avatar): string
    {
        return $this->getAvatarDirectory($avatar) . self::FORMAT_MEDIUM . '.jpg';
    }

    /**
     * The default avatar file.
     *
     * @param string $format Format of the image.
     * @return string
     */
    public function getFallBackFileName(string $format): string
    {
        return WWW_ROOT . Configure::readOrFail('FileStorage.imageDefaults.Avatar.' . $format);
    }
}
