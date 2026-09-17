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
namespace Passbolt\OfflineMode\Model\Rule\Checker;

use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\OfflineMode\Model\Rule\ForeignItemV5CheckerInterface;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class ResourceV5Checker implements ForeignItemV5CheckerInterface
{
    use LocatorAwareTrait;

    /**
     * @inheritDoc
     */
    public function isV5(string $foreignKey): bool
    {
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = $this->fetchTable('Resources');
        $row = $resourcesTable->find()
            ->select(['resource_type_id'])
            ->where(['id' => $foreignKey])
            ->disableHydration()
            ->first();

        if ($row === null) {
            return true;
        }

        return in_array($row['resource_type_id'], ResourceType::getV5ResourceTypes(), true);
    }
}
