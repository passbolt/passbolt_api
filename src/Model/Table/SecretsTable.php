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
use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use App\Model\Validation\IsNullOnCreateRule;
use App\Service\Secrets\SecretsCleanupHardDeletedPermissionsService;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\DateTime;
use Cake\ORM\Query;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\SecretRevisions\Model\Rule\IsSecretRevisionNotSoftDeletedRule;

/**
 * Secrets Model
 *
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \Passbolt\SecretRevisions\Model\Table\SecretRevisionsTable&\Cake\ORM\Association\BelongsTo $SecretRevisions
 * @method \App\Model\Entity\Secret get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Secret newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Secret[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Secret|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Secret patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Secret[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Secret findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Passbolt\Log\Model\Table\SecretsHistoryTable&\Cake\ORM\Association\BelongsTo $SecretsHistory
 * @property \Passbolt\Log\Model\Table\SecretAccessesTable&\Cake\ORM\Association\HasMany $SecretAccesses
 * @method \App\Model\Entity\Secret newEmptyEntity()
 * @method \App\Model\Entity\Secret saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\Secret>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Secret>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Secret>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Secret>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query\SelectQuery findByResourceId(string $resourceId)
 * @method \Cake\ORM\Query\SelectQuery findByResourceIdAndUserId(string $resourceId, string $userId)
 * @method \Cake\ORM\Query\SelectQuery findByUserId(string $id)
 */
class SecretsTable extends Table implements TableCleanupProviderInterface
{
    use ResourcesCleanupTrait;
    use TableCleanupTrait;
    use UsersCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('secrets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Resources');
        $this->belongsTo('Users');
        $this->belongsTo('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id',
        ]);
        $this->belongsTo('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id',
        ]);
        $this->belongsTo('SecretRevisions', [
            'className' => 'Passbolt/SecretRevisions.SecretRevisions',
        ]);

        $this->addBehavior('Timestamp');
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
            ->uuid('resource_id', __('The resource identifier should be a valid UUID.'))
            ->requirePresence('resource_id', 'create', __('The resource identifier is required.'))
            ->notEmptyString('resource_id', __('The resource identifier should not be empty.'));

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->notEmptyString('user_id', __('The user identifier should not be empty.'));

        $validator
            ->uuid('secret_revision_id', __('The secret revision identifier should be a valid UUID.'))
            ->requirePresence('secret_revision_id', 'create', __('A secret revision identifier is required.'))
            ->notEmptyString('secret_revision_id', __('The secret revision identifier should not be empty.'));

        $validator
            ->ascii('data', __('The message should be a valid ASCII string.'))
            ->requirePresence('data', 'create', __('A message is required.'))
            ->notEmptyString('data', __('The message should not be empty.'))
            ->add('data', 'isValidOpenPGPMessage', new IsParsableMessageValidationRule());

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted')
            ->add('deleted', 'isNullOnCreate', new IsNullOnCreateRule());

        return $validator;
    }

    /**
     * Create resource validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationSaveResource(Validator $validator): Validator
    {
        $validator = $this->validationDefault($validator);

        // The resource_id is added by cake after the resource is created.
        $validator->remove('resource_id');
        // The secret_revision_id is added after the resource was created.
        $validator->remove('secret_revision_id');

        return $validator;
    }

    /**
     * Validation used by the data-check
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     * @see SecretsHealthcheckService
     */
    public function validationHealthcheck(Validator $validator): Validator
    {
        $validator = $this->validationDefault($validator);
        $validator->remove('deleted', 'isNullOnCreate');

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
        // Only one non-deleted secret for the couple (user_id, resource_id)
        $rules->addCreate(
            $rules->isUnique(
                ['user_id', 'resource_id', 'deleted'],
                [
                    'message' => __('A secret already exists for the given user and resource.'),
                    'allowMultipleNulls' => false,
                ]
            ),
            'secret_unique'
        );
        // Only one secret for the combination (user_id, resource_id, secret_revision_id)
        $rules->addCreate(
            $rules->isUnique(
                ['user_id', 'resource_id', 'secret_revision_id'],
                __('A secret already exists for the given user, resource and secret revision.')
            ),
            'secret_revision_unique'
        );
        $rules->addCreate($rules->existsIn(['user_id'], 'Users'), 'user_exists', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);
        $rules->addCreate(new IsSecretRevisionNotSoftDeletedRule(), 'secret_revision_is_not_soft_deleted', [
            'table' => 'Passbolt/SecretRevisions.SecretRevisions',
            'errorField' => 'secret_revision_id',
            'message' => __('The secret revision does not exist or is deleted.'),
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_not_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
        ]);
        $rules->addCreate($rules->existsIn(['resource_id'], 'Resources'), 'resource_exists', [
            'errorField' => 'resource_id',
            'message' => __('The resource does not exist.'),
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'resource_is_not_soft_deleted', [
            'table' => 'Resources',
            'errorField' => 'resource_id',
            'message' => __('The resource does not exist.'),
        ]);
        $rules->addCreate(new HasResourceAccessRule(), 'has_resource_access', [
            'errorField' => 'resource_id',
            'message' => __('Access denied.'),
            'userField' => 'user_id',
            'resourceField' => 'resource_id',
        ]);

        return $rules;
    }

    /**
     * Retrieve a resource secret that belong to a user
     *
     * @param string $resourceId The resource to find the secret for
     * @param string $userId The user to find the secret for
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByResourceUser(string $resourceId, string $userId): SelectQuery
    {
        return $this->find('notDeleted')
            ->where([
                'resource_id' => $resourceId,
                'user_id' => $userId,
            ]);
    }

    /**
     * @param \Cake\ORM\Query $query query
     * @return \Cake\ORM\Query
     */
    public function findNotDeleted(Query $query): Query
    {
        return $query->where(function (QueryExpression $exp) {
            return $exp->isNull($this->aliasField('deleted'));
        });
    }

    /**
     * @param string $resourceId resource ID of the secrets to soft-delete
     * @return int
     */
    public function softDeleteMany(string $resourceId): int
    {
        return $this->updateAll(['deleted' => DateTime::now()], [
            'resource_id' => $resourceId,
            'deleted IS NULL',
        ]);
    }

    /**
     * Delete all records where associated permissions are soft deleted
     *
     * @param bool|null $dryRun default false
     * @return int number of affected records
     */
    public function cleanupHardDeletedPermissions(?bool $dryRun = false): int
    {
        return (new SecretsCleanupHardDeletedPermissionsService())->cleanupHardDeletedPermissions($dryRun);
    }

    /**
     * Retrieves a list of cleanup methods (first-class callables) implemented by this table.
     *
     * @return array<int, callable> List of callables
     */
    public function getCleanupMethods(): array
    {
        return [
            $this->cleanupSoftDeletedUsers(...),
            $this->cleanupHardDeletedUsers(...),
            $this->cleanupSoftDeletedResources(...),
            $this->cleanupHardDeletedResources(...),
            $this->cleanupHardDeletedPermissions(...),
        ];
    }
}
