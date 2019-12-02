<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\Log\Model\Table;

use App\Error\Exception\ValidationException;
use App\Utility\UserAction;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Log\Model\Entity\EntityHistory;

class SecretsHistoryTable extends Table
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

        $this->setTable('secrets_history');
        $this->setEntityClass('Passbolt\Log\Model\Entity\SecretHistory');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasOne('EntitiesHistory', [
            'foreignKey' => 'foreign_key',
            'className' => 'Passbolt/Log.EntitiesHistory'
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'resource_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        // The contains below are a duplicate of above.
        // They are named differently so that we can easily put
        // specific conditions in deeply nested associations.
        $this->belongsTo('SecretsHistoryUsers', [
            'foreignKey' => 'user_id',
            'className' => 'Users',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('SecretsHistoryResources', [
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
            ->uuid('resource_id')
            ->requirePresence('resource_id', 'create')
            ->notEmpty('resource_id');

        $validator
            ->uuid('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmpty('user_id');

        return $validator;
    }

    /**
     * Return a SecretsHistory entity.
     * @param array $data entity data
     *
     * @return EntityHistory
     */
    public function buildEntity(array $data)
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'user_id' => true,
                'resource_id' => true,
            ],
        ]);
    }

    /**
     * Create a new SecretHistory.
     *
     * @param array $data the data
     *
     * @return UserAction|bool
     * @throws ValidationException
     * @throws InternalErrorException
     */
    public function create(array $data)
    {
        // Check validation rules.
        $secretHistory = $this->buildEntity($data);
        if (!empty($secretHistory->getErrors())) {
            throw new ValidationException(__('Could not validate secret_history data.', true), $secretHistory, $this);
        }

        $secretHistory = $this->save($secretHistory);

        // Check for validation errors. (associated models too).
        if (!empty($secretHistory->getErrors())) {
            throw new ValidationException(__('Could not validate secret_history data.'), $secretHistory, $this);
        }

        // Check for errors while saving.
        if (!$secretHistory) {
            throw new InternalErrorException(__('The secret_history could not be saved.'));
        }

        return $secretHistory;
    }
}
