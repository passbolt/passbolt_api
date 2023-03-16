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
use Passbolt\Folders\Model\Traits\FoldersRelations\FoldersRelationsFindersTrait;

/**
 * FoldersRelations Model
 *
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation get($primaryKey, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation newEntity($data = null, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] newEntities(array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] patchEntities($entities, array $data, ?array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation findOrCreate($search, callable $callback = null, ?array $options = [])
 * @method \Cake\ORM\Query findById(string $id)
 * @method \Cake\ORM\Query findByForeignId(string $id)
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \Cake\ORM\Table&\Cake\ORM\Association\BelongsTo $Folders
 * @property \Cake\ORM\Table&\Cake\ORM\Association\BelongsTo $FoldersParents
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \Passbolt\Log\Model\Table\EntitiesHistoryTable&\Cake\ORM\Association\HasOne $EntitiesHistory
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
    public function initialize(array $config): void
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
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->inList('foreign_model', self::ALLOWED_FOREIGN_MODELS, __(
                'The child object type should be one of the following: {0}.',
                implode(', ', self::ALLOWED_FOREIGN_MODELS)
            ))
            ->requirePresence('foreign_model', 'create', __('A child object type is required.'))
            ->notEmptyString('foreign_model', __('The child object type cannot be empty.'));

        $validator
            ->uuid('foreign_id', __('The child object identifier should be a valid UUID.'))
            ->requirePresence('foreign_id', 'create', __('The child object identifier is required.'))
            ->notEmptyString('foreign_id', __('The child object identifier should not be empty.'), false);

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->notEmptyString('user_id', __('The user id should not be empty.'), false);

        $validator
            ->uuid('folder_parent_id', __('The folder parent identifier should be a valid UUID.'))
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
        /** @var \Passbolt\Folders\Model\Table\FoldersRelationsTable $foldersRelationsTable */
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
            $msg = __('Could not validate folders relations history data.');
            throw new ValidationException($msg, $folderRelationHistory, $this);
        }
        $folderRelationHistory = $this->save($folderRelationHistory);

        // Check for errors while saving.
        if (!$folderRelationHistory) {
            throw new InternalErrorException('Could not save the folder relation history.');
        }

        // Check for validation errors. (associated models too).
        if (!empty($folderRelationHistory->getErrors())) {
            $msg = __('Could not validate folders relations history data.');
            throw new ValidationException($msg, $folderRelationHistory, $this);
        }

        return $folderRelationHistory;
    }
}
