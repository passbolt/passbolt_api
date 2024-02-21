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

use App\Model\Entity\Role;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use Passbolt\Folders\Model\Dto\FolderRelationDto;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Traits\FoldersRelations\FoldersRelationsFindersTrait;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService;

/**
 * FoldersRelations Model
 *
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \Passbolt\Folders\Model\Table\FoldersTable&\Cake\ORM\Association\BelongsTo $Folders
 * @property \Passbolt\Folders\Model\Table\FoldersTable&\Cake\ORM\Association\BelongsTo $FoldersParents
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \Passbolt\Folders\Model\Table\FoldersRelationsHistoryTable&\Cake\ORM\Association\BelongsTo $FoldersRelationsHistory
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation get($primaryKey, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation newEntity(array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Folders\Model\Entity\FoldersRelation newEmptyEntity()
 * @method iterable<\Passbolt\Folders\Model\Entity\FoldersRelation>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Folders\Model\Entity\FoldersRelation>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Folders\Model\Entity\FoldersRelation>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Folders\Model\Entity\FoldersRelation>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findByForeignId(string $id)
 * @method \Cake\ORM\Query findById(string $id)
 * @method \Cake\ORM\Query findByUserId(string $userId)
 * @method \Cake\ORM\Query findByFolderParentId(string $folderParentId)
 * @method \Cake\ORM\Query findByUserIdAndForeignModel(string $userId, string $foreignModel)
 * @method \Cake\ORM\Query findByForeignIdAndFolderParentId(string $foreignId, string $folderParentId)
 * @method \Cake\ORM\Query findByUserIdAndFolderParentId(string $userId, string $folderParentId)
 * @method \Cake\ORM\Query findMissingFoldersRelations(string $foreignModel)
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FoldersRelationsTable extends Table
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

        $this->setTable('folders_relations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Resources', [
            'foreignKey' => 'foreign_id',
        ]);
        $this->belongsTo('Passbolt/Folders.Folders', [
            'foreignKey' => 'foreign_id',
        ]);
        $this->belongsTo('Passbolt/Folders.FoldersParents', [
            'className' => 'Passbolt/Folders.Folders',
            'foreignKey' => 'folder_parent_id',
        ]);
        $this->belongsTo('Users');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): \Cake\Validation\Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->inList('foreign_model', self::ALLOWED_FOREIGN_MODELS, __(
                'The child object type should be one of the following: {0}.',
                implode(', ', self::ALLOWED_FOREIGN_MODELS)
            ))
            ->requirePresence('foreign_model', 'create', __('The child object type is required.'))
            ->notEmptyString('foreign_model', __('The child object type should not be empty.'));

        $validator
            ->uuid('foreign_id', __('The child object identifier should be a valid UUID.'))
            ->requirePresence('foreign_id', 'create', __('The child object identifier required.'))
            ->notEmptyString('foreign_id', __('The child object identifier should not be empty.'), false);

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->notEmptyString('user_id', __('The user identifier should not be empty.'), false);

        $validator
            ->uuid('folder_parent_id', __('The folder parent identifier should be a valid UUID.'))
            ->allowEmptyString('folder_parent_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): \Cake\ORM\RulesChecker
    {
        $rules->addCreate(
            $rules->isUnique(
                ['foreign_id', 'user_id'],
                __('A folder relation already exists for the given child object and user.')
            ),
            'folder_relation_unique'
        );
        $rules->addCreate([$this, 'foreignIdExistsRule'], 'foreign_model_exists', [
            'errorField' => 'foreign_id',
            'message' => __('The child object does not exist.'),
        ]);
        $rules->addCreate($rules->existsIn(['user_id'], 'Users'), 'user_exists', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_not_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);

        return $rules;
    }

    /**
     * Checks that the foreign id exists
     *
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $foldersRelation The folder_relation to test
     * @param array $options The additional options for this rule
     * @return bool
     */
    public function foreignIdExistsRule(FoldersRelation $foldersRelation, array $options): bool
    {
        $rules = new RulesChecker($options);
        $exist = false;

        switch ($foldersRelation->foreign_model) {
            case FoldersRelation::FOREIGN_MODEL_RESOURCE:
                $rule = $rules->existsIn('foreign_id', 'Resources');
                $existIn = $rule($foldersRelation, $options);
                $rule = new IsNotSoftDeletedRule();
                $isNotSoftDeleted = $rule($foldersRelation, [
                    'table' => 'Resources',
                    'errorField' => 'foreign_id',
                ]);
                $exist = $existIn && $isNotSoftDeleted;
                break;
            case FoldersRelation::FOREIGN_MODEL_FOLDER:
                $rule = $rules->existsIn('foreign_id', 'Folders');
                $exist = $rule($foldersRelation, $options);
                break;
        }

        return $exist;
    }

    /**
     * Delete all records where associated users are soft deleted
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupSoftDeletedUsers(?bool $dryRun = false): int
    {
        return $this->cleanupSoftDeleted('Users', $dryRun);
    }

    /**
     * Delete all records where associated users are deleted
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupHardDeletedUsers(?bool $dryRun = false): int
    {
        return $this->cleanupHardDeleted('Users', $dryRun);
    }

    /**
     * Delete all records where associated resources are soft deleted
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupSoftDeletedResources(?bool $dryRun = false): int
    {
        return $this->cleanupSoftDeletedForeignId('Resources', $dryRun);
    }

    /**
     * Delete all association records where associated model entities are soft deleted
     *
     * @param string $modelName model
     * @param bool $dryRun false
     * @return int of affected records
     */
    private function cleanupSoftDeletedForeignId(string $modelName, ?bool $dryRun = false): int
    {
        $query = $this->selectQuery()
            ->select(['id'])
            ->leftJoinWith($modelName)
            ->where([
                "$modelName.deleted" => true,
                'FoldersRelations.foreign_model' => ucfirst(Inflector::singularize($modelName)),
            ]);

        return $this->cleanupHardDeleted($modelName, $dryRun, $query);
    }

    /**
     * Delete all records where associated resources are deleted
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupHardDeletedResources(?bool $dryRun = false): int
    {
        return $this->cleanupHardDeletedForeignId('Resources', $dryRun);
    }

    /**
     * Delete all association records where associated model entities are deleted
     *
     * @param string $modelName model
     * @param bool $dryRun false
     * @return int of affected records
     */
    private function cleanupHardDeletedForeignId(string $modelName, $dryRun = false): int
    {
        $query = $this->selectQuery()
            ->select(['id'])
            ->leftJoinWith($modelName)
            ->whereNull($modelName . '.id')
            ->where(['FoldersRelations.foreign_model' => ucfirst(Inflector::singularize($modelName)),]);

        return $this->cleanupHardDeleted($modelName, $dryRun, $query);
    }

    /**
     * Delete all records where associated folders are deleted
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupHardDeletedFolders(?bool $dryRun = false): int
    {
        return $this->cleanupHardDeletedForeignId('Folders', $dryRun);
    }

    /**
     * Move to root all folders relations where associated folders parents are deleted
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupHardDeletedFoldersParents(?bool $dryRun = false): int
    {
        $query = $this->findByDeletedFolderParent()
            ->select('id');

        return $this->cleanupHardDeleted('FoldersParents', $dryRun, $query);
    }

    /**
     * Add missing folders relations for each resource the users have access to.
     *
     * @param bool $dryRun false
     * @return int of affected records
     * @throws \Exception If something unexpected occurred
     */
    public function cleanupMissingResourcesFoldersRelations(?bool $dryRun = false): int
    {
        return $this->cleanupMissingFoldersRelations(FoldersRelation::FOREIGN_MODEL_RESOURCE, $dryRun);
    }

    /**
     * Add a folder relation for each item users have access but don't have it in their trees.
     *
     * @param string $foreignModel The type of item. Can be Folder or Resource
     * @param bool $dryRun false
     * @return int of affected records
     * @throws \Exception If something unexpected occurred
     */
    public function cleanupMissingFoldersRelations(string $foreignModel, ?bool $dryRun = false): int
    {
        $admin = $this->Users->findFirstAdmin();
        $uac = new UserAccessControl(Role::ADMIN, $admin->id);
        $addItemsToUserTreeService = new FoldersRelationsAddItemsToUserTreeService();

        $missingFoldersRelations = $this->findMissingFoldersRelations($foreignModel)->all();
        if (!$dryRun) {
            $items = [];
            foreach ($missingFoldersRelations as $missingFolderRelation) {
                $folderRelationToCreateDto = new FolderRelationDto($foreignModel, $missingFolderRelation['foreign_id']);
                $items[$missingFolderRelation['user_id']][] = $folderRelationToCreateDto;
            }

            foreach ($items as $userId => $userItems) {
                $addItemsToUserTreeService->addItemsToUserTree($uac, $userId, $userItems);
            }
        }

        return count($missingFoldersRelations);
    }

    /**
     * Delete duplicated folders relations
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupDuplicatedFoldersRelations(?bool $dryRun = false): int
    {
        $keys = ['user_id', 'foreign_model', 'foreign_id', 'folder_parent_id'];

        return $this->cleanupDuplicates($keys, $dryRun);
    }

    /**
     * Check if an item is in a user tree.
     *
     * @param string $userId The target user
     * @param string $foreignId The target item id
     * @param string|null $foreignModel The target item foreign model. If not given, the test won't check the the item
     * model.
     * @return bool
     */
    public function isItemInUserTree(string $userId, string $foreignId, ?string $foreignModel = null): bool
    {
        $conditions = ['foreign_id' => $foreignId, 'user_id' => $userId];

        if (!is_null($foreignModel)) {
            $conditions['foreign_model'] = $foreignModel;
        }

        return $this->exists($conditions);
    }

    /**
     * Add missing folders relations for each folder the users have access to.
     *
     * @param bool $dryRun false
     * @return int of affected records
     * @throws \Exception If something unexpected occurred
     */
    public function cleanupMissingFoldersFoldersRelations(?bool $dryRun = false): int
    {
        return $this->cleanupMissingFoldersRelations(FoldersRelation::FOREIGN_MODEL_FOLDER, $dryRun);
    }

    /**
     * Count the number of occurrences of a given relation.
     *
     * @param string $foreignId The relation child id
     * @param string|null $folderParentId The relation parent id
     * @return int
     */
    public function countRelationUsage(string $foreignId, ?string $folderParentId = FoldersRelation::ROOT): int
    {
        $conditions = [
            'foreign_id' => $foreignId,
            'folder_parent_id' => $folderParentId,
        ];

        return $this->find()
            ->where($conditions)
            ->count();
    }

    /**
     * Get an item folder parent id in a user tree.
     *
     * @param string $userId The target user to look for
     * @param string $foreignId The item identifier
     * @return string|null
     */
    public function getItemFolderParentIdInUserTree(string $userId, string $foreignId): ?string
    {
        $foldersParentIds = $this->getItemFoldersParentIdsInUsersTrees([$userId], $foreignId);

        $parent = reset($foldersParentIds);
        if ($parent === false) {
            return null;
        }

        return $parent;
    }

    /**
     * Get an item folders parent ids in multiple users trees.
     *
     * @param array $usersIds The list of users to get the item folder parent id
     * @param string $foreignId The target entity id
     * @param bool $excludeRoot Exclude the root folder. Default false.
     * @return array
     */
    public function getItemFoldersParentIdsInUsersTrees(
        array $usersIds,
        string $foreignId,
        ?bool $excludeRoot = false
    ): array {
        $conditions = [
            'user_id IN' => $usersIds,
            'foreign_id' => $foreignId,
        ];

        if ($excludeRoot) {
            $conditions[] = 'folder_parent_id IS NOT NULL';
        }

        return $this->find()
            ->where($conditions)
            ->select('folder_parent_id')
            ->distinct('folder_parent_id')
            ->all()
            ->extract('folder_parent_id')
            ->toArray();
    }

    /**
     * Return a list of users ids having access to a list of items.
     *
     * @param array $foreignIds The list of items to check for.
     * @return array
     */
    public function getUsersIdsHavingAccessToMultipleItems(array $foreignIds): array
    {
        if (empty($foreignIds)) {
            throw new InternalErrorException('The foreignIds parameter cannot be empty.');
        }

        $itemsCount = count($foreignIds);

        return $this->find()
            ->select(['user_id'])
            ->where(['foreign_id IN' => $foreignIds])
            ->group('user_id')
            ->having("count(user_id) = $itemsCount")
            ->all()
            ->extract('user_id')
            ->toArray();
    }

    /**
     * Check if an item is personal.
     *
     * @param string|null $foreignId The item id
     * @return bool
     */
    public function isItemPersonal(?string $foreignId = null): bool
    {
        if (is_null($foreignId)) {
            return false;
        }

        return $this->findByForeignId($foreignId)
                ->count() === 1;
    }

    /**
     * Move an item from multiple locations to a target location.
     *
     * @param string $foreignId The target item
     * @param array $fromFoldersIds The list of folders ids to move from
     * @param string|null $folderParentId (optional) The destination folder location. Set it to null to null to move the
     * item to the root. Default null.
     * @return void
     */
    public function moveItemFrom(string $foreignId, array $fromFoldersIds, ?string $folderParentId = null): void
    {
        $fields = [
            'folder_parent_id' => $folderParentId,
        ];
        $conditions = [
            'foreign_id' => $foreignId,
            'folder_parent_id IN' => $fromFoldersIds,
        ];
        $this->updateAll($fields, $conditions);
    }

    /**
     * Move an item for users from wherever they are to a target location
     * .
     *
     * @param string $foreignId The target item
     * @param array $forUsersIds The list of users to move the item for
     * @param string|null $folderParentId The destination folder
     * @return void
     */
    public function moveItemFor(string $foreignId, array $forUsersIds, ?string $folderParentId = null): void
    {
        if (empty($forUsersIds)) {
            return;
        }

        $fields = [
            'folder_parent_id' => $folderParentId,
        ];
        $conditions = [
            'foreign_id' => $foreignId,
            'user_id IN' => $forUsersIds,
        ];
        $this->updateAll($fields, $conditions);
    }
}
