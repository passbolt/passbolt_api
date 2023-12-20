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
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Log\Model\Entity\SecretHistory;

/**
 * @property \Passbolt\Log\Model\Table\EntitiesHistoryTable&\Cake\ORM\Association\HasOne $EntitiesHistory
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $Resources
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $SecretsHistoryUsers
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\BelongsTo $SecretsHistoryResources
 * @method \Passbolt\Log\Model\Entity\SecretHistory newEmptyEntity()
 * @method \Passbolt\Log\Model\Entity\SecretHistory newEntity(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretHistory[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretHistory get($primaryKey, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretHistory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretHistory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Log\Model\Entity\SecretHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\SecretHistory>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\SecretHistory>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\SecretHistory>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\Log\Model\Entity\SecretHistory>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 */
class SecretsHistoryTable extends Table
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

        $this->setTable('secrets_history');
        $this->setEntityClass('Passbolt\Log\Model\Entity\SecretHistory');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasOne('EntitiesHistory', [
            'foreignKey' => 'foreign_key',
            'className' => 'Passbolt/Log.EntitiesHistory',
        ]);
        $this->belongsTo('Resources', [
            'foreignKey' => 'resource_id',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);

        // The contains below are a duplicate of above.
        // They are named differently so that we can easily put
        // specific conditions in deeply nested associations.
        $this->belongsTo('SecretsHistoryUsers', [
            'foreignKey' => 'user_id',
            'className' => 'Users',
            'joinType' => 'LEFT',
        ]);
        $this->belongsTo('SecretsHistoryResources', [
            'foreignKey' => 'resource_id',
            'className' => 'Resources',
            'joinType' => 'LEFT',
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
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->uuid('resource_id', __('The resource identifier should be a valid UUID.'))
            ->requirePresence('resource_id', 'create', __('The resource identifier is required.'))
            ->notEmptyString('resource_id', __('The resource identifier should not be empty.'));

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->notEmptyString('user_id', __('The user identifier should not be empty.'));

        return $validator;
    }

    /**
     * Return a SecretsHistory entity.
     *
     * @param array $data entity data
     * @return \Passbolt\Log\Model\Entity\SecretHistory
     */
    public function buildEntity(array $data): SecretHistory
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
     * @return \Passbolt\Log\Model\Entity\SecretHistory
     * @throws \App\Error\Exception\ValidationException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function create(array $data): SecretHistory
    {
        // Check validation rules.
        $secretHistory = $this->buildEntity($data);
        if (!empty($secretHistory->getErrors())) {
            throw new ValidationException(__('Could not validate secret history data.', true), $secretHistory, $this);
        }

        $secretHistory = $this->save($secretHistory);

        // Check for errors while saving.
        if (!$secretHistory) {
            throw new InternalErrorException('Could not save the secret history.');
        }

        // Check for validation errors. (associated models too).
        if (!empty($secretHistory->getErrors())) {
            throw new ValidationException(__('Could not validate secret history data.'), $secretHistory, $this);
        }

        return $secretHistory;
    }
}
