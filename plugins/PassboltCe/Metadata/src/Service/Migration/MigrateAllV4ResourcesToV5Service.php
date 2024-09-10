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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Service\Migration;

use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Model\Table\ResourcesTable;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\OpenPGP\OpenPGPBackend;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class MigrateAllV4ResourcesToV5Service
{
    use LocatorAwareTrait;

    private ResourcesTable $Resources;

    /**
     * Result of migration.
     *
     * @var array
     */
    private array $result = [
        'success' => true,
        'errors' => [],
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->Resources = $this->fetchTable('Resources');
    }

    /**
     * Migrates all V4 resources to V5.
     *
     * @return array Result
     */
    public function migrate(): array
    {
        $v4ResourceTypes = [
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_STRING),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_AND_DESCRIPTION),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_STANDALONE_TOTP),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP),
        ];
        /** @var \App\Model\Entity\Resource[] $resources */
        $resources = $this->Resources
            ->find()
            ->contain(['Permissions.Users.Gpgkeys', 'Creator.Gpgkeys'])
            ->where(['resource_type_id IN' => $v4ResourceTypes])
            ->all()
            ->toArray();

        if (empty($resources)) {
            $this->addError(['error_message' => __('No resources to migrate.')]);

            return $this->getResult();
        }

        foreach ($resources as $resource) {
            $isPersonal = count($resource->permissions) > 1 ? false : true;

            $dto = MetadataResourceDto::fromArray($resource->toArray());
            // Continue with next resource if any error
            try {
                if ($dto->isV5()) {
                    throw new InternalErrorException(__('Resource ID "{0}" is already V5', $resource->id));
                }

                if ($isPersonal) {
                    $this->migratePersonal($dto, $resource);
                } else {
                    $this->migrateShared($dto, $resource);
                }
            } catch (\Exception $e) {
                $error = ['resource_id' => $resource->id, 'error_message' => $e->getMessage()];
                if (Configure::read('debug')) {
                    $error['trace'] = $e->getTraceAsString();
                }
                $this->addError($error);
            }
        }

        return $this->getResult();
    }

    /**
     * Returns migration result.
     *
     * @return array
     */
    public function getResult(): array
    {
        $this->result['success'] = empty($this->result['errors']);

        return $this->result;
    }

    /**
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $dto DTO.
     * @param \App\Model\Entity\Resource $resource Resource entity.
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException When resource type mapping is does not exist.
     */
    private function migratePersonal(MetadataResourceDto $dto, Resource $resource): void
    {
        $metadataArray = $dto->getClearTextMetadata();
        /** @var \App\Model\Entity\Permission $permission */
        $permission = $resource->get('permissions')[0];
        $user = $permission->user;

        try {
            $openpgp = $this->getOpenPGPBackendForEncrypt($user);
            $metadataClearText = json_encode($metadataArray, JSON_THROW_ON_ERROR);
            $metadataEncrypted = $openpgp->encrypt($metadataClearText, true);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage() . ' ';
            $msg .= __('The metadata could not be encrypted with the user id: {0}.', $user->id);
            throw new InternalErrorException($msg, 500, $exception);
        }

        $this->updateResource($resource, [
            'name' => null,
            'username' => null,
            'uri' => null,
            'description' => null,
            'resource_type_id' => $this->getV5ResourceType($resource->resource_type_id),
            'metadata' => $metadataEncrypted,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => 'user_key',
        ]);
    }

    /**
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $dto DTO.
     * @param \App\Model\Entity\Resource $resource Resource entity.
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no metadata key record.
     * @throws \Cake\Http\Exception\InternalErrorException When resource type mapping is does not exist.
     */
    private function migrateShared(MetadataResourceDto $dto, Resource $resource): void
    {
        $metadataArray = $dto->getClearTextMetadata();
        $metadataKey = $this->getMetadataKeyForEncryption();

        try {
            $openpgp = $this->getOpenPGPBackendForMetadataKey($metadataKey);
            $metadataClearText = json_encode($metadataArray, JSON_THROW_ON_ERROR);
            $metadataEncrypted = $openpgp->encrypt($metadataClearText, true);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage() . ' ';
            $msg .= __('The metadata could not be encrypted with the metadata key id: {0}.', $metadataKey->id);
            throw new InternalErrorException($msg, 500, $exception);
        }

        $this->updateResource($resource, [
            'name' => null,
            'username' => null,
            'uri' => null,
            'description' => null,
            'resource_type_id' => $this->getV5ResourceType($resource->resource_type_id),
            'metadata' => $metadataEncrypted,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => 'shared_key',
        ]);
    }

    /**
     * Get the OpenPGP Backend ready to encryption with user key
     *
     * @param \App\Model\Entity\User $user entity
     * @return \App\Utility\OpenPGP\OpenPGPBackend backend configured to use server keys
     * @throws \Cake\Http\Exception\InternalErrorException if the server key cannot be loaded
     */
    private function getOpenPGPBackendForEncrypt(User $user): OpenPGPBackend
    {
        $gpg = OpenPGPBackendFactory::get();
        $gpg->clearKeys();

        // Check if config contains fingerprint
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->assertFingerprint($fingerprint);

        // Check if config contains valid passphrase
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $this->assertServerPassphrase($passphrase);

        // Set sign key as the one from the server
        try {
            $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to sign.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        // Set encryption key as the one from the user
        try {
            $this->assertUserKey($user);
        } catch (\Exception $exception) {
            if (Configure::read('debug')) {
                Log::error(json_encode($user));
            }
            $msg = __('Could not validate user data.');
            throw new InternalErrorException($msg, 500, $exception);
        }
        try {
            $gpg->setEncryptKeyFromFingerprint($user->gpgkey->fingerprint);
        } catch (\Exception $exception) {
            // Try to import the key in keyring again
            try {
                $gpg->importKeyIntoKeyring($user->gpgkey->armored_key);
                $gpg->setEncryptKeyFromFingerprint($user->gpgkey->fingerprint);
            } catch (\Exception $exception) {
                if (Configure::read('debug')) {
                    Log::error(json_encode($user));
                }
                $msg = __('Could not import the user OpenPGP key.');
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg;
    }

    /**
     * Get the OpenPGP Backend ready to encryption with shared metadata key
     *
     * @param \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey Metadata entity object.
     * @return \App\Utility\OpenPGP\OpenPGPBackend backend configured to use server keys
     * @throws \Cake\Http\Exception\InternalErrorException if the server key cannot be loaded
     */
    private function getOpenPGPBackendForMetadataKey(MetadataKey $metadataKey): OpenPGPBackend
    {
        $gpg = OpenPGPBackendFactory::get();
        $gpg->clearKeys();

        // Check if config contains fingerprint
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->assertFingerprint($fingerprint);

        // Check if config contains valid passphrase
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $this->assertServerPassphrase($passphrase);

        // Set sign key as the one from the server
        try {
            $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to sign.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        // Set encryption key as the metadata key
        try {
            $this->assertMetadataKey($metadataKey);
        } catch (\Exception $exception) {
            if (Configure::read('debug')) {
                Log::error(json_encode($metadataKey));
            }
            $msg = __('Could not validate metadata key data.');
            throw new InternalErrorException($msg, 500, $exception);
        }
        try {
            $gpg->setEncryptKeyFromFingerprint($metadataKey->fingerprint);
        } catch (\Exception $exception) {
            // Try to import the key in keyring again
            try {
                $gpg->importKeyIntoKeyring($metadataKey->armored_key);
                $gpg->setEncryptKeyFromFingerprint($metadataKey->fingerprint);
            } catch (\Exception $exception) {
                if (Configure::read('debug')) {
                    Log::error(json_encode($metadataKey));
                }
                $msg = __('Could not import the metadata OpenPGP key.');
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg;
    }

    /**
     * @param mixed $fingerprint fingerprint
     * @throws \Cake\Http\Exception\InternalErrorException
     * @return void
     */
    private function assertFingerprint($fingerprint): void
    {
        if (!is_string($fingerprint) || !PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            $msg = __('The config for the server private key fingerprint is not available or incomplete.');
            throw new InternalErrorException($msg);
        }
    }

    /**
     * @param mixed $passphrase passphrase
     * @throws \Cake\Http\Exception\InternalErrorException
     * @return void
     */
    private function assertServerPassphrase($passphrase): void
    {
        if (!is_string($passphrase)) {
            $msg = __('The config for the server private key passphrase is invalid.');
            throw new InternalErrorException($msg);
        }
    }

    /**
     * @param \App\Model\Entity\User $user object as sent from event
     * @return void
     */
    private function assertUserKey(User $user): void
    {
        if (!isset($user->gpgkey) || !isset($user->gpgkey->armored_key) || !isset($user->gpgkey->fingerprint)) {
            $msg = __('The user public key is not available or incomplete.');
            throw new InternalErrorException($msg);
        }

        $fingerprint = $user->gpgkey->fingerprint;
        if (!is_string($fingerprint) || !PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            $msg = __('The user public key fingerprint is not available or incomplete.');
            throw new InternalErrorException($msg);
        }

        $armoredKey = $user->gpgkey->armored_key;
        if (!is_string($armoredKey) || !PublicKeyValidationService::parseAndValidatePublicKey($armoredKey)) {
            $msg = __('The user armored key is not available or incomplete.');
            throw new InternalErrorException($msg);
        }
    }

    /**
     * @param \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey Metadata key entity.
     * @throws \Cake\Http\Exception\InternalErrorException
     * @return void
     */
    private function assertMetadataKey(MetadataKey $metadataKey): void
    {
        if (!isset($metadataKey->armored_key) || !isset($metadataKey->fingerprint)) {
            $msg = __('The metadata key is not available or incomplete.');
            throw new InternalErrorException($msg);
        }

        $fingerprint = $metadataKey->fingerprint;
        if (!is_string($fingerprint) || !PublicKeyValidationService::isValidFingerprint($fingerprint)) {
            $msg = __('The metadata key fingerprint is not available or incomplete.');
            throw new InternalErrorException($msg);
        }

        $armoredKey = $metadataKey->armored_key;
        if (!is_string($armoredKey) || !PublicKeyValidationService::parseAndValidatePublicKey($armoredKey)) {
            $msg = __('The metadata armored key is not available or incomplete.');
            throw new InternalErrorException($msg);
        }
    }

    /**
     * @param string $v4ResourceTypeId V4 Resource type identifier to get mapping from.
     * @return string Mapped V5 resource type identifier.
     * @throws \Cake\Http\Exception\InternalErrorException If mapping doesn't exist.
     */
    private function getV5ResourceType(string $v4ResourceTypeId): string
    {
        $mapping = ResourceType::getV5Mapping();

        if (!isset($mapping[$v4ResourceTypeId])) {
            throw new InternalErrorException(__('No resource type mapping for ID \'{0}\'', $v4ResourceTypeId));
        }

        return $mapping[$v4ResourceTypeId];
    }

    /**
     * Updates entity with given data.
     *
     * @param \App\Model\Entity\Resource $resource Resource entity to update.
     * @param array $data Data to update.
     * @return void
     */
    private function updateResource(Resource $resource, array $data): void
    {
        try {
            $resource = $this->Resources->patchEntity($resource, $data, [
                'accessibleFields' => [
                    'name' => true,
                    'username' => true,
                    'uri' => true,
                    'description' => true,
                    'resource_type_id' => true,
                    'metadata' => true,
                    'metadata_key_id' => true,
                    'metadata_key_type' => true,
                ],
                'validate' => 'v5',
            ]);
            $this->Resources->saveOrFail($resource, ['checkRules' => false]);
        } catch (\Exception $exception) {
            $msg = __('Unable to migrate resource ID: {0} to V5.', $resource->id);
            $msg .= ' ' . $exception->getMessage();
            throw new InternalErrorException($msg, 500, $exception);
        }
    }

    /**
     * @return \Passbolt\Metadata\Model\Entity\MetadataKey
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no metadata key record.
     */
    private function getMetadataKeyForEncryption()
    {
        $metadataKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataKeys');
        $metadataPrivateKeysTable = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataPrivateKeys');

        $subQuery = $metadataPrivateKeysTable->find()
            ->select(['metadata_key_id'])
            ->where(['user_id IS' => null])
            ->order(['created' => 'DESC'])
            ->limit(1);
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey */
        $metadataKey = $metadataKeysTable
            ->find()
            ->select(['id', 'fingerprint', 'armored_key'])
            ->where([
                'id' => $subQuery,
                'deleted IS NULL',
            ])
            ->firstOrFail();

        return $metadataKey;
    }

    /**
     * Append a new error to the result.
     *
     * @param array $error Error.
     * @return void
     */
    private function addError(array $error): void
    {
        $this->result['errors'][] = $error;
    }
}
