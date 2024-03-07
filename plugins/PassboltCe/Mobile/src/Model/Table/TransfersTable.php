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
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.3.0
 */

namespace Passbolt\Mobile\Model\Table;

use App\Model\Rule\IsActiveRule;
use App\Model\Rule\IsNotSoftDeletedRule;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use Passbolt\Mobile\Model\Entity\Transfer;

/**
 * Transfers Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\AuthenticationTokensTable|\Cake\ORM\Association\BelongsTo $AuthenticationTokens
 * @method \Passbolt\Mobile\Model\Entity\Transfer get($primaryKey, ?array $options = [])
 * @method \Passbolt\Mobile\Model\Entity\Transfer newEntity($data = null, ?array $options = [])
 * @method \Passbolt\Mobile\Model\Entity\Transfer[] newEntities(array $data, ?array $options = [])
 * @method \Cake\Datasource\EntityInterface|false save(\Cake\Datasource\EntityInterface $entity, \ArrayAccess|array $options = [])
 * @method \Passbolt\Mobile\Model\Entity\Transfer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, ?array $options = [])
 * @method \Passbolt\Mobile\Model\Entity\Transfer[] patchEntities($entities, array $data, ?array $options = [])
 * @method \Passbolt\Mobile\Model\Entity\Transfer findOrCreate($search, callable $callback = null, ?array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TransfersTable extends Table
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

        $this->setTable('transfers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);

        $this->belongsTo('AuthenticationTokens', [
            'foreignKey' => 'authentication_token_id',
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
            ->uuid('id', __('The transfer id should be a uuid.'))
            ->allowEmptyString('id', null, 'create');

        $validator
            ->uuid('user_id', __('The user id should be a uuid.'))
            ->requirePresence('user_id', 'create', __('A user id is required'));

        $validator
            ->ascii('hash', __('The hash should be an ascii string.'))
            ->lengthBetween(
                'hash',
                [Transfer::TRANSFER_HASH_SIZE, Transfer::TRANSFER_HASH_SIZE],
                __('The hash should be {0} characters in length.', Transfer::TRANSFER_HASH_SIZE)
            )
            ->requirePresence('hash', 'create', __('The data transfer hash is required.'));

        $validator
            ->nonNegativeInteger('current_page', __('The current page should be a non negative integer.'))
            ->requirePresence('current_page', 'update', __('The current page number is required'))
            ->add('current_page', 'inferior_to_total', [
                'rule' => function ($value, $context) {
                    if (isset($context['data']['total_pages'])) {
                        return $value < $context['data']['total_pages'];
                    }

                    return true;
                },
                'message' => __('The current page number should be equal or inferior to the total number of pages.'),
            ])
            ->lessThan(
                'current_page',
                Transfer::TRANSFER_MAX_PAGES,
                __('The current page cannot be greater than {0}', Transfer::TRANSFER_MAX_PAGES)
            );

        $validator
            ->nonNegativeInteger('total_pages', __('The total number of pages should be a non negative integer.'))
            ->requirePresence('total_pages', 'create', __('The total number of pages is required.'))
            ->greaterThan('total_pages', 0, __('The total number of pages should be greater than 0.'))
            ->lessThan(
                'total_pages',
                Transfer::TRANSFER_MAX_PAGES,
                __('The total number of pages cannot be greater than {0}', Transfer::TRANSFER_MAX_PAGES)
            );

        $validator
            ->notEmptyString('status', __('The status should not be empty.'))
            ->requirePresence('status', true, __('The status is required.'))
            ->inList('status', Transfer::TRANSFER_STATUSES, __(
                'The status must be one of the following: {0}.',
                implode(', ', Transfer::TRANSFER_STATUSES)
            ));

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->addCreate($rules->existsIn('user_id', 'Users'), 'user_exists');
        $rules->addCreate(new IsNotSoftDeletedRule(), 'user_is_soft_deleted', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user must be active.'),
        ]);
        $rules->addCreate(new IsActiveRule(), 'user_is_active', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user must be active.'),
        ]);

        return $rules;
    }

    /**
     * Cancel all the transfers that are started or in progress and that cannot be
     * completed because the authentication token has expired.
     *
     * @return void
     */
    public function cancelAllTransfersWithInactiveAuthenticationToken(): void
    {
        $transfers = $this->find()
            ->select(['id'])
            ->distinct()
            ->contain(['AuthenticationTokens'])
            ->where(['Transfers.status in' => [
                Transfer::TRANSFER_STATUS_IN_PROGRESS, Transfer::TRANSFER_STATUS_START,
            ], 'AuthenticationTokens.active' => false])
            ->all()
            ->toArray();

        if (count($transfers)) {
            $this->updateQuery()
                ->update()
                ->set(['status' => Transfer::TRANSFER_STATUS_CANCEL])
                ->where(['id in' => Hash::extract($transfers, '{n}.id')])
                ->execute();
        }
    }
}
