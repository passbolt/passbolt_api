<?php
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

namespace App\Controller\ResourceTypes;

use App\Controller\AppController;
use App\Model\Table\ResourceTypesTable;
use Cake\ORM\TableRegistry;

class ResourceTypesIndexController extends AppController
{
    /**
     * Resource Types Index action
     *
     * @return void
     */
    public function index()
    {
        /** @var ResourceTypesTable $resourceTypesTable */
        $resourceTypesTable = TableRegistry::getTableLocator()->get('ResourceTypes');
        $resourceTypes = $resourceTypesTable->find()->all();
        $this->success(__('The operation was successful.'), $resourceTypes);
    }
}
