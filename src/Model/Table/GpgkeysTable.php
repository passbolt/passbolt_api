<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;

/**
 * Gpgkeys Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\KeysTable|\Cake\ORM\Association\BelongsTo $Keys
 *
 * @method \App\Model\Entity\Gpgkey get($primaryKey, $options = [])
 * @method \App\Model\Entity\Gpgkey newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Gpgkey[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Gpgkey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Gpgkey findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GpgkeysTable extends Table
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

        $this->setTable('gpgkeys');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->scalar('armored_key')
            ->requirePresence('armored_key', 'create')
            ->notEmpty('armored_key');

        $validator
            ->integer('bits')
            ->allowEmpty('bits');

        $validator
            ->scalar('uid')
            ->requirePresence('uid', 'create')
            ->notEmpty('uid');

        $validator
            ->scalar('key_id')
            ->requirePresence('key_id', 'create')
            ->notEmpty('key_id');

        $validator
            ->scalar('fingerprint')
            ->requirePresence('fingerprint', 'create')
            ->notEmpty('fingerprint');

        $validator
            ->scalar('type')
            ->allowEmpty('type');

        $validator
            ->dateTime('expires')
            ->allowEmpty('expires');

        $validator
            ->dateTime('key_created')
            ->allowEmpty('key_created');

        $validator
            ->boolean('deleted')
            ->requirePresence('deleted', 'create')
            ->notEmpty('deleted');

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
     * Custom validation rule to validate fingerprint
     *
     * @param string $value fingerprint
     * @param array $context
     * @return bool
     */
    public function isValidFingerprint($value, array $context = null)
    {
        return (preg_match('/^[A-F0-9]{40}$/', $value) === 1);
    }

    /**
     * Check for valid email inside GPG key UID
     *
     * @param string $value gpg key uid
     * @param array $context
     * @return bool
     */
    public function uidContainValidEmail($value, array $context = null) {
        preg_match('/<(\S+@\S+)>$/', $value, $matches);
        if (isset($matches[1])) {
            return Validation::email($matches[1]);
        }
        return false;
    }

    /**
     * Build the query that fetches data for user index
     *
     * @param Query $query a query instance
     * @param array $options options
     * @throws Exception if no role is specified
     * @return Query
     */
    public function findIndex(Query $query, array $options)
    {
        $query->where(['deleted' => false]);
        if (isset($options['filter']['modified-after'])) {
           $modified = date('Y-m-d H:i:s', $options['filter']['modified-after']);
           $query->where(['modified >' => $modified]);
        }
        return $query;
    }

    /**
     * Find view
     *
     * @param Query $query a query instance
     * @param array $options options
     * @throws Exception if no id is specified
     * @return Query
     */
    public function findView(Query $query, array $options)
    {
        // Options must contain an id
        if (!isset($options['id'])) {
            throw new Exception(__('Gpgkey table findView should have an id set in options.'));
        }
        // Same rule than index apply
        // with a specific id requested
        $query = $this->findIndex($query, $options);
        $query->where(['id' => $options['id']]);

        return $query;
    }

}
