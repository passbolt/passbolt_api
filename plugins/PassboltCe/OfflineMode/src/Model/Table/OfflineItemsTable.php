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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Model\Table;

use App\Model\Rule\HasResourceAccessRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Rule\User\IsActiveUserRule;
use App\Model\Table\TableCleanupProviderInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Passbolt\OfflineMode\Model\Rule\IsForeignItemV5Rule;
use Passbolt\OfflineMode\Model\Rule\MaxOfflineItemsRule;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;

/**
 * OfflineItems Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Creator
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem newEmptyEntity()
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem newEntity(array $data, array $options = [])
 * @method array<\Passbolt\OfflineMode\Model\Entity\OfflineItem> newEntities(array $data, array $options = [])
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, callable|array|null $callback = null, array $options = [])
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Passbolt\OfflineMode\Model\Entity\OfflineItem> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Passbolt\OfflineMode\Model\Entity\OfflineItem saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\Passbolt\OfflineMode\Model\Entity\OfflineItem>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\OfflineMode\Model\Entity\OfflineItem> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\OfflineMode\Model\Entity\OfflineItem>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\OfflineMode\Model\Entity\OfflineItem> deleteManyOrFail(iterable $entities, array $options = [])
 */
class OfflineItemsTable extends Table implements TableCleanupProviderInterface
{
    public const FOREIGN_MODEL_RESOURCE = 'Resource';

    /**
     * Allowed `foreign_model` values. Phase 1 only ships
     * `FOREIGN_MODEL_RESOURCE`; `Folder` lands in a later phase.
     */
    public const ALLOWED_FOREIGN_MODELS = [
        self::FOREIGN_MODEL_RESOURCE,
    ];

    /**
     * @param array $config Configuration passed by the registry.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('offline_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                ],
            ],
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->belongsTo('Creator', [
            'className' => 'Users',
            'foreignKey' => 'created_by',
        ]);

        $this->belongsTo('Resources', [
            'foreignKey' => 'foreign_key',
        ]);
    }

    /**
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
                    'The offline item object type should be one of the following: {0}.',
                    implode(', ', self::ALLOWED_FOREIGN_MODELS),
                ),
            )
            ->requirePresence('foreign_model', 'create', __('The offline item object type is required.'))
            ->notEmptyString('foreign_model', __('The offline item object type should not be empty.'));

        $validator
            ->uuid('foreign_key', __('The offline item object identifier should be a valid UUID.'))
            ->requirePresence('foreign_key', 'create', __('The offline item object identifier is required.'))
            ->notEmptyString('foreign_key', __('The offline item object identifier should not be empty.'));

        // `created` is stamped by TimestampBehavior on `Model.beforeSave`; it
        // never reaches the validator on a normal save path. The DB NOT NULL
        // constraint backs the invariant. Only format-check, no requirePresence.
        $validator
            ->dateTime('created', ['ymd'], __('The created date should be a valid date.'))
            ->allowEmptyDateTime('created');

        $validator
            ->uuid('created_by', __('The creator identifier should be a valid UUID.'))
            ->requirePresence('created_by', 'create', __('A creator identifier is required.'))
            ->notEmptyString('created_by', __('The creator identifier should not be empty.'));

        return $validator;
    }

    /**
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate($rules->existsIn('user_id', 'Users'), 'user_exists', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);
        $rules->addCreate(new IsActiveUserRule(), 'user_is_active', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);
        $rules->addCreate(
            $rules->existsIn('foreign_key', 'Resources'),
            'resource_exists',
            ['errorField' => 'foreign_key', 'message' => __('The resource does not exist.')],
        );
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
        $rules->addCreate(new IsForeignItemV5Rule(), 'offline_item_is_v5', [
            'errorField' => 'foreign_key',
            'message' => __('Offline mode is only available for v5 items.'),
        ]);
        $rules->addCreate(new MaxOfflineItemsRule(), 'max_items', [
            'errorField' => 'max_items',
            'message' => __('You have reached the maximum number of offline items.'),
        ]);
        $rules->addCreate(
            $rules->isUnique(
                ['user_id', 'foreign_model', 'foreign_key'],
                __('This item is already available offline for this user.'),
            ),
            'offline_item_unique',
        );

        return $rules;
    }

    /**
     * @inheritDoc
     */
    public function getCleanupMethods(): array
    {
        return [
            $this->cleanupOfflineItemsForRbacDeniedRoles(...),
        ];
    }

    /**
     * Deletes offline_items rows whose owning user's role has `OfflineItemsView.view` = Deny in rbacs.
     *
     * @param bool|null $dryRun If dry run mode or not.
     * @return int No of affected rows or in dry-run mode number of selected rows.
     */
    public function cleanupOfflineItemsForRbacDeniedRoles(?bool $dryRun = false): int
    {
        $query = $this->selectQuery();
        $query
            ->select(['OfflineItems.id'])
            ->innerJoin(
                ['Users' => 'users'],
                [
                    $query->expr()->equalFields('Users.id', 'OfflineItems.user_id'),
                    'Users.deleted' => false,
                ],
                ['Users.deleted' => 'boolean'],
            )
            ->innerJoin(
                ['Rbacs' => 'rbacs'],
                [
                    $query->expr()->equalFields('Rbacs.role_id', 'Users.role_id'),
                    'Rbacs.foreign_model' => Rbac::FOREIGN_MODEL_ACTION,
                    'Rbacs.control_function' => Rbac::CONTROL_FUNCTION_DENY,
                ],
            )
            ->innerJoin(
                ['Actions' => 'actions'],
                [
                    $query->expr()->equalFields('Actions.id', 'Rbacs.foreign_id'),
                    'Actions.name' => RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_VIEW,
                ],
            );

        $rows = $query->disableHydration()->toArray();
        if (empty($rows)) {
            return 0;
        }

        $ids = Hash::extract($rows, '{n}.id');
        if ($dryRun) {
            return count($ids);
        }

        if (!is_array($ids) || empty($ids)) {
            return 0;
        }

        return $this->deleteAll(['id IN' => $ids]);
    }
}
