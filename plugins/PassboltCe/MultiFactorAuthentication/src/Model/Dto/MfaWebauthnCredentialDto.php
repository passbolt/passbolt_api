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
namespace Passbolt\MultiFactorAuthentication\Model\Dto;

use App\Model\Entity\WebauthnCredential;
use Cake\I18n\DateTime;

readonly class MfaWebauthnCredentialDto
{
    /**
     * @param string $id Row uuid.
     * @param string $credentialId Base64url WebAuthn credential id.
     * @param string|null $name User-provided friendly name.
     * @param int $signCount Persisted signature counter.
     * @param string $aaguid Authenticator AAGUID as RFC 4122 uuid.
     * @param \Cake\I18n\DateTime|null $lastUsed Last successful assertion time.
     * @param \Cake\I18n\DateTime|null $created Row creation time.
     * @param \Cake\I18n\DateTime|null $modified Row modification time.
     */
    final public function __construct(
        public string $id,
        public string $credentialId,
        public ?string $name,
        public int $signCount,
        public string $aaguid,
        public ?DateTime $lastUsed,
        public ?DateTime $created,
        public ?DateTime $modified,
    ) {
    }

    /**
     * @param \App\Model\Entity\WebauthnCredential $credential Credential entity.
     * @return static
     */
    public static function fromEntity(WebauthnCredential $credential): static
    {
        return new static(
            $credential->id,
            $credential->credential_id,
            $credential->name,
            $credential->sign_count,
            $credential->data->aaguid->toRfc4122(),
            $credential->last_used,
            $credential->created,
            $credential->modified,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'credential_id' => $this->credentialId,
            'name' => $this->name,
            'sign_count' => $this->signCount,
            'aaguid' => $this->aaguid,
            'last_used' => $this->lastUsed,
            'created' => $this->created,
            'modified' => $this->modified,
        ];
    }
}
