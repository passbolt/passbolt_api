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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Model\Table;

use App\Error\Exception\ValidationException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Traits\Folders\FoldersFindersTrait;

/**
 * FoldersHistory Model
 *
 * @method Folder get($primaryKey, $options = [])
 * @method Folder newEntity($data = null, array $options = [])
 * @method Folder[] newEntities(array $data, array $options = [])
 * @method Folder|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method Folder saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method Folder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method Folder[] patchEntities($entities, array $data, array $options = [])
 * @method Folder findOrCreate($search, callable $callback = null, $options = [])
 */
class FoldersHistoryTable extends Table
{
    use FoldersFindersTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('folders_history');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasOne('Permission', [
            'className' => 'Permissions',
            'foreignKey' => 'aco_foreign_key',
        ]);
        $this->hasMany('Permissions', [
            'foreignKey' => 'aco_foreign_key',
            'dependent' => false,
        ]);
        $this->hasMany('FoldersRelations', [
            'className' => 'Passbolt/Folders.FoldersRelations',
            'foreignKey' => 'foreign_id',
            'dependent' => false,
        ]);
        $this->belongsToMany('ChildrenFolders', [
            'className' => 'Passbolt/Folders.Folders',
            'targetForeignKey' => 'foreign_id',
            'foreignKey' => 'folder_parent_id',
            'through' => 'Passbolt/Folders.FoldersRelations',
            'dependent' => false,
            'conditions' => [
                'FoldersRelations.foreign_model' => 'Folder',
            ],
        ]);
        $this->belongsToMany('ChildrenResources', [
            'className' => 'Resources',
            'targetForeignKey' => 'foreign_id',
            'foreignKey' => 'folder_parent_id',
            'through' => 'Passbolt/Folders.FoldersRelations',
            'dependent' => false,
            'conditions' => [
                'FoldersRelations.foreign_model' => 'Resource',
            ],
        ]);
        $this->hasOne('EntitiesHistory', [
            'foreignKey' => 'foreign_key',
            'className' => 'Passbolt/Log.EntitiesHistory',
        ]);
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
            ->uuid('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->uuid('folder_id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->utf8Extended('name', __('The name is not a valid utf8 string.'))
            ->maxLength('name', 64, __('The name length should be maximum {0} characters.', 64))
            ->requirePresence('name', 'create', __('A name is required.'))
            ->allowEmptyString('name', __('The name cannot be empty.'), false);

        return $validator;
    }

    /**
     * Return a FoldersHistory entity.
     * @param array $data entity data
     *
     * @return Folder
     */
    public function buildEntity(array $data)
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'folder_id' => true,
                'name' => true,
            ],
        ]);
    }

    /**
     * Create a new FolderHistory.
     *
     * @param array $data the data
     *
     * @return FoldersHistory|bool
     * @throws ValidationException
     * @throws InternalErrorException
     */
    public function create(array $data)
    {
        // Folder.Id becomes FolderHistory.FolderId
        $data['folder_id'] = $data['id'];
        unset($data['id']);

        // Check validation rules.
        $folderHistory = $this->buildEntity($data);
        if (!empty($folderHistory->getErrors())) {
            throw new ValidationException(__('Could not validate folder_history data.', true), $folderHistory, $this);
        }
        $folderHistory = $this->save($folderHistory);

        // Check for validation errors. (associated models too).
        if (!empty($folderHistory->getErrors())) {
            throw new ValidationException(__('Could not validate folder_history data.'), $folderHistory, $this);
        }

        // Check for errors while saving.
        if (!$folderHistory) {
            throw new InternalErrorException(__('The folder_history could not be saved.'));
        }

        return $folderHistory;
    }
}
