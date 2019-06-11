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

class EntitiesHistoryTable extends Table
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

        $this->setTable('entities_history');
        $this->setEntityClass('Passbolt\Log\Model\Entity\EntityHistory');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ActionLogs', [
            'foreignKey' => 'action_log_id',
            'className' => 'Passbolt/Log.ActionLogs',
        ]);
        $this->belongsTo('PermissionsHistory', [
            'foreignKey' => 'foreign_key',
            'className' => 'Passbolt/Log.PermissionsHistory',
        ]);
        $this->belongsTo('SecretsHistory', [
            'foreignKey' => 'foreign_key',
            'className' => 'Passbolt/Log.SecretsHistory',
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'foreign_key',
        ]);
        $this->belongsTo('SecretAccesses', [
            'foreignKey' => 'foreign_key',
            'className' => 'Passbolt/Log.SecretAccesses',
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
            ->uuid('action_log_id', __('action_log_id should be a uuid'))
            ->notEmpty('action_log_id', __('action_log_id should not be empty'))
            ->requirePresence('action_log_id', 'create', __('action_log_id is required'));

        $validator
            ->ascii('foreign_model', __('foreign_model should be ascii'))
            ->maxLength('foreign_model', 36, __('foreign_model should not exceed 255 characters'))
            ->requirePresence('foreign_model', 'create', __('foreign_model is required'))
            ->notEmpty('foreign_model', __('foreign_model should not be empty'));

        $validator
            ->uuid('foreign_key', __('foreign_key should be a uuid'))
            ->notEmpty('foreign_key', __('foreign_key should not be empty'))
            ->requirePresence('foreign_key', 'create', __('foreign_key is required'));

        $validator
            ->inList('crud', EntityHistory::CRUD, __('The crud provided is not supported'))
            ->requirePresence('crud', 'create', __('crud is required'))
            ->notEmpty('crud', __('crud should not be empty'));

        return $validator;
    }

    /**
     * Return a entity_history entity.
     * @param array $data entity data
     *
     * @return EntityHistory
     */
    public function buildEntity(array $data)
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'action_log_id' => true,
                'foreign_model' => true,
                'foreign_key' => true,
                'crud' => true,
                'created' => true,
                'permission_history' => true,
                'permissionHistory' => true,
                'permissions_history' => true,
                'permissionsHistory' => true,
            ],
            'associated' => [
                'PermissionsHistory' => [
                    'accessibleFields' => [
                        'id' => true,
                        'aco' => true,
                        'aco_foreign_key' => true,
                        'aro' => true,
                        'aro_foreign_key' => true,
                        'type' => true,
                    ],
                ],
            ]
        ]);
    }

    /**
     * Create a new entity_history.
     *
     * @param array $data the data: foreign_key and foreign_model
     * @param UserAction $userAction userAction object
     *
     * @return UserAction|bool
     * @throws ValidationException
     * @throws InternalErrorException
     */
    public function create(array $data, UserAction $userAction)
    {
        $defaultData = [
            'action_log_id' => $userAction->getUserActionId(),
        ];
        $data = array_merge($defaultData, $data);

        // Check validation rules.
        $log = $this->buildEntity($data, $userAction);
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate entity_history data.', true), $log, $this);
        }

        $entityHistory = $this->save($log, ['associated' => ['PermissionsHistory']]);

        // Check for validation errors. (associated models too).
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate entity_history data.'), $entityHistory, $this);
        }

        // Check for errors while saving.
        if (!$entityHistory) {
            throw new InternalErrorException(__('The entity_history could not be saved.'));
        }

        return $entityHistory;
    }
}
