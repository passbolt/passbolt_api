<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Controller\Resources;

use App\Controller\AppController;

class ResourcesIndexController extends AppController
{
    /**
     * Resource Index action
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Resources');
        $findOptions = [
            'contain' => []
        ];

        $containParam = $this->request->getQuery('contain');
        if (isset($containParam['secrets']) && $containParam['secrets'] == '1') {
            $findOptions['contain']['secrets'] = true;
            $findOptions['user_id'] = $this->User->id();
        }

        $resources = $this->Resources->findIndex($findOptions);
        $this->success($resources);
    }
}
