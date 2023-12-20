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
use App\Utility\UserAction;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Log\Model\Entity\EntityHistory;

/**
 * @property \Passbolt\Log\Model\Table\ActionLogsTable&\Cake\ORM\Association\BelongsTo $ActionLogs
 * @property \Passbolt\Log\Model\Table\PermissionsHistoryTable&\Cake\ORM\Association\BelongsTo $PermissionsHistory
 * @property \Passbolt\Log\Model\Table\SecretsHistoryTable&\Cake\ORM\Association\BelongsTo $SecretsHistory
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \Passbolt\Log\Model\Table\SecretAccessesTable&\Cake\ORM\Association\BelongsTo $SecretAccesses
 * @method \Passbolt\Log\Model\Entity\EntityHistory newEmptyEntity()
 * @method \Passbolt\Log\Model\Entity\EntityHistory newEntity(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\EntityHistory[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\EntityHistory get($primaryKey, $options = [])
 * @method \Passbolt\Log\Model\Entity\EntityHistory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Log\Model\Entity\EntityHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\EntityHistory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\EntityHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Log\Model\Entity\EntityHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\EntityHistory>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\EntityHistory>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\EntityHistory>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\EntityHistory>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EntitiesHistoryTable extends Table
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
        $this->belongsTo('Users', [
            'foreignKey' => 'foreign_key',
        ]);
        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $this->belongsTo('FoldersHistory', [
                'foreignKey' => 'foreign_key',
                'className' => 'Passbolt/Folders.FoldersHistory',
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
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->uuid('action_log_id', __('The action log identifier should be a valid UUID.'))
            ->notEmptyString('action_log_id', __('The action log identifier should not be empty.'))
            ->requirePresence('action_log_id', 'create', __('An action log identifier is required.'));

        $validator
            ->ascii('foreign_model', __('The object type should be a valid ASCII string.'))
            ->maxLength('foreign_model', 36, __('The object type length should be maximum {0} characters.', 36))
            ->requirePresence('foreign_model', 'create', __('A object type is required.'))
            ->notEmptyString('foreign_model', __('The object type should not be empty.'));

        $validator
            ->uuid('foreign_key', __('The object identifier should be a valid UUID.'))
            ->notEmptyString('foreign_key', __('The object identifier should not be empty.'))
            ->requirePresence('foreign_key', 'create', __('An object identifier is required.'));

        $validator
            ->inList(
                'crud',
                EntityHistory::CRUD,
                __(
                    'The operation type should be one of the following: {0}.',
                    implode(', ', EntityHistory::CRUD)
                )
            )
            ->requirePresence('crud', 'create', __('An operation type is required.'))
            ->notEmptyString('crud', __('The operation type should not be empty.'));

        return $validator;
    }

    /**
     * Return a entity_history entity.
     *
     * @param array $data entity data
     * @return \Passbolt\Log\Model\Entity\EntityHistory
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
            ],
        ]);
    }

    /**
     * Create a new entity_history.
     *
     * @param array $data the data: foreign_key and foreign_model
     * @param \App\Utility\UserAction $userAction userAction object
     * @return \Passbolt\Log\Model\Entity\EntityHistory
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function create(array $data, UserAction $userAction)
    {
        $defaultData = [
            'action_log_id' => $userAction->getUserActionId(),
        ];
        $data = array_merge($defaultData, $data);

        // Check validation rules.
        $log = $this->buildEntity($data);
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate entity history data.', true), $log, $this);
        }

        $entityHistory = $this->save($log, ['associated' => ['PermissionsHistory']]);

        // Check for validation errors. (associated models too).
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate entity history data.'), $entityHistory, $this);
        }

        // Check for errors while saving.
        if (!$entityHistory) {
            throw new InternalErrorException('Could not save the entity history.');
        }

        return $entityHistory;
    }
}
