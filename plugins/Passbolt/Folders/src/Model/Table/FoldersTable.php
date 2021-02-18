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

use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;
use Passbolt\Folders\Model\Traits\Folders\FoldersFindersTrait;

/**
 * Folders Model
 *
 * @method \Passbolt\Folders\Model\Entity\Folder get($primaryKey, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\Folder newEntity($data = null, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\Folder[] newEntities(array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\Folder|false save(\Cake\Datasource\EntityInterface $entity, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\Folder saveOrFail(\Cake\Datasource\EntityInterface $entity, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\Folder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\Folder[] patchEntities($entities, array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Table\Folder findOrCreate($search, callable $callback = null, ?array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Passbolt\Folders\Model\Behavior\FolderizableBehavior
 */
class FoldersTable extends Table
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

        $this->setTable('folders');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior(TimestampBehavior::class);
        $this->addBehavior(FolderizableBehavior::class);

        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id',
        ]);
        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id',
        ]);
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
            ->uuid('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->utf8Extended('name', __('The name is not a valid utf8 string.'))
            ->maxLength('name', 64, __('The name length should be maximum {0} characters.', 64))
            ->requirePresence('name', 'create', __('A name is required.'))
            ->allowEmptyString('name', __('The name cannot be empty.'), false);

        $validator
            ->uuid('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by', null, false);

        $validator
            ->uuid('modified_by')
            ->requirePresence('modified_by', 'create')
            ->notEmptyString('modified_by', null, false);

        return $validator;
    }

    /**
     * Get a folder created date.
     *
     * @param string $id The folder id
     * @return string
     */
    public function getCreatedDate(string $id)
    {
        return $this->findById($id)
            ->select('created')
            ->extract('created')
            ->first();
    }
}
