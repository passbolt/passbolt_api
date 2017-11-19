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

namespace App\Controller\Share;

use App\Controller\AppController;
use App\Model\Entity\Group;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Validation\Validation;

class ShareSearchController extends AppController
{
    /**
     * Share search potential aros action
     *
     * @param string $resourceId uuid Identifier of the resource
     * @throws BadRequestException if the resource id is not a uuid
     * @throws NotFoundException if the resource does not exist
     * @throws NotFoundException if the user does not have access to the resource
     * @return void
     */
    public function searchArosToShareWith($resourceId)
    {
        // Check request sanity
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }

        $this->loadModel('Resources');

        // Retrieve the resource to search the aros for.
        try {
            $resource = $this->Resources->get($resourceId);
        }
        catch(RecordNotFoundException $e) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
        // The resource is not soft deleted.
        if ($resource->deleted) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
        // The user can access the resource.
        if (!$this->Resources->hasAccess($this->User->id(), $resourceId)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        $this->loadModel('Users');
        $this->loadModel('Groups');

        // Build the find options.
        $whitelist = [
            'filter' => ['search']
        ];
        $options = $this->QueryString->get($whitelist);
        $options['filter']['has-not-permission'] = [$resourceId];

        // Retrieve the groups.
        $groupsOptions = array_merge($options, [
            'contain' => ['user_count' => true]
        ]);
        $groups = $this->Groups->findIndex($groupsOptions);

        // Retrieve the users.
        $users = $this->Users->findIndex(Role::USER, $options);

        // Merge the users and groups.
        $aros = $users->append($groups);

        // Sort the result alphabetically.
        $output = $this->_formatResult($aros);

        $this->success(__('The operation was successful.'), $output);
    }

    /**
     * Format the result alphabetically.
     *
     * @param \Cake\Collection\Collection $aros The collection of groups and users to sort.
     * @return \Cake\Collection\Collection
     */
    private function _formatResult($aros)
    {
        $sortIterator = $aros->sortBy(function($item) {
            if ($item instanceOf Group) {
                return strtolower($item->name);
            } else if ($item instanceOf User) {
                return strtolower($item->username);
            }
        }, SORT_ASC, SORT_STRING);
        return $sortIterator->compile(false);
    }
}
