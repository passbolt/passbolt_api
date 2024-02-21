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

use App\Model\Rule\HasResourceAccessRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\ResourcesCleanupTrait;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Favorites Model
 *
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\Favorite get($primaryKey, $options = [])
 * @method \App\Model\Entity\Favorite newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Favorite[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Favorite|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Favorite patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Favorite[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Favorite findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Favorite newEmptyEntity()
 * @method \App\Model\Entity\Favorite saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\Favorite>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Favorite>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Favorite>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Favorite>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 */
class FavoritesTable extends Table
{
    use ResourcesCleanupTrait;
    use TableCleanupTrait;
    use UsersCleanupTrait;

    /**
     * List of allowed foreign models on which Favorites can be plugged.
     */
    public const ALLOWED_FOREIGN_MODELS = [
        'Resource',
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

        $this->setTable('favorites');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Resources', [
            'foreignKey' => 'foreign_key',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->notEmptyString('user_id', __('The user identifier should not be empty.'));

        $validator
            ->inList(
                'foreign_model',
                self::ALLOWED_FOREIGN_MODELS,
                __(
                    'The favorite object type should be one of the following: {0}.',
                    implode(', ', self::ALLOWED_FOREIGN_MODELS)
                )
            )
            ->requirePresence('foreign_model', 'create', __('The favorite object type is required.'))
            ->notEmptyString('foreign_model', __('The favorite object type should not be empty'));

        $validator
            ->uuid('foreign_key', __('The favorite object identifier should be a valid UUID.'))
            ->requirePresence('foreign_key', 'create', __('The favorite object identifier is required.'))
            ->notEmptyString('foreign_key', __('The favorite object identifier should not be empty.'));

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
        // Add create rules.
        $rules->addCreate($rules->existsIn('user_id', 'Users'), 'user_exists');
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_not_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);
        $rules->addCreate($rules->existsIn('foreign_key', 'Resources'), 'resource_exists');
        $rules->addCreate(new IsNotSoftDeletedRule(), 'resource_is_not_soft_deleted', [
            'table' => 'Resources',
            'errorField' => 'foreign_key',
            'message' => __('The resource does not exist.'),
        ]);
        $rules->addCreate(new HasResourceAccessRule(), 'has_resource_access', [
            'errorField' => 'foreign_key',
            'message' => __('Access denied.'),
            'userField' => 'user_id',
            'resourceField' => 'foreign_key',
        ]);
        $rules->addCreate(
            $rules->isUnique(
                ['user_id', 'foreign_key'],
                __('The resource is already marked as favorite.')
            ),
            'favorite_unique'
        );

        // Add delete rules.
        $rules->addDelete([$this, 'isOwnerRule'], 'is_owner', [
            'errorField' => 'user_id',
            'message' => __('The user cannot delete this favorite.'),
        ]);

        return $rules;
    }

    /**
     * Validate that the favorite can be deleted by the user who requests the deletion.
     *
     * @param \App\Model\Entity\Favorite $entity The entity that will be deleted.
     * @param array|null $options options
     * @return bool
     */
    public function isOwnerRule(\App\Model\Entity\Favorite $entity, ?array $options = [])
    {
        if ($options['Favorites.user_id'] != $entity->user_id) {
            return false;
        }

        return true;
    }

    /**
     * Delete duplicated favorites
     *
     * @param bool $dryRun false
     * @return int of affected records
     */
    public function cleanupDuplicatedFavorites(?bool $dryRun = false): int
    {
        $keys = ['user_id', 'foreign_key', 'foreign_model'];

        return $this->cleanupDuplicates($keys, $dryRun);
    }
}
