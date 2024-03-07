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
use App\Service\Secrets\SecretsCleanupHardDeletedPermissionsService;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Secrets Model
 *
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\Secret get($primaryKey, $options = [])
 * @method \App\Model\Entity\Secret newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Secret[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Secret|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Secret patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Secret[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Secret findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Passbolt\Log\Model\Table\SecretsHistoryTable&\Cake\ORM\Association\BelongsTo $SecretsHistory
 * @property \Passbolt\Log\Model\Table\SecretAccessesTable&\Cake\ORM\Association\HasMany $SecretAccesses
 * @method \App\Model\Entity\Secret newEmptyEntity()
 * @method \App\Model\Entity\Secret saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\Secret>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Secret>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Secret>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\App\Model\Entity\Secret>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @method \Cake\ORM\Query findByResourceId(string $resourceId)
 * @method \Cake\ORM\Query findByResourceIdAndUserId(string $resourceId, string $userId)
 * @method \Cake\ORM\Query findByUserId(string $id)
 */
class SecretsTable extends Table
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
            ->ascii('data', __('The message should be a valid ASCII string.'))
            ->requirePresence('data', 'create', __('A message is required.'))
            ->notEmptyString('data', __('The message should not be empty.'))
            ->add('data', 'isValidOpenPGPMessage', new IsParsableMessageValidationRule());

        return $validator;
    }

    /**
     * Create resource validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationSaveResource(Validator $validator)
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
        $rules->addCreate(
            $rules->isUnique(
                ['user_id', 'resource_id'],
                __('A secret already exists for the given user and resource.')
            ),
            'secret_unique'
        );
        $rules->addCreate($rules->existsIn(['user_id'], 'Users'), 'user_exists', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist.'),
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
     * @return \Cake\ORM\Query
     */
    public function findByResourceUser(string $resourceId, string $userId)
    {
        return $this->find()
            ->where([
                'resource_id' => $resourceId,
                'user_id' => $userId,
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
}
