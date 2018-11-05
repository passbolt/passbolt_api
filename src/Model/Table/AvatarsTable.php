<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Model\Table;

use App\Model\Entity\Avatar;
use Burzum\FileStorage\Model\Table\ImageStorageTable;
use Burzum\FileStorage\Storage\StorageManager;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Validation\Validator;

class AvatarsTable extends ImageStorageTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->belongsTo('Profiles', [
            'foreignKey' => 'foreign_key',
            'conditions' => ['model' => 'Avatar']
        ]);

        // Reload behavior with our settings.
        $this->removeBehavior('UploadValidator');
        $this->addBehavior('Burzum/FileStorage.UploadValidator', [
            // In debug mode, we disable localFile so that we can test the file upload.
            'localFile' => Configure::read('debug') > 0 ? false : true,
            'validate' => true,
            'allowedExtensions' => [
                'jpg', 'jpeg', 'png', 'gif'
            ]
        ]);

        $this->setTable('file_storage');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('file', __('File should not be empty'))
            ->requirePresence('file', __('A file is required'));

        return $validator;
    }

    /**
     * Implements afterSave() callback.
     * Mainly used to delete former versions of avatars
     * @param Event $event the event
     * @param EntityInterface $entity entity
     * @param array $options options
     * @return bool
     */
    public function afterSave(Event $event, EntityInterface $entity, $options)
    {
        // If there was an existing avatar, we delete it.
        $formerAvatarToCleanUp = $this->getFormerAvatar($entity);
        if (!empty($formerAvatarToCleanUp)) {
            $this->deleteAvatar($formerAvatarToCleanUp);
        }

        $afterSave = parent::afterSave($event, $entity, $options);

        return $afterSave;
    }

    /**
     * Get former avatar, if any, for a given profile.
     * (The former avatar will be considered obsolete).
     * @param \App\Model\Entity\Avatar $entity the avatar entity that has been created
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getFormerAvatar($entity)
    {
        $profileAvatarEntity = $this->find()
            ->where([
                'foreign_key' => $entity->foreign_key,
                'id <>' => $entity->id,
                'model' => 'Avatar'
            ])
            ->first();

        if (empty($profileAvatarEntity)) {
            return null;
        }

        return $profileAvatarEntity;
    }

    /**
     * Delete a profile avatar and all its associated versions from the database and the file system.
     * @param \App\Model\Entity\Avatar $avatar the profile avatar entity
     * @return bool|mixed
     */
    public function deleteAvatar($avatar)
    {
        // Delete the versions of the file.
        $operations = Configure::read('FileStorage.imageSizes.Avatar');
        $Event = new Event('ImageVersion.removeVersion', $this, [
            'record' => $avatar,
            'storage' => StorageManager::getAdapter($avatar->adapter),
            'operations' => $operations
        ]);
        $this->getEventManager()->dispatch($Event);

        // Get the path of the file.
        $imagePath = $avatar->path . str_replace('-', '', $avatar->id) . '.' . $avatar->extension;
        $fullImagePath = Configure::read('ImageStorage.basePath') . DS . $imagePath;

        // If file exists, delete it.
        if (file_exists($fullImagePath)) {
            StorageManager::getAdapter($avatar->adapter)->delete($imagePath);
        }

        return $this->delete($avatar);
    }

    /**
     * BeforeMarshal callback.
     * It enforces the data related to this model and the adapter to be used.
     * @param Event $event the event
     * @param \ArrayAccess $data data
     * @return void
     */
    public function beforeMarshal(Event $event, \ArrayAccess $data)
    {
        parent::beforeMarshal($event, $data);
        $data['adapter'] = 'Local';
        $data['model'] = 'Avatar';
    }

    /**
     * Formatter for Avatar entities.
     * Used mainly to populate an avatar when no there is no result with the default avatar url.
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
     * @return array
     */
    public static function addContainAvatar()
    {
        return [
            'Avatars' => function ($q) {
                // Formatter for empty avatars.
                return $q->formatResults(function (CollectionInterface $avatars) {
                    return AvatarsTable::formatResults($avatars);
                });
            }
        ];
    }
}
