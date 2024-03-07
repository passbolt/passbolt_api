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

namespace Passbolt\ResourceTypes\Model\Table;

use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ResourceTypes Model
 *
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\HasMany $Resources
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType get($primaryKey, $options = [])
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType newEntity(array $data, array $options = [])
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType[] newEntities(array $data, array $options = [])
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType findOrCreate($search, ?callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType newEmptyEntity()
 * @method \Passbolt\ResourceTypes\Model\Entity\ResourceType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\Passbolt\ResourceTypes\Model\Entity\ResourceType>|iterable<\Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\ResourceTypes\Model\Entity\ResourceType>|iterable<\Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method iterable<\Passbolt\ResourceTypes\Model\Entity\ResourceType>|iterable<\Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method iterable<\Passbolt\ResourceTypes\Model\Entity\ResourceType>|iterable<\Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
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
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->notEmptyString('id', 'create');

        $validator
            ->utf8('name', __('The name should be a valid BMP-UTF8 string.'))
            ->requirePresence('name', 'create', __('A name is required.'))
            ->maxLength(
                'name',
                self::NAME_MAX_LENGTH,
                __('The name length should be maximum {0} characters.', self::NAME_MAX_LENGTH)
            )
            ->notEmptyString('name', __('The name should not be empty.'));

        $validator
            ->utf8('slug', __('The slug should be a valid BMP-UTF8 string.'))
            ->requirePresence('slug', 'create', __('A slug is required.'))
            ->maxLength(
                'slug',
                self::SLUG_MAX_LENGTH,
                __('The slug length should be maximum {0} characters.', self::SLUG_MAX_LENGTH)
            )
            ->notEmptyString('slug', __('The slug should not be empty'));

        $validator
            ->utf8('description', __('The description should be a valid BMP-UTF8 string.'))
            ->maxLength(
                'name',
                self::DESCRIPTION_MAX_LENGTH,
                __('The description length should be maximum {0} characters.', self::DESCRIPTION_MAX_LENGTH)
            )
            ->allowEmptyString('description');

        $validator
            ->utf8('definition', __('The definition should be a valid BMP-UTF8 string.'))
            ->requirePresence('definition', 'create', __('A definition is required.'))
            ->notEmptyString('definition', __('The definition should not be empty.'))
            ->add('definition', 'isValidJson', [
                'rule' => [$this, 'isValidJson'],
                'message' => __('The message should be valid JSON message.'),
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
    public static function getDefaultTypeId(): string
    {
        return UuidFactory::uuid('resource-types.id.password-string');
    }

    /**
     * Get the password and description type id
     *
     * @return string uuid
     */
    public static function getPasswordAndDescriptionTypeId(): string
    {
        return UuidFactory::uuid('resource-types.id.password-and-description');
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
