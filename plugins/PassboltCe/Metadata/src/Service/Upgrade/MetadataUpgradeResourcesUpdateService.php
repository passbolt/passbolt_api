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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Service\Upgrade;

use App\Utility\UserAccessControl;
use Passbolt\Metadata\Model\Validation\MetadataResourcesBatchUpgradeValidationService;
use Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyResourcesUpdateService;

class MetadataUpgradeResourcesUpdateService extends MetadataRotateKeyResourcesUpdateService
{
    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param array $requestData Request data.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If data is invalid.
     * @throws \App\Error\Exception\CustomValidationException If data is invalid.
     * @throws \Cake\Http\Exception\NotFoundException If one or more resources are not found.
     */
    public function updateMany(UserAccessControl $uac, array $requestData): void
    {
        // Check that the upgrade is possible
        $uac->assertIsAdmin();
        $this->assertRequestData($requestData);
        $metadataBatchValidationService = new MetadataResourcesBatchUpgradeValidationService();
        // As this service is accessible to admins only, the upgrade v4 to v5 rule should be skipped, as it applies only to users
        $data = $metadataBatchValidationService->validateMany($requestData);
        $this->updateData($uac, $data, $metadataBatchValidationService->getEntities());
    }
}
