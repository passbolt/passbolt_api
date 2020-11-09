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
 * @since         3.0.0
 */
namespace App\Service\ResourceTypes;

use App\Model\Table\ResourceTypesTable;
use Cake\ORM\TableRegistry;

class ResourceTypesFinderService
{
    /**
     * @var \App\Model\Table\ResourceTypesTable
     */
    private $resourceTypesTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->resourceTypesTable = TableRegistry::getTableLocator()->get('ResourceTypes');
    }

    /**
     * Get resource types query
     *
     * @return array|\App\Service\ResourceTypes\Query
     */
    public function find()
    {
        return $this->resourceTypesTable
            ->find()
            ->formatResults(ResourceTypesTable::resultFormatter());
    }

    /**
     * Get a resource type by Id
     *
     * @param string $id uuid
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if resource type is not present
     * @return array|\Cake\Datasource\EntityInterface
     */
    public function get(string $id)
    {
        return $this->find()
            ->where(['id' => $id])
            ->firstOrFail();
    }
}
