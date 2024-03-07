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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\Log\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Table\PermissionsTable;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Log\Model\Entity\PermissionHistory;

/**
 * @property \Passbolt\Log\Model\Table\EntitiesHistoryTable&\Cake\ORM\Association\HasOne $EntitiesHistory
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\BelongsTo $PermissionsHistoryGroups
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $PermissionsHistoryUsers
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $PermissionsHistoryResources
 * @method \Passbolt\Log\Model\Entity\PermissionHistory newEmptyEntity()
 * @method \Passbolt\Log\Model\Entity\PermissionHistory newEntity(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\PermissionHistory[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\PermissionHistory get($primaryKey, $options = [])
 * @method \Passbolt\Log\Model\Entity\PermissionHistory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Log\Model\Entity\PermissionHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\PermissionHistory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\PermissionHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Log\Model\Entity\PermissionHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\PermissionHistory>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\PermissionHistory>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\PermissionHistory>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\PermissionHistory>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 */
class PermissionsHistoryTable extends Table
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

        $this->setTable('permissions_history');
        $this->setEntityClass('Passbolt\Log\Model\Entity\PermissionHistory');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasOne('EntitiesHistory', [
            'foreignKey' => 'foreign_key',
            'className' => 'Passbolt/Log.EntitiesHistory',
        ]);
        $this->belongsTo('Groups', [
            'foreignKey' => 'aro_foreign_key',
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'aco_foreign_key',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'aro_foreign_key',
        ]);

        // The contains below are a duplicate of above.
        // They are named differently so that we can easily put
        // specific conditions in deeply nested associations.
        $this->belongsTo('PermissionsHistoryGroups', [
            'foreignKey' => 'aro_foreign_key',
            'className' => 'Groups',
        ]);
        $this->belongsTo('PermissionsHistoryUsers', [
            'foreignKey' => 'aro_foreign_key',
            'className' => 'Users',
        ]);
        $this->belongsTo('PermissionsHistoryResources', [
            'foreignKey' => 'aco_foreign_key',
            'className' => 'Resources',
            'joinType' => 'LEFT',
        ]);

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $this->belongsTo('PermissionsHistoryFolders', [
                'foreignKey' => 'aco_foreign_key',
                'className' => 'Passbolt/Folders.Folders',
                'joinType' => 'LEFT',
            ]);
            $this->belongsTo('Folders', [
                'className' => 'Passbolt/Folders.Folders',
                'foreignKey' => 'aco_foreign_key',
            ]);
        }
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
            ->requirePresence('id', 'create', __('An identifier is required.'))
            ->notEmptyString('id', __('The identifier should not be empty.'));

        $validator
            ->inList('aco', PermissionsTable::ALLOWED_ACOS, __(
                'The type of the access control object should be one of the following: {0}.',
                implode(', ', PermissionsTable::ALLOWED_ACOS)
            ))
            ->requirePresence('aco', 'create', __('The type of the access control object is required.'))
            ->notEmptyString('aco', __('The type of the access control object should not be empty.'));

        $validator
            ->uuid('aco_foreign_key', __('The identifier of the access control object should be a valid UUID.'))
            ->requirePresence(
                'aco_foreign_key',
                'create',
                __('The identifier of the access control object is required.')
            )
            ->notEmptyString('aco_foreign_key', __('The identifier of the access control object should not be empty.'));

        $validator
            ->inList('aro', PermissionsTable::ALLOWED_AROS, __(
                'The access request object type should be one of the following: {0}.',
                implode(', ', PermissionsTable::ALLOWED_AROS)
            ))
            ->requirePresence('aro', 'create', __('The type of the access request object is required.'))
            ->notEmptyString('aro', __('The access request object type should not be empty.'));

        $validator
            ->uuid('aro_foreign_key', __('The identifier of the access request object should be a valid UUID.'))
            ->requirePresence(
                'aro_foreign_key',
                'create',
                __('The identifier of the access request object is required.')
            )
            ->notEmptyString('aro_foreign_key', __('The identifier of the access request object should not be empty.'));

        $validator
            ->inList('type', PermissionsTable::ALLOWED_TYPES, __(
                'The type must be one of the following: {0}.',
                implode(', ', PermissionsTable::ALLOWED_TYPES)
            ))
            ->requirePresence('type', 'create', __('The type is required.'))
            ->notEmptyString('type', __('The type should not be empty.'));

        return $validator;
    }

    /**
     * Return a permissions_history entity.
     *
     * @param array $data entity data
     * @return \Passbolt\Log\Model\Entity\PermissionHistory
     */
    public function buildEntity(array $data): PermissionHistory
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
     * @return \Passbolt\Log\Model\Entity\PermissionHistory
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function create(array $data): PermissionHistory
    {
        // Check validation rules.
        $log = $this->buildEntity($data);
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate permission history data.', true), $log, $this);
        }

        $permissionHistory = $this->save($log);

        // Check for validation errors. (associated models too).
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate permission history data.'), $permissionHistory, $this);
        }

        // Check for errors while saving.
        if (!$permissionHistory) {
            throw new InternalErrorException('Could not save permission history.');
        }

        return $permissionHistory;
    }
}
