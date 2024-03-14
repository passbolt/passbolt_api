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
use Cake\Cache\Cache;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Passbolt\Log\Model\Entity\Action;

/**
 * @property \Passbolt\Log\Model\Table\ActionLogsTable&\Cake\ORM\Association\HasMany $ActionLogs
 * @method \Passbolt\Log\Model\Entity\Action newEmptyEntity()
 * @method \Passbolt\Log\Model\Entity\Action newEntity(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\Action[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\Action get($primaryKey, $options = [])
 * @method \Passbolt\Log\Model\Entity\Action findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Log\Model\Entity\Action patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\Action[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\Action|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Log\Model\Entity\Action saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\Action>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\Action>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\Action>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\Action>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 */
class ActionsTable extends Table
{
    /**
     * Used as key for query cache.
     */
    public const CACHE_KEY = 'table.actions.all';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('actions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('ActionLogs', [
            'foreignKey' => 'action_id',
            'className' => 'Passbolt/Log.ActionLogs',
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
            ->allowEmptyString('id', __('The identifier should be not be empty.'), 'create');

        $validator
            ->ascii('name', __('The name should be a valid ASCII string.'))
            ->requirePresence('name', __('A name is required.'));

        return $validator;
    }

    /**
     * Return a action entity.
     *
     * @param array $data entity data
     * @return \Cake\ORM\Entity Action
     */
    public function buildEntity(array $data)
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'id' => true,
                'name' => true,
            ],
        ]);
    }

    /**
     * Create a new action.
     *
     * @param string $id action id
     * @param string $name name of the action
     * @return \Passbolt\Log\Model\Entity\Action
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function create(string $id, string $name): Action
    {
        $data = [
            'id' => $id,
            'name' => $name,
        ];

        // Check validation rules.
        $action = $this->buildEntity($data);
        if (!empty($action->getErrors())) {
            throw new ValidationException(__('Could not validate action data.', true), $action, $this);
        }

        $actionSaved = $this->save($action);

        // Check for validation errors.
        if (!empty($action->getErrors())) {
            throw new ValidationException(__('Could not validate action data.'), $action, $this);
        }

        // Check for errors while saving.
        if (!$actionSaved) {
            throw new InternalErrorException('Could not save the action.');
        }

        return $actionSaved;
    }

    /**
     * Get cached actions.
     *
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function getCachedActions()
    {
        $actions = $this->find()
                    ->cache(self::CACHE_KEY)
                    ->all();

        return $actions;
    }

    /**
     * Clear table cache.
     *
     * @return void
     */
    public function clearCache()
    {
        Cache::delete(self::CACHE_KEY);
    }

    /**
     * Find or create an action.
     * The find method uses a cache.
     *
     * @param string $id id of the action
     * @param string $name name of the action
     * @return \Cake\ORM\Entity $action the action
     */
    public function findOrCreateAction(string $id, string $name)
    {
        $actions = $this->getCachedActions()->toArray();
        $actions = Hash::combine($actions, '{n}.name', '{n}');

        if (!isset($actions[$name])) {
            $action = $this->create($id, $name);
            $this->clearCache();
        } else {
            $action = $actions[$name];
        }

        return $action;
    }
}
