<?php
declare(strict_types=1);

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

trait AssertUsersTrait
{
    public function assertUserExist($id, ?array $where = null)
    {
        $where['id'] = $id;
        $Users = TableRegistry::getTableLocator()->get('Users');
        $results = $Users->find()->where($where)->all()->toArray();
        $this->assertEquals(1, count($results), __('The user does not exist'));
    }

    public function assertUserNotExist(?array $where = null)
    {
        $Users = TableRegistry::getTableLocator()->get('Users');
        $results = $Users->find()->where($where)->all()->toArray();
        $this->assertEquals(0, count($results), __('The user should not exist'));
    }

    /**
     * Assert user full name
     *
     * @param $id
     * @param string $firstName
     * @param string $lastName
     * @return void
     */
    public function assertUserFullName($id, string $firstName, string $lastName): void
    {
        /** @var \App\Model\Table\UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');
        /** @var \App\Model\Entity\User $user */
        $user = $Users->find()->contain(['Profiles'])->where([$Users->aliasField('id') => $id])->first();
        $this->assertNotEmpty($user, __('The user does not exist'));
        $this->assertTextEquals($firstName, $user->profile->first_name, __('First name is wrong. Actual first name: {0}', $user->profile->first_name));
        $this->assertTextEquals($lastName, $user->profile->last_name, __('Last name is wrong. Actual last name: {0}', $user->profile->last_name));
    }
}
