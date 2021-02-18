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
 * @since         3.0.0
 */

namespace App\Model\Table;

use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ResourceTypes Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Resources
 * @method \App\Model\Entity\ResourceType get($primaryKey, ?array $options = [])
 * @method \App\Model\Entity\ResourceType newEntity($data = null, ?array $options = [])
 * @method \App\Model\Entity\ResourceType[] newEntities(array $data, ?array $options = [])
 * @method \App\Model\Entity\ResourceType|bool save(\Cake\Datasource\EntityInterface $entity, ?array $options = [])
 * @method \App\Model\Entity\ResourceType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, ?array $options = [])
 * @method \App\Model\Entity\ResourceType[] patchEntities($entities, array $data, ?array $options = [])
 * @method \App\Model\Entity\ResourceType findOrCreate($search, callable $callback = null, ?array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResourceTypesTable extends Table
{
    public const NAME_MAX_LENGTH = 64;
    public const SLUG_MAX_LENGTH = 64;
    public const DESCRIPTION_MAX_LENGTH = 255;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('resource_types');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Resources');
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
            ->uuid('id')
            ->notEmptyString('id', 'create');

        $validator
            ->utf8('name')
            ->requirePresence('name', 'create')
            ->maxLength(
                'name',
                self::NAME_MAX_LENGTH,
                __('The name length should be maximum {0} characters.', self::NAME_MAX_LENGTH)
            )
            ->notEmptyString('name', __('The name can not be empty'));

        $validator
            ->utf8('slug')
            ->requirePresence('slug', 'create')
            ->maxLength(
                'slug',
                self::SLUG_MAX_LENGTH,
                __('The slug length should be maximum {0} characters.', self::SLUG_MAX_LENGTH)
            )
            ->notEmptyString('slug', __('The slug can not be empty'));

        $validator
            ->utf8('description')
            ->maxLength(
                'name',
                self::DESCRIPTION_MAX_LENGTH,
                __('The description length should be maximum {0} characters.', self::DESCRIPTION_MAX_LENGTH)
            )
            ->allowEmptyString('description');

        $validator
            ->utf8('definition')
            ->requirePresence('definition', 'create', __('A definition is required.'))
            ->notEmptyString('definition', __('The definition cannot be empty.'))
            ->add('definition', 'isValidJson', [
                'rule' => [$this, 'isValidJson'],
                'message' => __('The message is not a valid JSON.'),
            ]);

        return $validator;
    }

    /**
     * Check true if field is a valid json message.
     *
     * @param string $check Value to check
     * @param array $context A key value list of data containing the validation context.
     * @return bool Success
     */
    public function isValidJson(string $check, array $context)
    {
        return json_decode($check, true) !== null;
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
        $rules->addCreate(
            $rules->isUnique(
                ['slug'],
                __('A resource type already exists with this slug.')
            ),
            'slug_unique'
        );
        $rules->addCreate(
            $rules->isUnique(
                ['definition'],
                __('A resource type already exists with this definition.')
            ),
            'definition_unique'
        );

        return $rules;
    }

    /**
     * Get the default resource type id
     *
     * @return string uuid
     */
    public static function getDefaultTypeId()
    {
        return UuidFactory::uuid('resource-types.id.password-string');
    }

    /**
     * Make sure the definition is de-serialized after find
     *
     * @param bool $contain is the find done from an association
     * @return \Closure
     */
    public static function resultFormatter($contain = false)
    {
        if (!$contain) {
            return function (CollectionInterface $results) {
                return $results->map(function ($row) {
                    $row['definition'] = json_decode($row['definition']);

                    return $row;
                });
            };
        } else {
            return function (CollectionInterface $results) {
                return $results->map(function ($row) {
                    $row['resource_type']['definition'] = json_decode($row['resource_type']['definition']);

                    return $row;
                });
            };
        }
    }
}
