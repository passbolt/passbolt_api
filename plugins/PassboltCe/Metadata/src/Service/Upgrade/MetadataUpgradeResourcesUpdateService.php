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

use Passbolt\Metadata\Model\Validation\MetadataResourcesBatchUpgradeValidationService;
use Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyResourcesUpdateService;

class MetadataUpgradeResourcesUpdateService extends MetadataRotateKeyResourcesUpdateService
{
    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->metadataBatchValidationService = new MetadataResourcesBatchUpgradeValidationService();
    }
}
