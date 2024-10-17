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
namespace Passbolt\Tags\Service\Metadata;

use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Service\OpenPGP\OpenPGPCommonUserOperationsTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Service\Migration\V4ToV5MigrationServiceInterface;
use Passbolt\Metadata\Service\OpenPGP\OpenPGPCommonMetadataOperationsTrait;
use Passbolt\Metadata\Utility\MetadataSettingsAwareTrait;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Model\Entity\Tag;
use Passbolt\Tags\Model\Table\TagsTable;

class MigrateAllV4TagsToV5Service implements V4ToV5MigrationServiceInterface
{
    use LocatorAwareTrait;
    use MetadataSettingsAwareTrait;
    use OpenPGPCommonMetadataOperationsTrait;
    use OpenPGPCommonUserOperationsTrait;
    use OpenPGPCommonServerOperationsTrait;

    private TagsTable $Tags;

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
        $this->Tags = $this->fetchTable('Passbolt/Tags.Tags');
    }

    /**
     * @inheritDoc
     */
    public function getHumanReadableName(): string
    {
        return 'tag';
    }

    /**
     * Migrates all V4 tags to V5.
     *
     * @return array Result
     * @throws \Cake\Http\Exception\BadRequestException If V5 tag creation/modification is allowed.
     */
    public function migrate(): array
    {
        $this->assertV5TagCreationEnabled();

        $tags = $this->Tags
            ->find()
            ->contain(['Users.Gpgkeys'])
            ->where(['slug IS NOT NULL'])
            ->all()
            ->toArray();

        if (count($tags) < 1) {
            $this->addError(['error_message' => __('No tags to migrate.')]);

            return $this->getResult();
        }

        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        foreach ($tags as $tag) {
            $dto = MetadataTagDto::fromArray($tag->toArray());

            try {
                if ($dto->isV5()) {
                    $msg = __('Tag ID "{0}" is already V5', $tag->id);
                    throw new InternalErrorException($msg);
                }

                if ($tag->is_shared) {
                    $this->migrateShared($dto, $tag);
                } else {
                    $this->migratePersonal($dto, $tag);
                }

                $this->addMigrated($tag);
            } catch (\Exception $e) {
                // Continue with next resource if any error
                $error = ['tag_id' => $tag->id, 'error_message' => $e->getMessage()];
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
     * @param \Passbolt\Tags\Model\Entity\Tag $tag entity
     * @return void
     */
    private function addMigrated(Tag $tag): void
    {
        $this->result['migrated'][] = $tag->id;
    }

    /**
     * @param \Passbolt\Tags\Model\Dto\MetadataTagDto $dto DTO.
     * @param \Passbolt\Tags\Model\Entity\Tag $tag Entity.
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no metadata key record.
     */
    private function migrateShared(MetadataTagDto $dto, Tag $tag): void
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

        $this->updateTag($tag, [
            'slug' => null,
            'metadata' => $metadataEncrypted,
            'metadata_key_id' => $metadataKey->id,
            'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
            'is_shared' => true,
        ]);
    }

    /**
     * @param \Passbolt\Tags\Model\Dto\MetadataTagDto $dto DTO.
     * @param \Passbolt\Tags\Model\Entity\Tag $tag Tag entity.
     * @return void
     */
    private function migratePersonal(MetadataTagDto $dto, Tag $tag): void
    {
        $metadataArray = $dto->getClearTextMetadata();
        $users = $tag->get('users');

        if (!is_array($users) || count($users) < 1) {
            throw new InternalErrorException(__('No user found for the personal tag id: "{0}".', $tag->id));
        }

        /** @var \App\Model\Entity\User $user */
        $user = $users[0];
        if (is_null($user)) {
            throw new InternalErrorException(__('User contain data missing for the tag id: "{0}".', $tag->id));
        }
        if (is_null($user->gpgkey)) {
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

        $this->updateTag($tag, [
            'slug' => null,
            'metadata' => $metadataEncrypted,
            'metadata_key_id' => $user->gpgkey->id,
            'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
            'is_shared' => false,
        ]);
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
        $metadataKey = $metadataKeysTable->getLatestNonDeletedKey();

        return $metadataKey;
    }

    /**
     * Updates entity with given data.
     *
     * @param \Passbolt\Tags\Model\Entity\Tag $tag Tag entity to update.
     * @param array $data Data to update.
     * @return void
     */
    private function updateTag(Tag $tag, array $data): void
    {
        try {
            $tag = $this->Tags->patchEntity($tag, $data, [
                'accessibleFields' => [
                    'slug' => true,
                    'metadata' => true,
                    'metadata_key_id' => true,
                    'metadata_key_type' => true,
                    'is_shared' => true,
                ],
                'validate' => 'v5',
            ]);
            $this->Tags->saveOrFail($tag, ['checkRules' => false]);
        } catch (\Exception $exception) {
            $msg = __('Unable to migrate tag id: {0} to V5.', $tag->id);
            $msg .= ' ' . $exception->getMessage();
            throw new InternalErrorException($msg, 500, $exception);
        }
    }
}
