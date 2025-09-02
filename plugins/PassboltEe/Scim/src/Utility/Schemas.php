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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Utility;

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

        return new $schemaClass();
    }
}
