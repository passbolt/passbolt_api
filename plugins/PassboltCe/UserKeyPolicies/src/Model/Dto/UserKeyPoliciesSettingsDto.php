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
 * @since         5.2.0
 */
namespace Passbolt\UserKeyPolicies\Model\Dto;

class UserKeyPoliciesSettingsDto
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
     * Key type RSA
     *
     * @var string
     */
    public const KEY_TYPE_RSA = 'rsa';

    /**
     * Key type curve
     *
     * @var string
     */
    public const KEY_TYPE_CURVE = 'curve';

    /**
     * Default value of key type
     *
     * @var string
     */
    public const DEFAULT_KEY_TYPE = self::KEY_TYPE_CURVE;

    /**
     * Default key size (null for ECC).
     *
     * @var int|null
     */
    public const DEFAULT_KEY_SIZE = null;

    /**
     * Default key curve (should be used with ECC - which is default).
     *
     * @var string|null
     */
    public const DEFAULT_KEY_CURVE = self::KEY_CURVE_ED25519_LEGACY;

    /**
     * Key size: 3072
     *
     * @var int
     */
    public const KEY_SIZE_3072 = 3072;

    /**
     * Key size: 4096
     *
     * @var int
     */
    public const KEY_SIZE_4096 = 4096;

    /**
     * Legacy curve type for ECC.
     *
     * @var string
     */
    public const KEY_CURVE_ED25519_LEGACY = 'curve25519_legacy+ed25519_legacy';

    /**
     * @var string|null
     */
    public ?string $preferred_key_type = null;

    /**
     * @var int|null
     */
    public ?int $preferred_key_size = null;

    /**
     * @var string|null
     */
    public ?string $preferred_key_curve = null;

    /**
     * @var string|null
     */
    public ?string $source = null;

    /**
     * @param string|null $keyType Key type.
     * @param string|int|null $keySize Key size.
     * @param string|null $keyCurve Key curve.
     * @param string|null $source Source of these settings (can be: file, env or default).
     */
    public function __construct(
        ?string $keyType,
        int|string|null $keySize,
        ?string $keyCurve,
        ?string $source
    ) {
        $this->preferred_key_type = self::marshalKeyType($keyType);
        $this->preferred_key_size = self::marshalKeySize($keySize);
        $this->preferred_key_curve = $keyCurve === 'null' ? null : $keyCurve;
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
        switch (strtolower($keyType)) {
            case self::KEY_TYPE_RSA:
                $keyType = self::KEY_TYPE_RSA;
                break;
            case self::KEY_TYPE_CURVE:
                $keyType = self::KEY_TYPE_CURVE;
                break;
        }

        return $keyType;
    }

    /**
     * @param mixed $keySize Value to marshal.
     * @return int|null
     */
    private static function marshalKeySize(mixed $keySize): ?int
    {
        if (is_int($keySize) || is_null($keySize)) {
            return $keySize;
        }

        $keySize = (int)$keySize;

        /**
         * In scenarios where converting value to integer return zero,
         * i.e. when 'null' value is set via env variable and converted to integer,
         * we consider it null which is also a fallback value.
         */
        if ($keySize === 0) {
            $keySize = null;
        }

        return $keySize;
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
            $data['preferred_key_size'] ?? null,
            $data['preferred_key_curve'] ?? null,
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
            'preferred_key_size' => $this->preferred_key_size,
            'preferred_key_curve' => $this->preferred_key_curve,
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
            'preferred_key_type' => self::DEFAULT_KEY_TYPE,
            'preferred_key_size' => self::DEFAULT_KEY_SIZE, // null for ECC
            'preferred_key_curve' => self::DEFAULT_KEY_CURVE,
            'source' => self::SOURCE_DEFAULT,
        ], $data));
    }
}
