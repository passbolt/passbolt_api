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
namespace App\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BaseType;
use InvalidArgumentException;
use Symfony\Component\Serializer\SerializerInterface;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\CredentialRecord;
use Webauthn\Denormalizer\WebauthnSerializerFactory;

class WebauthnCredentialRecordType extends BaseType
{
    private static ?SerializerInterface $serializer = null;

    /**
     * @inheritDoc
     */
    public function toDatabase(mixed $value, Driver $driver): ?string
    {
        if ($value === null) {
            return null;
        }
        if (!$value instanceof CredentialRecord) {
            throw new InvalidArgumentException(sprintf(
                'Cannot marshal value of type "%s" to a WebAuthn CredentialRecord column.',
                get_debug_type($value),
            ));
        }

        return self::serializer()->serialize($value, 'json');
    }

    /**
     * @inheritDoc
     */
    public function toPHP(mixed $value, Driver $driver): ?CredentialRecord
    {
        if ($value === null) {
            return null;
        }

        return self::serializer()->deserialize((string)$value, CredentialRecord::class, 'json');
    }

    /**
     * @inheritDoc
     */
    public function marshal(mixed $value): ?CredentialRecord
    {
        if ($value === null || $value instanceof CredentialRecord) {
            return $value;
        }
        if (is_string($value) && $value !== '') {
            return self::serializer()->deserialize($value, CredentialRecord::class, 'json');
        }

        return null;
    }

    /**
     * Lazily build the Webauthn serializer.
     * We only need to (de)serialize CredentialRecord — none of the attestation
     * statement supports contribute to that serialization, so an empty manager
     * with only the "none" support is enough here.
     *
     * @return \Symfony\Component\Serializer\SerializerInterface
     */
    private static function serializer(): SerializerInterface
    {
        if (self::$serializer === null) {
            $manager = new AttestationStatementSupportManager();
            $manager->add(new NoneAttestationStatementSupport());
            self::$serializer = (new WebauthnSerializerFactory($manager))->create();
        }

        return self::$serializer;
    }
}
