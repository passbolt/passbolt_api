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
use App\Model\Table\ResourcesTable;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Service\OpenPGP\OpenPGPCommonUserOperationsTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Service\OpenPGP\OpenPGPCommonMetadataOperationsTrait;
use Passbolt\Metadata\Utility\MetadataSettingsAwareTrait;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class MigrateAllV4ResourcesToV5Service implements V4ToV5MigrationServiceInterface
{
    use LocatorAwareTrait;
    use OpenPGPCommonMetadataOperationsTrait;
    use OpenPGPCommonUserOperationsTrait;
    use OpenPGPCommonServerOperationsTrait;
    use MetadataSettingsAwareTrait;

    private ResourcesTable $Resources;

    /**
     * Result of migration.
     *
     * @var array
     */
    private array $result = [
        'success' => true,
        'migrated' => [],
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
     * @inheritDoc
     */
    public function getHumanReadableName(): string
    {
        return 'resource';
    }

    /**
     * Migrates all V4 resources to V5.
     *
     * @return array Result
     * @throws \Cake\Http\Exception\BadRequestException If V5 resource creation/modification is not allowed.
     */
    public function migrate(): array
    {
        $this->assertV5ResourceCreationEnabled();

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
            $dto = MetadataResourceDto::fromArray($resource->toArray());

            try {
                if ($dto->isV5()) {
                    $msg = __('Resource ID "{0}" is already V5', $resource->id);
                    throw new InternalErrorException($msg);
                }
                if (count($resource->permissions) === 0) {
                    $msg = __('No permission found for resource ID {0}', $resource->id);
                    throw new InternalErrorException($msg);
                }
                if (count($resource->permissions) === 1) {
                    $this->migratePersonal($dto, $resource);
                } else {
                    $this->migrateShared($dto, $resource);
                }
                $this->addMigrated($resource);
            } catch (\Exception $e) {
                // Continue with next resource if any error
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

        if (!isset($user)) {
            $msg = __('No user provided.') . ' ';
            $msg .= __('The metadata could not be encrypted for permission id: {0}.', $permission->id);
            throw new InternalErrorException($msg);
        }
        if (!isset($user->gpgkey)) {
            $msg = __('No OpenPGP key found for the user.') . ' ';
            $msg .= __('The metadata could not be encrypted with the user id: {0}.', $user->id);
            throw new InternalErrorException($msg);
        }
        try {
            $gpg = OpenPGPBackendFactory::get();
            $gpg->clearKeys();
            $gpg = $this->setSignKeyWithServerKey($gpg);
            $gpg = $this->setEncryptKeyWithUserKey($gpg, $user->gpgkey);
            $metadataClearText = json_encode($metadataArray, JSON_THROW_ON_ERROR);
            $metadataEncrypted = $gpg->encrypt($metadataClearText, true);
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
            $gpg = OpenPGPBackendFactory::get();
            $gpg->clearKeys();
            $gpg = $this->setSignKeyWithServerKey($gpg);
            $gpg = $this->setEncryptKeyWithMetadataKey($gpg, $metadataKey);
            $metadataClearText = json_encode($metadataArray, JSON_THROW_ON_ERROR);
            $metadataEncrypted = $gpg->encrypt($metadataClearText, true);
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
            //TODO support nullable resource.modified_by to allow server side modification
            //'modified_by' => null,
        ]);
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
                    'modified_by' => true,
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
        /** @var \Passbolt\Metadata\Model\Table\MetadataKeysTable $metadataKeysTable */
        $metadataKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataKeys');

        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey */
        $metadataKey = $metadataKeysTable->getLatestActiveKey();

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

    /**
     * @param \App\Model\Entity\Resource $resource entity
     * @return void
     */
    private function addMigrated(Resource $resource): void
    {
        $this->result['migrated'][] = $resource->id;
    }
}
