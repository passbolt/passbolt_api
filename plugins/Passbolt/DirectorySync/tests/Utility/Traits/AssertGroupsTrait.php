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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Test\Utility\Traits;

use Cake\ORM\TableRegistry;

trait AssertGroupsTrait
{
    public function assertGroupExist($id = null, $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $Groups = TableRegistry::getTableLocator()->get('Groups');
        $results = $Groups->find()->where($where)->all()->toArray();
        $this->assertEquals(count($results), 1);

        return $results[0];
    }

    public function assertGroupNotExist($id = null, $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $Groups = TableRegistry::getTableLocator()->get('Groups');
        $results = $Groups->find()->where($where)->all()->toArray();
        $this->assertEmpty($results);
    }
}
