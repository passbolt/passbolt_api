<?php
namespace Passbolt\UsersSettings\Model\Table;

use Cake\Network\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * UsersSettings Model
 *
 * @property \Passbolt\UsersSettings\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \Passbolt\UsersSettings\Model\Entity\UsersSetting get($primaryKey, $options = [])
 * @method \Passbolt\UsersSettings\Model\Entity\UsersSetting newEntity($data = null, array $options = [])
 * @method \Passbolt\UsersSettings\Model\Entity\UsersSetting[] newEntities(array $data, array $options = [])
 * @method \Passbolt\UsersSettings\Model\Entity\UsersSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\UsersSettings\Model\Entity\UsersSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\UsersSettings\Model\Entity\UsersSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \Passbolt\UsersSettings\Model\Entity\UsersSetting findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersSettingsTable extends Table
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

        $this->setTable('users_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Passbolt/UsersSettings.Users'
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
            ->scalar('property')
            ->maxLength('property', 256)
            ->requirePresence('property', 'create')
            ->notEmpty('property');

        $validator
            ->scalar('value')
            ->maxLength('value', 256)
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * Find all the settings for a given user
     * @param string $userId uuid
     * @return Query
     */
    public function findIndex($userId)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id must be a valid uuid.'));
        }

        return $this->find()
            ->where(['user_id' => $userId])
            ->all();
    }
}
