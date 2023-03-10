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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Model\Table;

use App\Error\Exception\ValidationException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FolderHistory;
use Passbolt\Folders\Model\Traits\Folders\FoldersFindersTrait;

/**
 * FoldersHistory Model
 *
 * @method \Passbolt\Folders\Model\Entity\FolderHistory get($primaryKey, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FolderHistory newEntity($data = null, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FolderHistory[] newEntities(array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FolderHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FolderHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FolderHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FolderHistory[] patchEntities($entities, array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FolderHistory findOrCreate($search, callable $callback = null, ?array $options = [])
 * @property \App\Model\Table\PermissionsTable&\Cake\ORM\Association\HasOne $Permission
 * @property \Passbolt\Log\Model\Table\EntitiesHistoryTable&\Cake\ORM\Association\HasOne $EntitiesHistory
 * @property \App\Model\Table\PermissionsTable&\Cake\ORM\Association\HasMany $Permissions
 * @property \Passbolt\Folders\Model\Table\FoldersRelationsTable&\Cake\ORM\Association\HasMany $FoldersRelations
 * @property \Passbolt\Folders\Model\Table\FoldersTable&\Cake\ORM\Association\BelongsToMany $ChildrenFolders
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsToMany $ChildrenResources
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
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('folders_history');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->setEntityClass(FolderHistory::class);

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
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->uuid('folder_id', __('The folder identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The folder identifier should not be empty.'), 'create');

        $validator
            ->utf8Extended('name', __('The name should be a valid UTF8 string.'))
            ->maxLength(
                'name',
                Folder::MAX_NAME_LENGTH,
                __('The name length should be maximum {0} characters.', Folder::MAX_NAME_LENGTH)
            )
            ->requirePresence('name', 'create', __('A name is required.'))
            ->allowEmptyString('name', __('The name should not be empty.'), false);

        return $validator;
    }

    /**
     * Return a FoldersHistory entity.
     *
     * @param array $data entity data
     * @return \Passbolt\Folders\Model\Entity\FolderHistory
     */
    public function buildEntity(array $data): FolderHistory
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
     * @return \Passbolt\Folders\Model\Entity\FolderHistory
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function create(array $data): FolderHistory
    {
        // Folder.Id becomes FolderHistory.FolderId
        $data['folder_id'] = $data['id'];
        unset($data['id']);

        // Check validation rules.
        $folderHistory = $this->buildEntity($data);
        if (!empty($folderHistory->getErrors())) {
            throw new ValidationException(__('Could not validate folder history data.', true), $folderHistory, $this);
        }
        $folderHistory = $this->save($folderHistory);

        // Check for errors while saving.
        if (!$folderHistory) {
            throw new InternalErrorException('Could not save the folder history.');
        }

        // Check for validation errors. (associated models too).
        if (!empty($folderHistory->getErrors())) {
            throw new ValidationException(__('Could not validate folder history data.'), $folderHistory, $this);
        }

        return $folderHistory;
    }
}
