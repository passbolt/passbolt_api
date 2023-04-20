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
 * @since         2.0.0
 */

namespace App\Controller\Share;

use App\Controller\AppController;
use App\Model\Entity\Group;
use App\Model\Entity\User;
use Cake\Collection\CollectionInterface;
use Cake\ORM\Query;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\GroupsTable $Groups
 */
class ShareSearchController extends AppController
{
    /**
     * Share search potential user or group to share with
     *
     * @return void
     */
    public function searchArosToShareWith()
    {
        $this->loadModel('Users');
        $this->loadModel('Groups');

        // Build the find options.
        $whitelist = [
            'filter' => ['search'],
        ];
        $options = $this->QueryString->get($whitelist);

        $groups = $this->_searchGroups($options);
        $users = $this->_searchUsers($options);

        $aros = $users->all()->append($groups);
        $output = $this->_formatResult($aros);

        $this->success(__('The operation was successful.'), $output);
    }

    /**
     * Search groups.
     *
     * @param array|null $options The find options
     * @return \Cake\ORM\Query
     */
    private function _searchGroups(?array $options = []): Query
    {
        $options['contain']['user_count'] = true;

        return $this->Groups->findIndex($options);
    }

    /**
     * Search the users.
     *
     * @param array|null $options The find options
     * @return \Cake\ORM\Query
     */
    private function _searchUsers(?array $options = []): Query
    {
        $options['filter']['is-active'] = true;

        return $this->Users->findIndex($this->User->role(), $options);
    }

    /**
     * Format the result alphabetically.
     *
     * @param \Cake\Collection\CollectionInterface $aros The collection of groups and users to sort.
     * @return \Cake\Collection\CollectionInterface
     */
    private function _formatResult(CollectionInterface $aros): CollectionInterface
    {
        $sortIterator = $aros->sortBy(function ($item) {
            if ($item instanceof Group) {
                return strtolower($item->name);
            } elseif ($item instanceof User) {
                return strtolower($item->username);
            }
        }, SORT_ASC, SORT_STRING);

        return $sortIterator->compile(false);
    }
}
