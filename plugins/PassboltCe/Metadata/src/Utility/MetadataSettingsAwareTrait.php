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

        $settingsDto = (new MetadataTypesSettingsGetService())->getSettings();

        if ($isV5) {
            if ($entity === MetadataTypesSettingsDto::ENTITY_RESOURCE) {
                if (!$settingsDto->isV5ResourceCreationAllowed()) {
                    throw new BadRequestException(__('Resource creation/modification with encrypted metadata not allowed.')); // phpcs:ignore
                }
            }
        } else {
            if ($entity === MetadataTypesSettingsDto::ENTITY_RESOURCE) {
                if (!$settingsDto->isV4ResourceCreationAllowed()) {
                    throw new BadRequestException(__('Resource creation/modification with cleartext metadata not allowed.')); // phpcs:ignore
                }
            }
        }
    }
}
