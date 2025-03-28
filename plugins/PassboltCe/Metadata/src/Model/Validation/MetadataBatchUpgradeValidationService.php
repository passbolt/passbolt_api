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
namespace Passbolt\Metadata\Model\Validation;

use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

abstract class MetadataBatchUpgradeValidationService extends MetadataBatchUpdateValidationService
{
    /**
     * @param array $entityIds Resource ids to find.
     * @return \Cake\ORM\Query
     */
    protected function queryEntitiesFromIds(array $entityIds): Query
    {
        $Table = TableRegistry::getTableLocator()->get($this->getModel());

        return $Table->find('v4')
            ->select([
                $Table->aliasField('id'),
                $Table->aliasField('metadata'),
                $Table->aliasField('modified'),
                $Table->aliasField('modified_by'),
            ])
            ->where([
                $Table->aliasField('id') . ' IN' => $entityIds,
            ])
            ->orderByDesc($Table->aliasField('id'));
    }
}
