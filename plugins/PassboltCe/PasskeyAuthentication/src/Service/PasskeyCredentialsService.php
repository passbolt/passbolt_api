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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Service;

use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential;
use Passbolt\PasskeyAuthentication\Model\Table\PasskeyCredentialsTable;
use Webauthn\CredentialRecord;

/**
 * Repository/adapter between the web-auth/webauthn-lib CredentialRecord and the passkey_credentials
 * table. Encapsulates the base64url encoding of credential ids, the (de)serialization of the
 * authoritative credential material, and the storage of the server half of the passphrase kit.
 *
 * The relying party building blocks (serializer, validators, rp id / origin) are reused from the
 * MultiFactorAuthentication plugin so both WebAuthn surfaces share a single, tested configuration.
 *
 * @property \Passbolt\PasskeyAuthentication\Model\Table\PasskeyCredentialsTable $PasskeyCredentials
 */
class PasskeyCredentialsService
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
     */
    protected MfaWebauthnRelyingPartyService $relyingParty;

    /**
     * @var \Passbolt\PasskeyAuthentication\Model\Table\PasskeyCredentialsTable
     */
    protected PasskeyCredentialsTable $PasskeyCredentials;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService|null $relyingParty Relying party service
     */
    public function __construct(?MfaWebauthnRelyingPartyService $relyingParty = null)
    {
        $this->relyingParty = $relyingParty ?? new MfaWebauthnRelyingPartyService();
        /** @var \Passbolt\PasskeyAuthentication\Model\Table\PasskeyCredentialsTable $table */
        $table = $this->fetchTable('Passbolt/PasskeyAuthentication.PasskeyCredentials');
        $this->PasskeyCredentials = $table;
    }

    /**
     * Persist a newly enrolled passkey credential together with the server half of the kit.
     *
     * @param string $userId User id
     * @param \Webauthn\CredentialRecord $record The validated credential record
     * @param string $serverKitKey Server half of the passphrase kit (base64 JWK)
     * @param string|null $name User provided friendly name
     * @return \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential
     */
    public function create(
        string $userId,
        CredentialRecord $record,
        string $serverKitKey,
        ?string $name = null
    ): PasskeyCredential {
        $entity = $this->PasskeyCredentials->newEntity([
            'name' => $name,
        ]);
        $entity->set('id', UuidFactory::uuid());
        $entity->set('user_id', $userId);
        $entity->set('credential_id', $this->encodeCredentialId($record->publicKeyCredentialId));
        $entity->set('data', $this->serializeRecord($record));
        $entity->set('server_kit_key', $serverKitKey);
        $entity->set('sign_count', $record->counter);
        $entity->set('transports', json_encode($record->transports));
        $entity->set('aaguid', $record->aaguid->__toString());

        $this->PasskeyCredentials->saveOrFail($entity);

        return $entity;
    }

    /**
     * Update the stored credential after a successful assertion (bump the signature counter and the
     * authoritative record, stamp the last used date).
     *
     * @param \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential $entity Credential entity
     * @param \Webauthn\CredentialRecord $record The updated credential record returned by the validator
     * @return void
     */
    public function updateAfterAssertion(PasskeyCredential $entity, CredentialRecord $record): void
    {
        $entity->set('data', $this->serializeRecord($record));
        $entity->set('sign_count', $record->counter);
        $entity->set('last_used', DateTime::now());
        $this->PasskeyCredentials->saveOrFail($entity);
    }

    /**
     * Find one of a user's credential entities by its base64url credential id.
     *
     * @param string $userId User id
     * @param string $credentialId The base64url encoded credential id
     * @return \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential|null
     */
    public function findEntityByCredentialId(string $userId, string $credentialId): ?PasskeyCredential
    {
        /** @var \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential|null $entity */
        $entity = $this->PasskeyCredentials->find()
            ->where([
                'user_id' => $userId,
                'credential_id' => $credentialId,
            ])
            ->first();

        return $entity;
    }

    /**
     * Get the server half of the passphrase kit for a stored credential.
     *
     * @param \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential $entity Credential entity
     * @return string|null
     */
    public function getServerKitKey(PasskeyCredential $entity): ?string
    {
        $key = $entity->get('server_kit_key');

        return is_string($key) && $key !== '' ? $key : null;
    }

    /**
     * Get the deserialized credential record for a stored entity.
     *
     * @param \Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential $entity Credential entity
     * @return \Webauthn\CredentialRecord
     */
    public function getRecord(PasskeyCredential $entity): CredentialRecord
    {
        /** @var \Webauthn\CredentialRecord $record */
        $record = $this->relyingParty->getSerializer()->deserialize(
            $entity->get('data'),
            CredentialRecord::class,
            'json'
        );

        return $record;
    }

    /**
     * Get the credential descriptors for a user, used to build excludeCredentials (enrollment) and
     * allowCredentials (login).
     *
     * @param string $userId User id
     * @return array<\Webauthn\PublicKeyCredentialDescriptor>
     */
    public function getDescriptors(string $userId): array
    {
        $descriptors = [];
        $entities = $this->PasskeyCredentials->find()->where(['user_id' => $userId])->all();
        foreach ($entities as $entity) {
            $descriptors[] = $this->getRecord($entity)->getPublicKeyCredentialDescriptor();
        }

        return $descriptors;
    }

    /**
     * List a user's credentials as entities (for the management UI).
     *
     * @param string $userId User id
     * @return \Cake\Collection\CollectionInterface|array<\Passbolt\PasskeyAuthentication\Model\Entity\PasskeyCredential>
     */
    public function listForUser(string $userId): iterable
    {
        return $this->PasskeyCredentials->find()
            ->where(['user_id' => $userId])
            ->orderBy(['created' => 'ASC'])
            ->all();
    }

    /**
     * Whether the user has at least one passkey credential enrolled.
     *
     * @param string $userId User id
     * @return bool
     */
    public function hasCredentials(string $userId): bool
    {
        return $this->countForUser($userId) > 0;
    }

    /**
     * Count a user's credentials.
     *
     * @param string $userId User id
     * @return int
     */
    public function countForUser(string $userId): int
    {
        return $this->PasskeyCredentials->find()->where(['user_id' => $userId])->count();
    }

    /**
     * Delete a single credential belonging to a user.
     *
     * @param string $userId User id
     * @param string $credentialId The base64url encoded credential id
     * @return bool True if a credential was deleted
     */
    public function deleteOne(string $userId, string $credentialId): bool
    {
        $entity = $this->findEntityByCredentialId($userId, $credentialId);
        if ($entity === null) {
            return false;
        }

        return $this->PasskeyCredentials->delete($entity);
    }

    /**
     * Delete all the credentials belonging to a user.
     *
     * @param string $userId User id
     * @return void
     */
    public function deleteAllForUser(string $userId): void
    {
        $this->PasskeyCredentials->deleteAll(['user_id' => $userId]);
    }

    /**
     * Serialize a credential record to its JSON storage representation.
     *
     * @param \Webauthn\CredentialRecord $record Credential record
     * @return string
     */
    protected function serializeRecord(CredentialRecord $record): string
    {
        return $this->relyingParty->getSerializer()->serialize($record, 'json');
    }

    /**
     * Base64url encode a raw (binary) credential id.
     *
     * @param string $rawCredentialId Raw credential id
     * @return string
     */
    public function encodeCredentialId(string $rawCredentialId): string
    {
        return rtrim(strtr(base64_encode($rawCredentialId), '+/', '-_'), '=');
    }
}
