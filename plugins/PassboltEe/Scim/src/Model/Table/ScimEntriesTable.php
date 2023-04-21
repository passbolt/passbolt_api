<?php
declare(strict_types=1);

namespace Passbolt\Scim\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Scim\Model\Entity\ScimEntry;

/**
 * ScimEntries Model
 *
 * @method \Passbolt\Scim\Model\Entity\ScimEntry newEmptyEntity()
 * @method \Passbolt\Scim\Model\Entity\ScimEntry newEntity(array $data, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry get($primaryKey, $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ScimEntriesTable extends Table
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

        $this->setTable('scim_entries');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->setEntityClass(ScimEntry::class);

        $this->addBehavior('Timestamp');
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
            ->scalar('foreign_key')
            ->maxLength('foreign_key', 36)
            ->requirePresence('foreign_key', 'create')
            ->notEmptyString('foreign_key');

        $validator
            ->scalar('foreign_model')
            ->maxLength('foreign_model', 36)
            ->inList('foreign_model', array_keys(self::getForeignModels()))
            ->requirePresence('foreign_model', 'create')
            ->notEmptyString('foreign_model');

        $validator
            ->scalar('external_identifier')
            ->maxLength('external_identifier', 256)
            ->requirePresence('external_identifier', 'create')
            ->notEmptyString('external_identifier');

        $validator
            ->scalar('scim_name')
            ->maxLength('scim_name', 256)
            ->requirePresence('scim_name', 'create')
            ->notEmptyString('scim_name');

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
        $rules->add($rules->isUnique(['foreign_key', 'foreign_model']), [
            'errorField' => 'foreign_key',
            'message' => __('An entry for the same related entity already exists'),
        ]);
        $rules->add($rules->isUnique(['scim_name', 'foreign_model']), [
            'errorField' => 'scim_name',
            'message' => __('An entry with the same `scim_name` and ResourceType already exist'),
        ]);

        return $rules;
    }

    /**
     * Return the list of available foreign models
     *
     * @return array
     */
    public static function getForeignModels(): array
    {
        return [
            ScimEntry::FOREIGN_MODEL_USERS => __('Users'),
            ScimEntry::FOREIGN_MODEL_GROUPS => __('Groups'),
        ];
    }

    /**
     * Return a DirectoryEntry entity.
     *
     * @param array $data data
     * @return \Passbolt\Scim\Model\Entity\ScimEntry
     */
    public function buildEntity(array $data): ScimEntry
    {
        return $this->newEntity($data, [
            'accessibleFields' => [
                'foreign_key' => true,
                'foreign_model' => true,
                'external_identifier' => true,
                'scim_name' => true,
            ],
        ]);
    }
}
