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
use App\Model\Traits\Cleanup\TableCleanupTrait;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Traits\Folders\FoldersRelationsFindersTrait;

/**
 * FoldersRelations Model
 *
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation get($primaryKey, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation newEntity($data = null, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] newEntities(array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation|false save(\Passbolt\Folders\Model\Table\EntityInterface $entity, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation saveOrFail(\Passbolt\Folders\Model\Table\EntityInterface $entity, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation patchEntity(\Passbolt\Folders\Model\Table\EntityInterface $entity, array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] patchEntities($entities, array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation findOrCreate($search, callable $callback = null, ?array $options = [])
 * @mixin \Passbolt\Folders\Model\Table\TimestampBehavior
 */
class FoldersRelationsHistoryTable extends Table
{
    use FoldersRelationsFindersTrait;
    use TableCleanupTrait;

    /**
     * List of allowed item models on which a folder relation can be plugged.
     */
    public const ALLOWED_FOREIGN_MODELS = [
        FoldersRelation::FOREIGN_MODEL_FOLDER,
        FoldersRelation::FOREIGN_MODEL_RESOURCE,
    ];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('folders_relations_history');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Resources', [
            'foreignKey' => 'foreign_id',
        ]);
        $this->belongsTo('Folders', [
            'foreignKey' => 'foreign_id',
        ]);
        $this->belongsTo('FoldersParents', [
            'className' => 'Folders',
            'foreignKey' => 'folder_parent_id',
        ]);
        $this->belongsTo('Users');

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
            ->uuid('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->inList('foreign_model', self::ALLOWED_FOREIGN_MODELS, __(
                'The foreign model must be one of the following: {0}.',
                implode(', ', self::ALLOWED_FOREIGN_MODELS)
            ))
            ->requirePresence('foreign_model', 'create', __('The foreign model is required.'))
            ->notEmptyString('foreign_model', __('The foreign model cannot be empty'));

        $validator
            ->uuid('foreign_id')
            ->requirePresence('foreign_id', 'create', __('The foreign id is required.'))
            ->notEmptyString('foreign_id', __('The foreign id cannot be empty.'), false);

        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id', __('The user id cannot be empty.'), false);

        $validator
            ->uuid('folder_parent_id')
            ->allowEmptyString('folder_parent_id');

        return $validator;
    }

    /**
     * Return a FoldersRelationsHistory entity.
     *
     * @param array $data entity data
     * @return \Passbolt\Folders\Model\Entity\FoldersRelation
     */
    public function buildEntity(array $data): FoldersRelation
    {
        $foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');

        return $foldersRelationsTable->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true,
                'foreign_id' => true,
                'user_id' => true,
                'folder_parent_id' => true,
            ],
        ]);
    }

    /**
     * Create a new FoldersRelationHistory.
     *
     * @param array $data the data
     * @return \Passbolt\Folders\Model\Entity\FoldersRelation
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function create(array $data): FoldersRelation
    {
        // Check validation rules.
        $folderRelationHistory = $this->buildEntity($data);
        if (!empty($folderRelationHistory->getErrors())) {
            $msg = __('Could not validate folders_relations_history data.');
            throw new ValidationException($msg, $folderRelationHistory, $this);
        }
        $folderRelationHistory = $this->save($folderRelationHistory);

        // Check for validation errors. (associated models too).
        if (!empty($folderRelationHistory->getErrors())) {
            $msg = __('Could not validate folders_relations_history data.');
            throw new ValidationException($msg, $folderRelationHistory, $this);
        }

        // Check for errors while saving.
        if (!$folderRelationHistory) {
            throw new InternalErrorException(__('The folders_relations__history could not be saved.'));
        }

        return $folderRelationHistory;
    }
}
