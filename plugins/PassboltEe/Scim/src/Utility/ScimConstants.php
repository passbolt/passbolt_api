<?php
declare(strict_types=1);

namespace Passbolt\Scim\Utility;

/**
 * ScimConstants class
 */
class ScimConstants
{
    public const CONTENT_TYPE = 'application/scim+json';

    public const ATTRIBUTE_MUTABILITY_READ_ONLY = 'readOnly';
    public const ATTRIBUTE_MUTABILITY_READ_WRITE = 'readWrite';
    public const ATTRIBUTE_MUTABILITY_IMMUTABLE = 'immutable';

    /**
     * @param string $mutability
     * @return bool
     */
    public static function isValidAttributeMutability(string $mutability): bool
    {
        return in_array($mutability, [
            self::ATTRIBUTE_MUTABILITY_READ_ONLY,
            self::ATTRIBUTE_MUTABILITY_READ_WRITE,
            self::ATTRIBUTE_MUTABILITY_IMMUTABLE,
        ]);
    }
}
