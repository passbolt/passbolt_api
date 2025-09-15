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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Model\Table;

use App\Model\Table\GroupsTable;
use App\Model\Table\UsersTable;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Scim\Model\Entity\ScimEntry;

/**
 * ScimEntries Model
 *
 * @method \Passbolt\Scim\Model\Entity\ScimEntry newEmptyEntity()
 * @method \Passbolt\Scim\Model\Entity\ScimEntry newEntity(array $data, array $options = [])
 * @method array<\Passbolt\Scim\Model\Entity\ScimEntry> newEntities(array $data, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Passbolt\Scim\Model\Entity\ScimEntry findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, callable|array|null $callback = null, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\Passbolt\Scim\Model\Entity\ScimEntry> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \Passbolt\Scim\Model\Entity\ScimEntry saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\Passbolt\Scim\Model\Entity\ScimEntry>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\Scim\Model\Entity\ScimEntry> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\Scim\Model\Entity\ScimEntry>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\Passbolt\Scim\Model\Entity\ScimEntry> deleteManyOrFail(iterable $entities, array $options = [])
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

        //@TODO confirm we want this
        $this->hasOne('Users')
            ->setDependent(false)
            ->setClassName(UsersTable::class)
            ->setBindingKey('foreign_key')
            ->setForeignKey('id');

        $this->hasOne('Groups')
            ->setDependent(false)
            ->setClassName(GroupsTable::class)
            ->setBindingKey('foreign_key')
            ->setForeignKey('id');
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
            ->requirePresence('external_identifier', false)
            ->allowEmptyString('external_identifier');

        $validator
            ->scalar('scim_name')
            ->maxLength('scim_name', 256)
            ->requirePresence('scim_name', false)
            ->allowEmptyString('scim_name');

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
        $rules->add([$this, 'isUniqueScimName'], 'uniqueScimName', [
            'errorField' => 'scim_name',
            'message' => __('An entry with the same `scim_name` and ResourceType already exist.'),
        ]);

        return $rules;
    }

    /**
     * Assert that the scim_name is unique among all non-deleted users
     *
     * @param \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry entity being saved
     * @return bool
     * @throws \Cake\Http\Exception\InternalErrorException if the scim_name field is not accessible
     */
    public function isUniqueScimName(ScimEntry $scimEntry): bool
    {
        if (!$scimEntry->isNew() && !$scimEntry->isDirty('scim_name')) {
            return true;
        }
        if (is_null($scimEntry->scim_name)) {
            return true;
        }
        $conditions = [
            $this->aliasField('scim_name') => $scimEntry->scim_name,
            $this->aliasField('foreign_model') => $scimEntry->foreign_model,
        ];
        if ($scimEntry->id) {
            $conditions[$this->aliasField('id') . ' !='] = $scimEntry->id;
        }
        $exist = $this->find()->where($conditions)->whereNull($this->aliasField('deleted'))->all()->count() > 0;

        return $exist === false;
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
