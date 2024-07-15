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

namespace App\Controller\Groups;

use App\Controller\AppController;
use App\Database\Type\ISOFormatDateTimeType;

/**
 * GroupsIndexController Class
 */
class GroupsIndexController extends AppController
{
    /**
     * Group Index action
     *
     * @return void
     */
    public function index()
    {
        $this->assertJson();

        /** @var \App\Model\Table\GroupsTable $groupsTable */
        $groupsTable = $this->fetchTable('Groups');

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => [
                'modifier', 'modifier.profile', 'my_group_user',
                'users', 'groups_users', 'groups_users.user', 'groups_users.user.profile', 'groups_users.user.gpgkey',
                // Deprecated contain use plural form
                // @deprecated remove when v2 support is dropped
                'user', 'group_user', 'group_user.user', 'group_user.user.profile', 'group_user.user.gpgkey',
            ],
            'filter' => ['has-users', 'has-managers'],
            'order' => ['Group.name'],
        ];
        $options = $this->QueryString->get($whitelist);
        if (isset($options['contain']['my_group_user'])) {
            $options['my_user_id'] = $this->User->id();
        }

        // Performance improvement: map query result datetime properties to string.
        ISOFormatDateTimeType::mapDatetimeTypesToMe();
        $groups = $groupsTable->findIndex($options)->all();
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();
        $this->success(__('The operation was successful.'), $groups);
    }
}
