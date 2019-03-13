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
 */

namespace App\Model\Table;

use App\Model\Entity\Secret;
use App\Model\Entity\SecretAccess;
use App\Model\Rule\HasResourceAccessRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use App\Model\Traits\Cleanup\ResourcesCleanupTrait;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Traits\Cleanup\UsersCleanupTrait;
use App\Utility\UserAccessControl;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class SecretAccessesTable
 * @package App\Model\Table
 */
class SecretAccessesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('secret_accesses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Secrets', [
            'foreignKey' => 'secret_id'
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'resource_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('SecretAccessResources', [
            'foreignKey' => 'resource_id',
            'className' => 'Resources',
            'joinType' => 'LEFT'
        ]);
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
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmpty('user_id');

        $validator
            ->uuid('resource_id')
            ->requirePresence('resource_id', 'create')
            ->notEmpty('resource_id');

        $validator
            ->uuid('secret_id')
            ->requirePresence('secret_id', 'create')
            ->notEmpty('secret_id');

        return $validator;
    }

    /**
     * Return a secret_access entity.
     * @param array $data entity data
     *
     * @return SecretAccess
     */
    public function buildEntity(array $data)
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
     * @param Secret $secret the secret entity
     * @param UserAccessControl $uac user access control object
     *
     * @return bool|\Cake\Datasource\EntityInterface|false|mixed
     */
    public function create(Secret $secret, UserAccessControl $uac)
    {
        $userId = $uac->userId();

        $data = [
            'user_id' => $userId,
            'resource_id' => $secret->resource_id,
            'secret_id' => $secret->id,
        ];

        // Check validation rules.
        $secretAccess = $this->buildEntity($data);
        if (!empty($secretAccess->getErrors())) {
            throw new ValidationException(__('Could not validate secret_access data.', true), $secretAccess, $this);
        }

        $secretAccessSaved = $this->save($secretAccess);

        // Check for validation errors. (associated models too).
        if (!empty($secretAccess->getErrors())) {
            throw new ValidationException(__('Could not validate secret_access data.'), $secretAccess, $this);
        }

        // Check for errors while saving.
        if (!$secretAccessSaved) {
            throw new InternalErrorException(__('The secret_access could not be saved.'));
        }

        return $secretAccessSaved;
    }
}
