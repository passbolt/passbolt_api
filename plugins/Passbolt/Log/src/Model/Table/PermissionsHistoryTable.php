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
use App\Model\Table\PermissionsTable;
use App\Utility\UserAction;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Log\Model\Entity\EntityHistory;

class PermissionsHistoryTable extends Table
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

        $this->setTable('permissions_history');
        $this->setEntityClass('Passbolt\Log\Model\Entity\PermissionHistory');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasOne('EntitiesHistory', [
            'foreignKey' => 'foreign_key',
            'className' => 'Passbolt/Log.EntitiesHistory'
        ]);
        $this->belongsTo('Groups', [
            'foreignKey' => 'aro_foreign_key'
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'aco_foreign_key'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'aro_foreign_key'
        ]);

        // The contains below are a duplicate of above.
        // They are named differently so that we can easily put
        // specific conditions in deeply nested associations.
        $this->belongsTo('PermissionsHistoryGroups', [
            'foreignKey' => 'aro_foreign_key',
            'className' => 'Groups'
        ]);
        $this->belongsTo('PermissionsHistoryUsers', [
            'foreignKey' => 'aro_foreign_key',
            'className' => 'Users'
        ]);
        $this->belongsTo('PermissionsHistoryResources', [
            'foreignKey' => 'aco_foreign_key',
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
            ->requirePresence('id', 'create')
            ->notEmpty('id', __('The id cannot be empty.'));

        $validator
            ->inList('aco', PermissionsTable::ALLOWED_ACOS, __(
                'The aco must be one of the following: {0}.',
                implode(', ', PermissionsTable::ALLOWED_ACOS)
            ))
            ->requirePresence('aco', 'create', __('The aco is required.'))
            ->notEmpty('aco', __('The aco cannot be empty.'));

        $validator
            ->uuid('aco_foreign_key')
            ->requirePresence('aco_foreign_key', 'create')
            ->notEmpty('aco_foreign_key');

        $validator
            ->inList('aro', PermissionsTable::ALLOWED_AROS, __(
                'The aro must be one of the following: {0}.',
                implode(', ', PermissionsTable::ALLOWED_AROS)
            ))
            ->requirePresence('aro', 'create', __('The aro is required.'))
            ->notEmpty('aro', __('The aro cannot be empty.'));

        $validator
            ->uuid('aro_foreign_key')
            ->requirePresence('aro_foreign_key', 'create')
            ->notEmpty('aro_foreign_key');

        $validator
            ->inList('type', PermissionsTable::ALLOWED_TYPES, __(
                'The type must be one of the following: {0}.',
                implode(', ', PermissionsTable::ALLOWED_TYPES)
            ))
            ->requirePresence('type', 'create', __('The type is required.'))
            ->notEmpty('type', __('The type cannot be empty.'));

        return $validator;
    }

    /**
     * Return a permissions_history entity.
     * @param array $data entity data
     *
     * @return EntityHistory
     */
    public function buildEntity(array $data)
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'aco' => true,
                'aco_foreign_key' => true,
                'aro' => true,
                'aro_foreign_key' => true,
                'type' => true,
            ],
        ]);
    }

    /**
     * Create a new permissions_history.
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
        $log = $this->buildEntity($data);
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate permission_history data.', true), $log, $this);
        }

        $permissionHistory = $this->save($log);

        // Check for validation errors. (associated models too).
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate permission_history data.'), $permissionHistory, $this);
        }

        // Check for errors while saving.
        if (!$permissionHistory) {
            throw new InternalErrorException(__('The permission_history could not be saved.'));
        }

        return $permissionHistory;
    }
}
