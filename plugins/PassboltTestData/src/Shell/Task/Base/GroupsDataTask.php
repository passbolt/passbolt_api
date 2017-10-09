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
namespace PassboltTestData\Shell\Task\Base;;

use App\Utility\Common;
use PassboltTestData\Lib\DataTask;

class GroupsDataTask extends DataTask
{
    public $entityName = 'Groups';

    protected function _getData()
    {
        $groups[] = [
            'id' => Common::uuid('group.id.accounting'),
            'name' => 'Accounting',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.board'),
            'name' => 'Board',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.creative'),
            'name' => 'Creative',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.developer'),
            'name' => 'Developer',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.ergonom'),
            'name' => 'Ergonom',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.freelancer'),
            'name' => 'Freelancer',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.human_resource'),
            'name' => 'Human resource',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.it_support'),
            'name' => 'IT support',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.leadership_team'),
            'name' => 'Leadership team',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.management'),
            'name' => 'Management',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.marketing'),
            'name' => 'Marketing',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.network'),
            'name' => 'Network',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.operations'),
            'name' => 'Operations',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.procurement'),
            'name' => 'Procurement',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.quality_assurance'),
            'name' => 'Quality assurance',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.resource_planning'),
            'name' => 'Resource planning',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.sales'),
            'name' => 'Sales',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.traffic'),
            'name' => 'Traffic',
            'deleted' => 0,
            'created' => '2016-01-29 13:39:25',
            'modified' => '2016-01-29 13:39:25',
            'created_by' => Common::uuid('user.id.admin'),
            'modified_by' => Common::uuid('user.id.admin')
        ];
        $groups[] = [
            'id' => Common::uuid('group.id.deleted'),
            'name' => 'deleted',
            'deleted' => 1,
            'created' => '2016-02-02 18:59:05',
            'modified' => '2016-02-02 18:59:05',
            'created_by' => Common::uuid('user.id.anonymous'),
            'modified_by' => Common::uuid('user.id.anonymous')
        ];

        return $groups;
    }
}
