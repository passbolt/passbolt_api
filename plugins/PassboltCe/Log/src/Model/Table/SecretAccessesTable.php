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
 */
namespace Passbolt\Log\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Secret;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Log\Model\Entity\SecretAccess;

/**
 * @property \App\Model\Table\SecretsTable&\Cake\ORM\Association\BelongsTo $Secrets
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $SecretAccessResources
 * @property \Passbolt\Log\Model\Table\EntitiesHistoryTable&\Cake\ORM\Association\BelongsTo $EntitiesHistory
 * @method \Passbolt\Log\Model\Entity\SecretAccess newEmptyEntity()
 * @method \Passbolt\Log\Model\Entity\SecretAccess newEntity(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess get($primaryKey, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretAccess[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SecretAccessesTable extends Table
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

        $this->setTable('secret_accesses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Secrets', [
            'foreignKey' => 'secret_id',
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'resource_id',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->belongsTo('SecretAccessResources', [
            'foreignKey' => 'resource_id',
            'className' => 'Resources',
            'joinType' => 'LEFT',
        ]);
        $this->belongsTo('EntitiesHistory', [
            'className' => 'Passbolt/Log.EntitiesHistory',
            'foreignKey' => 'foreign_key',
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
            ->uuid('resource_id', __('The resource identifier should be a valid UUID.'))
            ->requirePresence('resource_id', 'create', __('A resource identifier is required.'))
            ->notEmptyString('resource_id', __('The resource identifier should not be empty.'));

        $validator
            ->uuid('secret_id', __('The secret identifier should be a valid UUID.'))
            ->requirePresence('secret_id', 'create', __('A secret identifier is required.'))
            ->notEmptyString('secret_id', __('The secret identifier should not be empty.'));

        return $validator;
    }

    /**
     * Return a secret_access entity.
     *
     * @param array $data entity data
     * @return \Passbolt\Log\Model\Entity\SecretAccess
     */
    public function buildEntity(array $data): SecretAccess
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'user_id' => true,
                'resource_id' => true,
                'secret_id' => true,
                'created' => true,
            ],
        ]);
    }

    /**
     * Create a new SecretAccess
     *
     * @param \App\Model\Entity\Secret $secret the secret entity
     * @param \App\Utility\UserAccessControl $uac user access control object
     * @return bool|\Cake\Datasource\EntityInterface|false|mixed
     */
    public function create(Secret $secret, UserAccessControl $uac)
    {
        $userId = $uac->getId();

        $data = [
            'user_id' => $userId,
            'resource_id' => $secret->resource_id,
            'secret_id' => $secret->id,
        ];

        // Check validation rules.
        $secretAccess = $this->buildEntity($data);
        if (!empty($secretAccess->getErrors())) {
            throw new ValidationException(__('Could not validate secret access data.', true), $secretAccess, $this);
        }

        $secretAccessSaved = $this->save($secretAccess);

        // Check for validation errors. (associated models too).
        if (!empty($secretAccess->getErrors())) {
            throw new ValidationException(__('Could not validate secret access data.'), $secretAccess, $this);
        }

        // Check for errors while saving.
        if (!$secretAccessSaved) {
            throw new InternalErrorException('Could not save the secret access.');
        }

        return $secretAccessSaved;
    }
}
