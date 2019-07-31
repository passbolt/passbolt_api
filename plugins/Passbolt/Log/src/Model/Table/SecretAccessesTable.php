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
namespace Passbolt\Log\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Secret;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Log\Model\Entity\SecretAccess;

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
        $this->belongsTo('EntitiesHistory', [
            'className' => 'Passbolt/Log.EntitiesHistory',
            'foreignKey' => 'foreign_key'
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->allowEmptyString('user_id', null, false);

        $validator
            ->uuid('resource_id')
            ->requirePresence('resource_id', 'create')
            ->allowEmptyString('resource_id', null, false);

        $validator
            ->uuid('secret_id')
            ->requirePresence('secret_id', 'create')
            ->allowEmptyString('secret_id', null, false);

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
