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

class ActionLogsTable extends Table
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

        $this->setTable('action_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        $this->belongsTo('Actions', [
            'foreignKey' => 'action_id',
            'className' => 'Passbolt/Log.Actions',
        ]);

        $this->hasMany('EntitiesHistory', [
            'foreignKey' => 'action_log_id',
            'className' => 'Passbolt/Log.EntitiesHistory',
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
            ->uuid('user_id', __('user_id should be a uuid'))
            ->allowEmpty('user_id')
            ->requirePresence('user_id', 'create', __('A user_id is required'));

        $validator
            ->uuid('action_id', __('action_id should be a uuid'))
            ->requirePresence('action_id');

        $validator
            ->ascii('context', __('context should be ascii'))
            ->maxLength('context', 255, __('context should not exceed 255 characters'))
            ->requirePresence('context');

        $validator
            ->boolean('status', __('status should be boolean'))
            ->requirePresence('status');

        return $validator;
    }

    /**
     * Return a action_log entity.
     * @param array $data entity data
     *
     * @return ActionLog
     */
    public function buildEntity(array $data)
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'user_id' => true,
                'action_id' => true,
                'context' => true,
                'status' => true,
                'created' => true
            ],
        ]);
    }

    /**
     * Create a new user_log.
     *
     * @param UserAction $userAction log type in clear
     * @param int $status user_log data
     *
     * @return \App\Model\Entity\UserLog|bool
     * @throws ValidationException
     * @throws InternalErrorException
     */
    public function create(UserAction $userAction, int $status)
    {
        $userId = $userAction->getUserAccessControl()->userId();

        // Create corresponding action.
        $action = $this->Actions->findOrCreateAction($userAction->getActionId(), $userAction->getActionName());

        $data = [
            'id' => $userAction->getUserActionId(),
            'user_id' => $userId,
            'action_id' => $action->id,
            'context' => $userAction->getContext(),
            'status' => $status,
        ];
        // Check validation rules.
        $log = $this->buildEntity($data);
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate action_log data.'), $log, $this);
        }

        $logSaved = $this->save($log);

        // Check for validation errors. (associated models too).
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate action_log data.'), $log, $this);
        }

        // Check for errors while saving.
        if (!$logSaved) {
            throw new InternalErrorException(__('The action_log could not be saved.'));
        }

        return $logSaved;
    }
}
