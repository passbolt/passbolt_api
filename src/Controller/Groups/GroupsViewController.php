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

namespace App\Controller\Groups;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Validation\Validation;

class GroupsViewController extends AppController
{
    /**
     * Group View action
     *
     * @throws BadRequestException if the group id is not a uuid
     * @throws NotFoundException if the group does not exist
     * @param string $id uuid Identifier of the group
     * @return void
     */
    public function view($id)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The group id is not valid.'));
        }
        $this->loadModel('Groups');

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => ['modifier', 'user']
        ];
        $options = $this->QueryString->get($whitelist);

        // Retrieve the group.
        $group = $this->Groups->findView($id, $options)->first();
        if (empty($group)) {
            throw new NotFoundException(__('The group does not exist.'));
        }
        $this->success('The operation was successful.', $group);
    }
}
