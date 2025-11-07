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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Model\Table;

use App\Model\Validation\IsNullOnCreateRule;
use ArrayObject;
use Cake\Database\Expression\QueryExpression;
use Cake\Event\Event;
use Cake\I18n\DateTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\SecretRevisions\Model\Entity\SecretRevision;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsGetService;

/**
 * SecretRevisions Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Creator
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Modifier
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \Passbolt\ResourceTypes\Model\Table\ResourceTypesTable&\Cake\ORM\Association\BelongsTo $ResourceTypes
 * @property \App\Model\Table\SecretsTable&\Cake\ORM\Association\HasMany $Secrets
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision newEmptyEntity()
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision newEntity(array $data, array $options = [])
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision[] newEntities(array $data, array $options = [])
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision[]|iterable<mixed, \Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision[]|iterable<mixed, \Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision[]|iterable<mixed, \Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\SecretRevisions\Model\Entity\SecretRevision[]|iterable<mixed, \Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SecretRevisionsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('secret_revisions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Resources');

        $this->belongsTo('ResourceTypes', [
            'className' => 'Passbolt/ResourceTypes.ResourceTypes',
        ]);

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

        $this->hasMany('Secrets');

        /**
         * The contains below are a duplicate of above. They are named differently so that we can easily put specific conditions in deeply nested associations.
         */
        $this->belongsTo('SecretRevisionsResources', [
            'foreignKey' => 'resource_id',
            'className' => 'Resources',
            'joinType' => 'LEFT',
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
            ->uuid('resource_id', __('The resource identifier should be a valid UUID.'))
            ->requirePresence('resource_id', 'create', __('The resource identifier is required.'))
            ->notEmptyString('resource_id', __('The resource identifier should not be empty.'));

        $validator
            ->uuid('resource_type_id', __('The resource type identifier should be a valid UUID.'))
            ->requirePresence('resource_type_id', 'create', __('A resource type identifier is required.'));

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted')
            ->add('deleted', 'isNullOnCreate', new IsNullOnCreateRule());

        $validator
            ->uuid('created_by', __('The identifier of the user who created the secret revision should be a valid UUID.')) // phpcs:ignore;
            ->allowEmptyString('created_by');

        $validator
            ->uuid('modified_by', __('The identifier of the user who modified the secret revision should be a valid UUID.')) // phpcs:ignore;
            ->allowEmptyString('modified_by');

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
        $rules->add($rules->existsIn('resource_id', 'Resources'), ['errorField' => 'resource_id']);

        $rules->add($rules->existsIn('resource_type_id', 'ResourceTypes'), 'resource_type_exists', [
            'message' => __('The resource type does not exist.'),
        ]);

        return $rules;
    }

    /**
     * Get the SecretRevision settings and trim all the secret revisions
     * above the threshold and their secrets
     *
     * @param \Cake\Event\Event $event the event
     * @param \Passbolt\SecretRevisions\Model\Entity\SecretRevision $secretRevision entity
     * @param \ArrayObject $options options
     * @return void
     */
    public function afterSave(Event $event, SecretRevision $secretRevision, ArrayObject $options): void
    {
        $maxRevisions = SecretRevisionsSettingsGetService::getSettings()->getMaxRevisions();
        $resourceId = $secretRevision->resource_id;
        $revisionsToKeep = $this->find('list')
            ->select('id')
            ->where(['resource_id' => $resourceId])
            ->limit($maxRevisions)
            ->orderByDesc($this->subquery()->newExpr()->isNull('deleted'))
            ->orderByDesc('deleted')
            ->disableHydration()
            ->all()
            ->toList();

        // There is no reason for the list above to be empty, but just in case:
        if (empty($revisionsToKeep)) {
            return;
        }
        // In this case we know that the total number of revisions is smaller than the max allowed
        if (count($revisionsToKeep) < $maxRevisions) {
            return;
        }

        // Delete the revisions that are for that resource, but too old and thus not in the short list
        // Ensure that only soft deleted revisions are flushed
        $this->deleteQuery()->where([
            'id NOT IN' => $revisionsToKeep,
            'resource_id' => $resourceId,
            'deleted IS NOT NULL',
        ])->execute();
        // Delete the secrets that are for that resource, but not associated to the revisions kept
        // Ensure that only soft deleted secrets are flushed
        $this->Secrets->deleteQuery()->where([
            'secret_revision_id NOT IN' => $revisionsToKeep,
            'resource_id' => $resourceId,
            'deleted IS NOT NULL',
        ])->execute();
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
     * @param string $resourceId resource ID of the secret revision to soft-delete
     * @return int
     */
    public function softDelete(string $resourceId): int
    {
        $this->Secrets->softDeleteMany($resourceId);

        return $this->updateAll(['deleted' => DateTime::now()], [
            'resource_id' => $resourceId,
            'deleted IS NULL',
        ]);
    }
}
