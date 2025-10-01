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
namespace Passbolt\Metadata\Utility;

use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;

trait MetadataSettingsAwareTrait
{
    /**
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If V5 resource creation/modification is not allowed.
     */
    public function assertV5ResourceCreationEnabled(): void
    {
        $this->assertCreationAllowedByMetadataSettings(true, MetadataTypesSettingsDto::ENTITY_RESOURCE);
    }

    /**
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If V5 folder creation/modification is not allowed.
     */
    public function assertV5FolderCreationEnabled(): void
    {
        $this->assertCreationAllowedByMetadataSettings(true, MetadataTypesSettingsDto::ENTITY_FOLDER);
    }

    /**
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If V5 tag creation/modification is not allowed.
     */
    public function assertV5TagCreationEnabled(): void
    {
        $this->assertCreationAllowedByMetadataSettings(true, MetadataTypesSettingsDto::ENTITY_TAG);
    }

    /**
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If v4 tag creation/modification is not allowed.
     */
    public function assertV4TagCreationEnabled(): void
    {
        $this->assertCreationAllowedByMetadataSettings(false, MetadataTypesSettingsDto::ENTITY_TAG);
    }

    /**
     * @param bool $isV5 Format is V5 or not.
     * @param string $entity Entity to check for (resource, folder, etc.)
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If entity creation/modification is not allowed.
     */
    public function assertCreationAllowedByMetadataSettings(bool $isV5, string $entity): void
    {
        $v5Enabled = Configure::read('passbolt.v5.enabled');

        if (!$v5Enabled && $isV5) {
            throw new BadRequestException(__('V5 metadata format is not enabled.'));
        }
        if (!$v5Enabled) {
            // No need to assert if format is v4 and v5 config is disabled
            return;
        }

        $settingsDto = MetadataTypesSettingsGetService::getSettings();

        if ($isV5) {
            if ($entity === MetadataTypesSettingsDto::ENTITY_RESOURCE) {
                if (!$settingsDto->isV5ResourceCreationAllowed()) {
                    throw new BadRequestException(__('Resource creation/modification with encrypted metadata not allowed.')); // phpcs:ignore
                }
            } elseif ($entity === MetadataTypesSettingsDto::ENTITY_FOLDER) {
                if (!$settingsDto->isV5FolderCreationAllowed()) {
                    throw new BadRequestException(__('Folder creation/modification with encrypted metadata not allowed.')); // phpcs:ignore
                }
            } elseif ($entity === MetadataTypesSettingsDto::ENTITY_TAG) {
                if (!$settingsDto->isV5TagCreationAllowed()) {
                    throw new BadRequestException(__('Tag creation/modification with encrypted metadata not allowed.')); // phpcs:ignore
                }
            }
        } else {
            if ($entity === MetadataTypesSettingsDto::ENTITY_RESOURCE) {
                if (!$settingsDto->isV4ResourceCreationAllowed()) {
                    throw new BadRequestException(__('Resource creation with cleartext metadata not allowed.'));
                }
            } elseif ($entity === MetadataTypesSettingsDto::ENTITY_FOLDER) {
                if (!$settingsDto->isV4FolderCreationAllowed()) {
                    throw new BadRequestException(__('Folder creation with cleartext metadata not allowed.'));
                }
            } elseif ($entity === MetadataTypesSettingsDto::ENTITY_TAG) {
                if (!$settingsDto->isV4TagCreationAllowed()) {
                    throw new BadRequestException(__('Tag creation with cleartext metadata not allowed.'));
                }
            }
        }
    }
}
