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
 * @since         5.1.1
 */
namespace Passbolt\UserGpgKeyPolicies\Model\Dto;

class UserGpgKeyPoliciesSettingsDto
{
    /**
     * Source default.
     *
     * @var string
     */
    public const SOURCE_DEFAULT = 'default';

    /**
     * Source file.
     *
     * @var string
     */
    public const SOURCE_FILE = 'file';

    /**
     * Source env.
     *
     * @var string
     */
    public const SOURCE_ENV = 'env';

    /**
     * GPG key type RSA
     *
     * @var string
     */
    public const GPG_KEY_TYPE_RSA = 'RSA';

    /**
     * GPG key type EdDSA
     *
     * @var string
     */
    public const GPG_KEY_TYPE_EDDSA = 'EdDSA';

    /**
     * Default value of gpg key type
     *
     * @var string
     */
    public const DEFAULT_GPG_KEY_TYPE = self::GPG_KEY_TYPE_RSA;

    /**
     * @var string|null
     */
    public ?string $preferred_key_type = null;

    /**
     * @var string|null
     */
    public ?string $source = null;

    /**
     * @param string|null $keyType Key type.
     * @param string|null $source Source of these settings (can be env or default).
     */
    public function __construct(
        string|null $keyType,
        ?string     $source
    )
    {
        $this->preferred_key_type = self::marshalKeyType($keyType);
        $this->source = $source;
    }

    /**
     * Normalize a given GPG key type.
     *
     * This method ensures that the given key type string matches one of the supported key types,
     * normalizing its case to the expected constant value. If the key type matches a known type
     * (e.g., RSA or EDDSA), it is returned using the exact casing defined by GPG library.
     *
     * @param string $keyType The input key type to normalize (case-insensitive).
     * @return string The normalized key type, matching one of the supported key type constants.
     */
    private static function marshalKeyType(string $keyType): string
    {
        if ($keyType && is_string($keyType)) {
            switch (strtolower($keyType)) {
                case strtolower(self::GPG_KEY_TYPE_RSA):
                    $keyType = self::GPG_KEY_TYPE_RSA;
                    break;
                case strtolower(self::GPG_KEY_TYPE_EDDSA):
                    $keyType = self::GPG_KEY_TYPE_EDDSA;
                    break;
            }
        }

        return $keyType;
    }

    /**
     * Returns object of itself from provided array.
     *
     * @param array $data Data.
     * @return self
     */
    public static function createFromArray(array $data): self
    {
        return new self(
            $data['preferred_key_type'] ?? null,
            $data['source'] ?? null,
        );
    }

    /**
     * Returns array representation of the object.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'preferred_key_type' => $this->preferred_key_type,
            'source' => $this->source,
        ];
    }

    /**
     * Create DTO from default policies settings.
     *
     * @param array $data Data to override.
     * @return self
     */
    public static function createFromDefault(array $data = []): self
    {
        return self::createFromArray(array_merge([
            'preferred_key_type' => self::DEFAULT_GPG_KEY_TYPE,
            'source' => self::SOURCE_DEFAULT,
        ], $data));
    }
}
