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
use Cake\Http\Exception\InternalErrorException;
use Passbolt\Metadata\Model\Validation\MetadataResourcesBatchUpgradeValidationService;
use Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyResourcesUpdateService;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class MetadataUpgradeResourcesUpdateService extends MetadataRotateKeyResourcesUpdateService
{
    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->metadataBatchValidationService = new MetadataResourcesBatchUpgradeValidationService();
    }

    /**
     * @inheritDoc
     */
    protected function updateData(UserAccessControl $uac, array $data, array $entitiesToUpdate): void
    {
        // Set mapped v5 resource type id
        foreach ($data as $i => $value) {
            $resource = $entitiesToUpdate[$value['id']];
            $data[$i]['resource_type_id'] = $this->getV5ResourceType($resource->get('resource_type_id'));
        }

        parent::updateData($uac, $data, $entitiesToUpdate);
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
}
