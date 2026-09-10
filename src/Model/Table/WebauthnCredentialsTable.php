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
 * @since         5.17.0
 */
namespace App\Model\Table;

use App\Database\Type\WebauthnCredentialRecordType;
use App\Model\Entity\WebauthnCredential;
use ArrayObject;
use Cake\Database\TypeFactory;
use Cake\Event\EventInterface;
use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Webauthn\CredentialRecord;
use Webauthn\PublicKeyCredentialDescriptor;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 */
abstract class WebauthnCredentialsTable extends Table
{
    /**
     * @return string Discriminator value written to and filtered from the `type` column.
     */
    abstract public function getType(): string;

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        if (!TypeFactory::getMapped('webauthn_credential_record')) {
            TypeFactory::map('webauthn_credential_record', WebauthnCredentialRecordType::class);
        }

        $this->setTable('webauthn_credentials');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->setEntityClass(WebauthnCredential::class);

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->getSchema()->setColumnType('data', 'webauthn_credential_record');
    }

    /**
     * @param \Cake\Event\EventInterface $event Event.
     * @param \Cake\ORM\Query\SelectQuery $query Query.
     * @param \ArrayObject $options Options.
     * @param bool $primary Whether this is the primary query.
     * @return void
     */
    public function beforeFind(EventInterface $event, SelectQuery $query, ArrayObject $options, bool $primary): void
    {
        $query->where([$this->aliasField('type') => $this->getType()]);
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->uuid('id', __('The identifier should be a valid UUID.'))
            ->allowEmptyString('id', __('The identifier should not be empty.'), 'create');

        $validator
            ->uuid('user_id', __('The user identifier should be a valid UUID.'))
            ->requirePresence('user_id', 'create', __('A user identifier is required.'))
            ->notEmptyString('user_id', __('The user identifier should not be empty.'));

        $validator
            ->scalar('type')
            ->requirePresence('type', 'create', __('A credential type is required.'))
            ->notEmptyString('type', __('The credential type should not be empty.'))
            ->maxLength('type', 16, __('The credential type length should be maximum {0} characters.', 16))
            ->add('type', 'matchesProviderType', [
                'rule' => fn($value) => $value === $this->getType(),
                'message' => __('The credential type must be "{0}".', $this->getType()),
            ]);

        $validator
            ->ascii('credential_id', __('The credential id should be a valid ASCII string.'))
            ->maxLength(
                'credential_id',
                1364,
                __('The credential id length should be maximum {0} characters.', 1364),
            )
            ->requirePresence('credential_id', 'create', __('A credential id is required.'))
            ->notEmptyString('credential_id', __('The credential id should not be empty.'));

        $validator
            ->requirePresence('data', 'create', __('A credential data is required.'))
            ->notEmptyString('data', __('The credential data should not be empty.'))
            ->add('data', 'isCredentialRecord', [
                'rule' => fn($value) => $value instanceof CredentialRecord,
                'message' => __('The credential data should be a valid WebAuthn credential record.'),
            ]);

        $validator
            ->nonNegativeInteger('sign_count', __('The sign count should be a non-negative integer.'))
            ->requirePresence('sign_count', 'create', __('A sign count is required.'));

        $validator
            ->utf8('name', __('The name should be a valid UTF-8 string.'))
            ->maxLength('name', 255, __('The name length should be maximum {0} characters.', 255))
            ->allowEmptyString('name');

        $validator->dateTime('last_used')->allowEmptyDateTime('last_used');

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add(
            $rules->isUnique(
                ['credential_id', 'type'],
                __('This credential is already registered.'),
            ),
            'unique_credential_id',
        );
        $rules->add(
            $rules->isUnique(
                ['user_id', 'name'],
                [
                    'allowMultipleNulls' => true,
                    'message' => __('A credential with this name already exists for this user.'),
                ],
            ),
            'unique_user_name',
        );

        return $rules;
    }

    /**
     * @param string $userId User uuid.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByUserId(string $userId): SelectQuery
    {
        return $this->find()
            ->where([$this->aliasField('user_id') => $userId])
            ->orderByAsc($this->aliasField('created'));
    }

    /**
     * @param string $userId User uuid.
     * @return array<\App\Model\Entity\WebauthnCredential>
     */
    public function listForUser(string $userId): array
    {
        /** @var array<\App\Model\Entity\WebauthnCredential> $rows */
        $rows = $this->findByUserId($userId)->toArray();

        return $rows;
    }

    /**
     * @param string $userId User uuid.
     * @return int
     */
    public function countForUser(string $userId): int
    {
        return $this->findByUserId($userId)->count();
    }

    /**
     * @param string $userId User uuid.
     * @return bool
     */
    public function hasAnyForUser(string $userId): bool
    {
        return $this->countForUser($userId) > 0;
    }

    /**
     * @param string $credentialId Base64url credential id.
     * @return \App\Model\Entity\WebauthnCredential|null
     */
    public function findOneByCredentialId(string $credentialId): ?WebauthnCredential
    {
        /** @var \App\Model\Entity\WebauthnCredential|null $entity */
        $entity = $this->find()
            ->where([$this->aliasField('credential_id') => $credentialId])
            ->first();

        return $entity;
    }

    /**
     * @param string $userId User uuid.
     * @return array<\Webauthn\PublicKeyCredentialDescriptor>
     */
    public function descriptorsForUser(string $userId): array
    {
        $descriptors = [];
        foreach ($this->listForUser($userId) as $credential) {
            $record = $credential->data;
            if (!$record instanceof CredentialRecord) {
                continue;
            }
            $descriptors[] = PublicKeyCredentialDescriptor::create(
                $record->type,
                $record->publicKeyCredentialId,
                $record->transports,
            );
        }

        return $descriptors;
    }

    /**
     * Clone-detection policy per WebAuthn spec §6.1.1 (Signature Counter Considerations) —
     * a stored+reported zero counter means the authenticator opted out; otherwise the
     * counter must be strictly increasing.
     *
     * Write helpers here (and in `deleteAllForUser`) carry an explicit `type` filter —
     * `beforeFind` does not fire on `updateAll` / `deleteAll`.
     *
     * @param \App\Model\Entity\WebauthnCredential $stored Currently persisted credential.
     * @param \Webauthn\CredentialRecord $updated Post-assertion credential record.
     * @return bool
     */
    public function updateAfterAssertion(WebauthnCredential $stored, CredentialRecord $updated): bool
    {
        $newCount = $updated->counter;

        if ($newCount === 0 && $stored->sign_count === 0) {
            $this->updateAll(
                ['last_used' => DateTime::now()],
                [
                    'id' => $stored->id,
                    'type' => $this->getType(),
                ],
            );

            return true;
        }

        $affected = $this->updateAll(
            [
                'sign_count' => $newCount,
                'last_used' => DateTime::now(),
            ],
            [
                'id' => $stored->id,
                'type' => $this->getType(),
                'sign_count <' => $newCount,
            ],
        );

        return $affected === 1;
    }

    /**
     * @param string $userId User uuid.
     * @param string $credentialId Row uuid.
     * @return bool
     */
    public function deleteOne(string $userId, string $credentialId): bool
    {
        $entity = $this->find()
            ->where([
                $this->aliasField('id') => $credentialId,
                $this->aliasField('user_id') => $userId,
            ])
            ->first();
        if ($entity === null) {
            return false;
        }

        return $this->delete($entity);
    }

    /**
     * @param string $userId User uuid.
     * @return int
     */
    public function deleteAllForUser(string $userId): int
    {
        return $this->deleteAll([
            $this->aliasField('user_id') => $userId,
            $this->aliasField('type') => $this->getType(),
        ]);
    }
}
