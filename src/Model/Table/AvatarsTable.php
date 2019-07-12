<?php
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
use Burzum\FileStorage\Model\Table\FileStorageTable;
use Burzum\FileStorage\Storage\ImageVersionsTrait;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Validation\Validator;

class AvatarsTable extends FileStorageTable
{
    use ImageVersionsTrait;

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
            ->requirePresence('file', __('A file is required'))
            ->allowEmptyString('file', __('File should not be empty'), false)
            ->add('file', 'validMimeType', [
                'rule' => ['mimeType', ['image/jpeg', 'image/png', 'image/gif']],
            ])
            ->add('file', 'validExtension', [
                'rule' => ['extension', ['png', 'jpg', 'gif']]
            ])
            ->add('file', 'validUploadedFile', [
                'rule' => ['uploadedFile', ['optional' => false]],
                'message' => 'File is no valid uploaded file'
            ]);

        return $validator;
    }

    /**
     * Implements afterSave() callback.
     * Mainly used to delete former versions of avatars
     *
     * @param Event $event the event
     * @param EntityInterface $entity entity
     * @param \ArrayObject $options options
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        // If there was an existing avatar, we delete it.
        $formerAvatar = $this->find()
            ->where([
                'foreign_key' => $entity->foreign_key,
                'id <>' => $entity->id,
                'model' => 'Avatar'
            ])
            ->first();

        if (!empty($formerAvatar)) {
            $this->delete($formerAvatar);
        }
    }

    /**
     * BeforeMarshal callback.
     * It enforces the data related to this model and the adapter to be used.
     *
     * @param Event $event the event
     * @param \ArrayObject $data data
     * @param \ArrayObject $options options
     * @return void
     */
    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        $data['adapter'] = Configure::read('ImageStorage.adapter');
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
