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

        // Retrieve and sanity the query options.
        $whitelist = ['contain' => ['secret', 'creator', 'modifier']];
        $options = $this->QueryString->get($whitelist);

        // If the result contains the secrets, include only the current user secret.
        if (isset($options['contain']['secret']) && $options['contain']['secret']) {
            $options['user_id'] = $this->User->id();
        }

        // Retrieve the resources.
        $resources = $this->Resources->findIndex($options);
        $this->success($resources);
    }
}
