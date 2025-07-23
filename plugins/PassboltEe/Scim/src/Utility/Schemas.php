<?php
declare(strict_types=1);

namespace Passbolt\Scim\Utility;

use Exception;
use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Utility\Schema\GroupSchema;
use Passbolt\Scim\Utility\Schema\UserSchema;

/**
 * Schemas class
 */
class Schemas
{
    /**
     * Schema class maps
     *
     * @var array
     */
    public const MAPPING = [
        SchemaIdentifier::CORE_USER => UserSchema::class,
        SchemaIdentifier::CORE_GROUP => GroupSchema::class,
    ];

    /**
     * @param string $schemaId
     * @return bool
     */
    public static function isValid(string $schemaId): bool
    {
        return array_key_exists($schemaId, self::MAPPING);
    }

    /**
     * Return the list of Schemas objects
     *
     * @return array<\Passbolt\Scim\Utility\ScimObjectInterface>
     * @throws \Passbolt\Scim\Exception\ScimException
     * @throws \Exception
     */
    public static function getAll(): array
    {
        $schemas = [];
        foreach (self::MAPPING as $identifier => $class) {
            $schemas[] = self::build($identifier);
        }

        return $schemas;
    }

    /**
     * Build a schema object given the id
     *
     * @param string $schemaId
     * @return \Passbolt\Scim\Utility\ScimObjectInterface
     * @throws \Passbolt\Scim\Exception\ScimException
     * @throws \Exception
     */
    public static function build(string $schemaId): ScimObjectInterface
    {
        $schemaClass = self::MAPPING[$schemaId] ?? null;
        if (!$schemaClass) {
            throw new ScimException(__('Invalid Schema'));
        }

        $schema = new $schemaClass();
        if (!$schema instanceof ScimObjectInterface) {
            throw new Exception(sprintf('Invalid mapped class for schema id %s', $schemaId));
        }

        return $schema;
    }
}
