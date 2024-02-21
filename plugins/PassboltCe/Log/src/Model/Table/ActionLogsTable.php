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
use App\Model\Table\AvatarsTable;
use App\Utility\UserAction;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Log\Model\Entity\ActionLog;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \Passbolt\Log\Model\Table\ActionsTable&\Cake\ORM\Association\BelongsTo $Actions
 * @property \Passbolt\Log\Model\Table\EntitiesHistoryTable&\Cake\ORM\Association\HasMany $EntitiesHistory
 * @method \Passbolt\Log\Model\Entity\ActionLog newEmptyEntity()
 * @method \Passbolt\Log\Model\Entity\ActionLog newEntity(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\ActionLog[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\ActionLog get($primaryKey, $options = [])
 * @method \Passbolt\Log\Model\Entity\ActionLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Log\Model\Entity\ActionLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\ActionLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\ActionLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Log\Model\Entity\ActionLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\ActionLog>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\ActionLog>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\ActionLog>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\ActionLog>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ActionLogsTable extends Table
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

        $this->setTable('action_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The action log identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The action log identifier should not be empty.'), 'create');

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->allowEmptyString('user_id')
            ->requirePresence('user_id', 'create', __('A user identifier is required.'));

        $validator
            ->uuid('action_id', __('The action identifier should be a valid UUID.'))
            ->requirePresence('action_id', __('An action identifier is required.'));

        $validator
            ->ascii('context', __('The context should be a valid ASCII string.'))
            ->maxLength('context', 255, __('The context length should be maximum {0} characters.', 255))
            ->requirePresence('context', __('A context is required.'));

        $validator
            ->boolean('status', __('The status should be a valid boolean.'))
            ->requirePresence('status', __('A status is required.'));

        return $validator;
    }

    /**
     * Create a new action_log from a userAction.
     *
     * Will not process blacklisted actions (see config).
     *
     * @param \App\Utility\UserAction $userAction user action
     * @param int $status status of the request
     * @return \Passbolt\Log\Model\Entity\ActionLog|bool
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function create(UserAction $userAction, int $status)
    {
        // Create corresponding action.
        $action = $this->Actions->findOrCreateAction($userAction->getActionId(), $userAction->getActionName());

        $data = [
            'id' => $userAction->getUserActionId(),
            'user_id' => $userAction->getUserAccessControl()->getId(),
            'action_id' => $action->id,
            'context' => $userAction->getContext(),
            'status' => $status,
        ];
        // Check validation rules.
        $log = $this->buildEntity($data);
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate action log data.'), $log, $this);
        }

        /** @var \Passbolt\Log\Model\Entity\ActionLog|bool $logSaved */
        $logSaved = $this->save($log);

        // Check for validation errors. (associated models too).
        if (!empty($log->getErrors())) {
            throw new ValidationException(__('Could not validate action log data.'), $log, $this);
        }

        // Check for errors while saving.
        if (!$logSaved) {
            throw new InternalErrorException('Could not save the action log.');
        }

        return $logSaved;
    }

    /**
     * Return a action_log entity.
     *
     * @param array $data entity data
     * @return \Passbolt\Log\Model\Entity\ActionLog
     */
    public function buildEntity(array $data): ActionLog
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'user_id' => true,
                'action_id' => true,
                'context' => true,
                'status' => true,
                'created' => true,
            ],
        ]);
    }

    /**
     * @param array $options options
     * @return \Cake\ORM\Query
     */
    public function index(array $options): Query
    {
        $logs = $this->find();
        $this->addContain($logs, $options);
        $this->filterByUsers($logs, $options);
        $this->filterByDate($logs, $options);
        $this->filterBySuccess($logs, $options);

        return $logs;
    }

    /**
     * @param \Cake\ORM\Query $query Logs
     * @param array $options options
     * @return void
     */
    protected function addContain(Query $query, array $options): void
    {
        $contain = $options['contain'] ?? [];
        if (isset($contain['user'])) {
            $query->contain('Users');
        } elseif (isset($contain['user.profile'])) {
            $query->contain([
                'Users.Profiles' => AvatarsTable::addContainAvatar(),
            ]);
        }
    }

    /**
     * @param \Cake\ORM\Query $logs Logs
     * @param array $options options
     * @return void
     */
    protected function filterByUsers(Query $logs, array $options): void
    {
        $hasUsers = $options['filter']['has-users'] ?? [];
        if (empty($hasUsers)) {
            return;
        }

        $logs->where(function (QueryExpression $exp) use ($hasUsers) {
            return $exp->in($this->aliasField('user_id'), $hasUsers);
        });
    }

    /**
     * @param \Cake\ORM\Query $logs Logs
     * @param array $options options
     * @return void
     */
    protected function filterByDate(Query $logs, array $options): void
    {
        $from = $options['filter']['created-after'] ?? null;
        $to = $options['filter']['created-before'] ?? null;
        if (!is_null($from)) {
            $logs->where(function (QueryExpression $exp) use ($from) {
                return $exp->gte($this->aliasField('created'), $from);
            });
        }
        if (!is_null($to)) {
            $logs->where(function (QueryExpression $exp) use ($to) {
                return $exp->lte($this->aliasField('created'), $to);
            });
        }
        if (!is_null($from) && !is_null($to) && (new \DateTime($from) > new \DateTime($to))) {
            throw new BadRequestException(
                __('The date {0} should be after the date {1}.', 'created-after', 'created-before')
            );
        }
    }

    /**
     * @param \Cake\ORM\Query $logs Logs
     * @param array $options options
     * @return void
     */
    protected function filterBySuccess(Query $logs, array $options): void
    {
        $isSuccess = $options['filter']['is-success'] ?? null;
        if (!is_null($isSuccess)) {
            $logs->where([$this->aliasField('status') => (int)$isSuccess]);
        }
    }
}
