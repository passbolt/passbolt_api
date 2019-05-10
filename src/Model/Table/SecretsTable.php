<?php
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

use App\Error\Exception\CustomValidationException;
use App\Model\Rule\HasResourceAccessRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\PermissionsCleanupTrait;
use App\Model\Traits\Cleanup\ResourcesCleanupTrait;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Secrets Model
 *
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Secret get($primaryKey, $options = [])
 * @method \App\Model\Entity\Secret newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Secret[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Secret|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Secret patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Secret[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Secret findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SecretsTable extends Table
{

    use PermissionsCleanupTrait;
    use ResourcesCleanupTrait;
    use TableCleanupTrait;
    use UsersCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->uuid('id')
            ->allowEmpty('id', 'create');

        $validator
            ->uuid('resource_id')
            ->requirePresence('resource_id', 'create')
            ->notEmpty('resource_id');

        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmpty('user_id');

        $validator
            ->ascii('data', __('The message is not a valid armored gpg message.'))
            ->add('data', 'isValidGpgMessage', [
                'rule' => [$this, 'isValidGpgMessageRule'],
                'message' => __('The message is not a valid armored gpg message.')
            ])
            ->requirePresence('data', 'create', __('A message is required.'))
            ->notEmpty('data', __('The message cannot be empty.'));

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
     * Check true if field is a valid gpg message.
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function isValidGpgMessageRule(string $check, array $context)
    {
        $gpg = OpenPGPBackendFactory::get();

        return $gpg->isValidMessage($check);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
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
            'message' => __('The user does not exist.')
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_not_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user does not exist.')
        ]);
        $rules->addCreate($rules->existsIn(['resource_id'], 'Resources'), 'resource_exists', [
            'errorField' => 'resource_id',
            'message' => __('The resource does not exist.')
        ]);
        $rules->addCreate(new IsNotSoftDeletedRule(), 'resource_is_not_soft_deleted', [
            'table' => 'Resources',
            'errorField' => 'resource_id',
            'message' => __('The resource does not exist.')
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
     * Patch a list of secrets entities with a list of secrets to add and a list of users for whom the secrets
     * must be deleted.
     *
     * @param string $resourceId The resource identifier the secrets belong to
     * @param array $entities The list of secrets entities to patch
     * @param array $add The list of secrets to add
     * @param array $delete The list of user identifies for whom the secrets must be deleted
     * @throw CustomValidationException If a user identifier for whom a secret has to deleted is not found in the list
     *   of secrets
     * @throw CustomValidationException If a secret to add does not validate when calling newEntity
     * @return array The list of secrets entities patched with the changes
     */
    public function patchEntitiesWithChanges(string $resourceId, array $entities, array $add = [], array $delete = [])
    {
        // Remove secrets from the list.
        foreach ($delete as $userId) {
            $secretKey = array_search($userId, array_column($entities, 'user_id'));
            // The user_id does not have a secret.
            if ($secretKey === false) {
                $errors = ['secret_exists' => __('There is no secret to delete for the user {0}.', $userId)];
                throw new CustomValidationException(__('Validation error.'), $errors);
            }
            array_splice($entities, $secretKey, 1);
        }

        // Add the new secrets to the list.
        foreach ($add as $addKey => $secret) {
            // Enforce data.
            $secret['resource_id'] = $resourceId;
            // New entity options.
            $options = ['accessibleFields' => [
                'resource_id' => true,
                'user_id' => true,
                'data' => true
            ]];
            // Create and validate the new secret entity.
            $secret = $this->newEntity($secret, $options);
            $errors = $secret->getErrors();
            if (!empty($errors)) {
                throw new CustomValidationException(__('Validation error.'), [$addKey => $errors]);
            }
            $entities[] = $secret;
        }

        return $entities;
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
                'user_id' => $userId
            ]);
    }

    /**
     * Retrieve all the resources secrets that belong to a given user
     *
     * @param array $resourcesIds The list of resources to find the secrets for
     * @param string $userId The user to find the secrets for
     * @return \Cake\ORM\Query
     */
    public function findByResourcesUser(array $resourcesIds, string $userId)
    {
        return $this->find()
            ->where([
                'resource_id IN' => $resourcesIds,
                'user_id' => $userId
            ]);
    }
}
