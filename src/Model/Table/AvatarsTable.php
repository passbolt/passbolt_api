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
use App\Model\Traits\Cleanup\AvatarsCleanupTrait;
use App\Service\Avatars\AvatarsCacheService;
use App\Service\Avatars\AvatarsConfigurationService;
use App\Utility\AvatarProcessing;
use App\View\Helper\AvatarHelper;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Psr\Http\Message\UploadedFileInterface;

/**
 * @property \App\Model\Table\ProfilesTable&\Cake\ORM\Association\BelongsTo $Profiles
 * @method \App\Model\Entity\Avatar newEmptyEntity()
 * @method \App\Model\Entity\Avatar newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Avatar[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Avatar get($primaryKey, $options = [])
 * @method \App\Model\Entity\Avatar findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Avatar patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Avatar[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Avatar|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Avatar saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\Avatar>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Avatar>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Avatar>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Avatar>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findById(string $id)
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AvatarsTable extends Table
{
    use AvatarsCleanupTrait;

    public const MAX_SIZE = '5MB';
    public const ALLOWED_MIME_TYPES = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
    public const ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg', 'gif'];
    public const FILESYSTEM_ADAPTER_OPTION = 'filesystemAdapter';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->initializeConfiguration();
        $this->addBehavior('Timestamp');
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
            ->requirePresence('file', __('A file is required.'))
            ->allowEmptyString('file', __('The file should not be empty'), false)
            ->add('file', 'validMimeType', [
                'rule' => ['mimeType', self::ALLOWED_MIME_TYPES],
                'message' => __(
                    'The file mime type should be one of the following: {0}.',
                    implode(', ', self::ALLOWED_MIME_TYPES)
                ),
            ])
            ->add('file', 'validExtension', [
                'rule' => ['extension', self::ALLOWED_EXTENSIONS],
                'message' => __(
                    'The file extension should be one of the following: {0}.',
                    implode(', ', self::ALLOWED_EXTENSIONS)
                ),
            ])
            ->add('file', 'validUploadedFile', [
                'rule' => ['uploadedFile', ['maxSize' => self::MAX_SIZE]], // Max size in bytes
                'message' => __(
                    'The file is not valid, or exceeds max size of {0} bytes.',
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
     * @return \Cake\Datasource\EntityInterface|bool
     */
    public function beforeSave(Event $event, Avatar $avatar)
    {
        if (!$this->setData($avatar)) {
            $avatar->setError('data', __('Could not save the data in {0} format.', AvatarHelper::IMAGE_EXTENSION));
            $event->stopPropagation();

            return false;
        }

        return $avatar;
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
        $filesystemAdapter = $this->getFilesystemFromOptions($options);
        (new AvatarsCacheService($filesystemAdapter))->storeInCache($avatar);

        $this->deleteMany($this->find()->where([
             'OR' => [
                 [
                     'profile_id' => $avatar->get('profile_id'),
                     'id !=' => $avatar->get('id'),
                 ],
                 ['data IS NULL'],
             ],
        ]), [self::FILESYSTEM_ADAPTER_OPTION => $filesystemAdapter]);
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
        $filesystemAdapter = $this->getFilesystemFromOptions($options);
        try {
            $filesystemAdapter->deleteDirectory($avatar->get('id'));
        } catch (\Throwable $exception) {
            Log::warning($exception->getMessage());
        }
    }

    /**
     * @param \ArrayObject $options options passed to the after save and after delete events
     * @return \League\Flysystem\FilesystemAdapter
     * @throws \Cake\Http\Exception\InternalErrorException if the developer did not pass the adapter in the saving options
     */
    public function getFilesystemFromOptions(\ArrayObject $options): FilesystemAdapter
    {
        $adapter = $options[self::FILESYSTEM_ADAPTER_OPTION];
        if (!($adapter instanceof FilesystemAdapter)) {
            throw new InternalErrorException('The file system adapter was not passed in the event.');
        }

        return $options[self::FILESYSTEM_ADAPTER_OPTION];
    }

    /**
     * Formatter for Avatar entities.
     * Used mainly to populate an avatar when no there is no result with the default avatar url.
     *
     * @param \Cake\Collection\CollectionInterface $avatars list of avatars (\App\Model\Entity\Avatar)
     * @param bool $isHydrationEnabled if hydration is enabled, return an Avatar object, otherwise an array
     * @return mixed
     */
    private static function formatResults(CollectionInterface $avatars, bool $isHydrationEnabled)
    {
        return $avatars->map(function ($avatar) use ($isHydrationEnabled) {
            $sizes = Configure::read('FileStorage.imageSizes.Avatar');
            $url = [];
            // Add path for each available size.
            foreach ($sizes as $size => $filters) {
                $url[$size] = AvatarHelper::getAvatarUrl([
                    'id' => $avatar['id'] ?? null,
                ], $size);
            }

            if (empty($avatar)) {
                // If avatar is empty, we instantiate one.
                // The virtual field will take care of retrieving the default avatar.
                $avatar = $isHydrationEnabled ? new Avatar() : [];
            }

            if ($avatar instanceof Avatar) {
                $avatar->setVirtual(['url'], true);
                $avatar->set('url', (object)$url);
            } else {
                $avatar['url'] = $url;
            }

            return $avatar;
        });
    }

    /**
     * addContainAvatar
     * Helper to add avatar contains options in a query
     *
     * @return array contain clause
     */
    public static function addContainAvatar(): array
    {
        return [
            'Avatars' => function (Query $q) {
                // Formatter for empty avatars.
                return $q
                    ->select(['Avatars.id', 'Avatars.profile_id', 'Avatars.created', 'Avatars.modified'])
                    ->formatResults(function (CollectionInterface $avatars, Query $mainQuery) {
                        return AvatarsTable::formatResults($avatars, $mainQuery->isHydrationEnabled());
                    });
            },
        ];
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
            // If the avatar provided is empty, the avatar will not be saved, but we should not
            // block the saving. See UsersTable::editEntity() where an empty Avatar is set per default.
            return true;
        } elseif (is_array($file)) {
            $content = file_get_contents($file['tmp_name']);
        } elseif ($file instanceof UploadedFileInterface) {
            try {
                $content = $file->getStream()->getContents();
            } catch (\Throwable $e) {
                Log::error($e->getMessage());

                return false;
            }
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
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * @return \League\Flysystem\FilesystemAdapter
     * @deprecated used for transferring avatars from the file_storage to the avatars table
     */
    public function getFilesystem(): FilesystemAdapter
    {
        return new LocalFilesystemAdapter(TMP . 'avatars');
    }

    /**
     * Set the default file system adapter and configurations on initialize.
     *
     * @return void
     */
    protected function initializeConfiguration(): void
    {
        //  These configurations should be set in the Application::bootstrap() method.
        // However, has a backup, we ensure that on AvatarTable's initialization these
        // configurations are well set.
        if (!Configure::check('FileStorage')) {
            (new AvatarsConfigurationService())->loadConfiguration();
        }
    }
}
