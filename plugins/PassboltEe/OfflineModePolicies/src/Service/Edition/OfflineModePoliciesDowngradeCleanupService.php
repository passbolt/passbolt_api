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
 * @since         5.16.0
 */
namespace Passbolt\OfflineModePolicies\Service\Edition;

use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Edition\Service\Cleanup\EditionDowngradeCleanupServiceInterface;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;

/**
 * Updates Offline Mode settings to the CE default values.
 */
class OfflineModePoliciesDowngradeCleanupService implements EditionDowngradeCleanupServiceInterface
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function cleanup(): void
    {
        $table = $this->fetchTable('Passbolt/OfflineMode.OfflineModeSettings');

        /** @var \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting|null $entity */
        $entity = $table->find()->first();
        if ($entity === null) {
            return;
        }

        $entity->set('value', OfflineSettingsDto::createFromDefault()->toSettingsArray());
        $table->saveOrFail($entity);
    }
}
