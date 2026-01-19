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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Model\Table;

use App\Model\Table\TableCleanupProviderInterface;
use App\Model\Traits\Cleanup\TableCleanupTrait;
use App\Model\Validation\ArmoredMessage\IsParsableMessageValidationRule;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Passbolt\Metadata\Model\Rule\IsValidEncryptedMetadataPrivateKey;
use Passbolt\Metadata\Model\Rule\UserAndMetadataKeyIdIsUniqueNullableCombo;
use Passbolt\Metadata\Model\Rule\UserIsActiveAndNotDeletedIfPresent;

/**
 * MetadataPrivateKeys Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Creator
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasOne $Modifier
 * @property \Passbolt\Metadata\Model\Table\MetadataKeysTable&\Cake\ORM\Association\BelongsTo $MetadataKeys
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey newEmptyEntity()
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey newEntity(array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[] newEntities(array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[]|iterable<mixed, \Cake\Datasource\EntityInterface>|false saveMany(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> saveManyOrFail(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[]|iterable<mixed, \Cake\Datasource\EntityInterface>|false deleteMany(iterable $entities, $options = [])
 * @method \Passbolt\Metadata\Model\Entity\MetadataPrivateKey[]|iterable<mixed, \Cake\Datasource\EntityInterface> deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MetadataPrivateKeysTable extends Table implements TableCleanupProviderInterface
{
    use TableCleanupTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('metadata_private_keys');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('MetadataKeys', [
            'foreignKey' => 'metadata_key_id',
            'joinType' => 'INNER',
            'className' => 'Passbolt/Metadata.MetadataKeys',
        ]);
        $this->belongsTo('Users', ['foreignKey' => 'user_id']);

        $this->hasOne('Creator', [
            'className' => 'Users',
            'bindingKey' => 'created_by',
            'foreignKey' => 'id',
        ]);
        $this->hasOne('Modifier', [
            'className' => 'Users',
            'bindingKey' => 'modified_by',
            'foreignKey' => 'id',
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
            ->uuid('metadata_key_id', __('The metadata key identifier should be a valid UUID.'))
            ->notEmptyString('metadata_key_id', __('The metadata key identifier should not be empty.'));

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->allowEmptyString('user_id');

        $validator
            ->ascii('data', __('The data should be a valid ASCII string.'))
            ->requirePresence('data', 'create', __('A data is required.'))
            ->notEmptyString('data', __('The data should not be empty.'))
            ->add('data', 'isValidOpenPGPMessage', new IsParsableMessageValidationRule());

        $validator
            ->uuid('created_by', __('The identifier of the user who created the metadata key should be a valid UUID.')) // phpcs:ignore;
            ->allowEmptyString('created_by');

        $validator
            ->uuid('modified_by', __('The identifier of the user who modified the metadata key should be a valid UUID.')) // phpcs:ignore;
            ->allowEmptyString('modified_by');

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
        $rules->add($rules->existsIn('metadata_key_id', 'MetadataKeys'), ['errorField' => 'metadata_key_id']);

        $rules->addCreate(new UserIsActiveAndNotDeletedIfPresent(), 'isUserActiveIfPresent', [
            'errorField' => 'user_id',
            'message' => __('The user does not exist or is not active or is deleted.'),
        ]);

        $rules->addCreate(new UserAndMetadataKeyIdIsUniqueNullableCombo(), '_isUnique', [
            'errorField' => 'user_id',
            'message' => __('The metadata key id & user id combination is already in use.'),
        ]);

        $rules->add(new IsValidEncryptedMetadataPrivateKey(), 'isValidEncryptedMetadataPrivateKey', [
            'errorField' => 'data',
            'message' => __('The data is not valid. Please make sure it is encrypted for the correct key.'),
        ]);

        return $rules;
    }

    /**
     * Delete all records where associated users are deleted
     *
     * @param bool|null $dryRun false
     * @return int of affected records
     */
    public function cleanupHardDeletedUsers(?bool $dryRun = false): int
    {
        $association = 'Users';

        $query = $this->selectQuery();
        $query = $query
            ->select(['id'])
            ->leftJoinWith($association)
            ->where([
                $query->newExpr()->isNull($this->getModelNameFromAssociation($association) . '.id'),
                $query->newExpr()->isNotNull($this->aliasField('user_id')), // Do not remove metadata server key
            ]);

        return $this->cleanupHardDeleted('Users', $dryRun, $query);
    }

    /**
     * Delete all records where associated users are soft deleted
     *
     * @param bool|null $dryRun false
     * @return int of affected records
     */
    public function cleanupSoftDeletedUsers(?bool $dryRun = false): int
    {
        $association = 'Users';

        $query = $this->selectQuery();
        $query = $query
            ->select(['id'])
            ->leftJoinWith($association)
            ->where([
                $this->getModelNameFromAssociation($association) . '.deleted' => true,
                $query->newExpr()->isNotNull($this->aliasField('user_id')), // Do not remove metadata server key
            ]);

        return $this->cleanupSoftDeleted('Users', $dryRun, $query);
    }

    /**
     * Retrieves a list of cleanup methods (first-class callables) implemented by this table.
     *
     * @return array<int, callable> List of callables
     */
    public function getCleanupMethods(): array
    {
        return [
            $this->cleanupHardDeletedUsers(...),
            $this->cleanupSoftDeletedUsers(...),
        ];
    }
}
