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
 * @since         2.13.0
 */
namespace Passbolt\Reports\Utility\CombinedReports\Users;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Passbolt\Reports\Utility\AbstractSingleReport;

class NonActiveUsersListReport extends AbstractSingleReport
{
    const SLUG = 'non-active-users-list';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @param UsersTable|null $usersTable Instance of UsersTable
     */
    public function __construct(UsersTable $usersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
        $this->setSlug(self::SLUG)
            ->setName(__('Non active user list report'))
            ->setDescription(__('List of all users who have not activated their account.'));
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $users = $this->usersTable->find()
            ->contain(['Profiles', 'Roles'])
            ->where([
                'Users.deleted' => false,
                'Users.active' => false,
            ])
            ->order(['Users.created' => 'ASC'])
            ->all()
            ->toList();

        return [
            'users' => $users,
        ];
    }
}
