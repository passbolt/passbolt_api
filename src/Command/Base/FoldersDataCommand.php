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
 * @since         2.0.0
 */

namespace PassboltTestData\Command\Base;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataCommand;

class FoldersDataCommand extends DataCommand
{
    public $entityName = 'Folders';

    /**
     * Get the tags data
     *
     * @return array
     */
    public function getData()
    {
        $data = $this->_getData();
        $adminUserId = UuidFactory::uuid('user.id.admin');
        foreach ($data as $i => $row) {
            if (!array_key_exists('created', $row)) {
                $data[$i]['created'] = '2020-02-01 00:00:00';
            }
            if (!array_key_exists('modified', $row)) {
                $data[$i]['modified'] = '2020-02-01 00:00:00';
            }
            if (!array_key_exists('created_by', $row)) {
                $data[$i]['created_by'] = $adminUserId;
            }
            if (!array_key_exists('modified_by', $row)) {
                $data[$i]['modified_by'] = $adminUserId;
            }
        }

        return $data;
    }

    /**
     * Get the tags data
     *
     * @return array
     */
    public function _getData()
    {
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.accounting'),
            'name' => 'Accounting'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.bank'),
            'name' => 'Bank'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.blogs'),
            'name' => 'Blogs'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.certificates'),
            'name' => 'Certificates'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.communication'),
            'name' => 'Communication'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.continuous-integration'),
            'name' => 'Continous Integration'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.credit-cards'),
            'name' => 'Credit Cards'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.human-resources'),
            'name' => 'Human Resources'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.it'),
            'name' => 'IT'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.licenses'),
            'name' => 'Licenses'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.marketing'),
            'name' => 'Marketing'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.private-ada'),
            'name' => 'Private'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.private-admin'),
            'name' => 'Private'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.production'),
            'name' => 'Production'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.sales'),
            'name' => 'Sales'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.social-networks'),
            'name' => 'Social Networks'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.staging'),
            'name' => 'Staging'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.travel'),
            'name' => 'Travel'
        ];
        $folders[] = [
            'id' => UuidFactory::uuid('folder.id.vat'),
            'name' => 'VAT'
        ];

        return $folders;
    }
}
