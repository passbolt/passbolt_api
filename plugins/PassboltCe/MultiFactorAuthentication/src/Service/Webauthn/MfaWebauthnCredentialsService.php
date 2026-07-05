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
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn;

use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential;
use Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable;
use Webauthn\CredentialRecord;

/**
 * Repository/adapter between the web-auth/webauthn-lib CredentialRecord and the
 * mfa_webauthn_credentials table. Encapsulates the base64url encoding of credential ids and the
 * (de)serialization of the authoritative credential material.
 *
 * @property \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $MfaWebauthnCredentials
 */
class MfaWebauthnCredentialsService
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
     */
    protected MfaWebauthnRelyingPartyService $relyingParty;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable
     */
    protected MfaWebauthnCredentialsTable $MfaWebauthnCredentials;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService|null $relyingParty Relying party service
     */
    public function __construct(?MfaWebauthnRelyingPartyService $relyingParty = null)
    {
        $this->relyingParty = $relyingParty ?? new MfaWebauthnRelyingPartyService();
        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $table */
        $table = $this->fetchTable('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');
        $this->MfaWebauthnCredentials = $table;
    }

    /**
     * Persist a newly registered credential.
     *
     * @param string $userId User id
     * @param \Webauthn\CredentialRecord $record The validated credential record
     * @param string|null $name User provided friendly name
     * @return \Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential
     */
    public function create(string $userId, CredentialRecord $record, ?string $name = null): MfaWebauthnCredential
    {
        $entity = $this->MfaWebauthnCredentials->newEntity([
            'name' => $name,
        ]);
        $entity->set('id', UuidFactory::uuid());
        $entity->set('user_id', $userId);
        $entity->set('credential_id', $this->encodeCredentialId($record->publicKeyCredentialId));
        $entity->set('data', $this->serializeRecord($record));
        $entity->set('sign_count', $record->counter);
        $entity->set('transports', json_encode($record->transports));
        $entity->set('aaguid', $record->aaguid->__toString());

        if (!$this->MfaWebauthnCredentials->save($entity)) {
            // A duplicate credential (same authenticator registered twice — normally blocked client
            // side by excludeCredentials) is a client error, not a 500.
            if (isset($entity->getErrors()['credential_id'])) {
                throw new BadRequestException(__('This security key is already registered.'));
            }
            throw new BadRequestException(__('The security key could not be saved.'));
        }

        return $entity;
    }

    /**
     * Update the stored credential after a successful assertion (bump the signature counter and the
     * authoritative record, stamp the last used date).
     *
     * @param \Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential $entity Credential entity
     * @param \Webauthn\CredentialRecord $record The updated credential record returned by the validator
     * @return void
     */
    public function updateAfterAssertion(MfaWebauthnCredential $entity, CredentialRecord $record): void
    {
        $entity->set('data', $this->serializeRecord($record));
        $entity->set('sign_count', $record->counter);
        $entity->set('last_used', DateTime::now());
        $this->MfaWebauthnCredentials->saveOrFail($entity);
    }

    /**
     * Find one of a user's credential entities by its base64url credential id.
     *
     * @param string $userId User id
     * @param string $credentialId The base64url encoded credential id
     * @return \Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential|null
     */
    public function findEntityByCredentialId(string $userId, string $credentialId): ?MfaWebauthnCredential
    {
        /** @var \Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential|null $entity */
        $entity = $this->MfaWebauthnCredentials->find()
            ->where([
                'user_id' => $userId,
                'credential_id' => $credentialId,
            ])
            ->first();

        return $entity;
    }

    /**
     * Get the deserialized credential record for a stored entity.
     *
     * @param \Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential $entity Credential entity
     * @return \Webauthn\CredentialRecord
     */
    public function getRecord(MfaWebauthnCredential $entity): CredentialRecord
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
     * Get the credential descriptors for a user, used to build excludeCredentials (registration) and
     * allowCredentials (authentication).
     *
     * @param string $userId User id
     * @return array<\Webauthn\PublicKeyCredentialDescriptor>
     */
    public function getDescriptors(string $userId): array
    {
        $descriptors = [];
        $entities = $this->MfaWebauthnCredentials->find()->where(['user_id' => $userId])->all();
        foreach ($entities as $entity) {
            $descriptors[] = $this->getRecord($entity)->getPublicKeyCredentialDescriptor();
        }

        return $descriptors;
    }

    /**
     * List a user's credentials as entities (for the management UI).
     *
     * @param string $userId User id
     * @return \Cake\Collection\CollectionInterface|array<\Passbolt\MultiFactorAuthentication\Model\Entity\MfaWebauthnCredential>
     */
    public function listForUser(string $userId): iterable
    {
        return $this->MfaWebauthnCredentials->find()
            ->where(['user_id' => $userId])
            ->orderBy(['created' => 'ASC'])
            ->all();
    }

    /**
     * Count a user's credentials.
     *
     * @param string $userId User id
     * @return int
     */
    public function countForUser(string $userId): int
    {
        return $this->MfaWebauthnCredentials->find()->where(['user_id' => $userId])->count();
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

        return $this->MfaWebauthnCredentials->delete($entity);
    }

    /**
     * Delete all the credentials belonging to a user.
     *
     * @param string $userId User id
     * @return void
     */
    public function deleteAllForUser(string $userId): void
    {
        $this->MfaWebauthnCredentials->deleteAll(['user_id' => $userId]);
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
