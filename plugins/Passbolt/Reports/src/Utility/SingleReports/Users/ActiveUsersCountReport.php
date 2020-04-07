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
namespace Passbolt\Reports\Utility\SingleReports\Users;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Passbolt\Reports\Utility\AbstractSingleReport;

class ActiveUsersCountReport extends AbstractSingleReport
{
    const SLUG = 'active-users-count';

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
            ->setName(__('Active user count.'))
            ->setDescription(__('Total number of users who have activated their account.'));
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $count = $this->usersTable->find()
            ->contain(['Profiles'])
            ->where([
                'Users.deleted' => false,
                'Users.active' => true,
            ])
            ->order(['Users.created' => 'ASC'])
            ->all()
            ->count();

        return [
            'count' => $count,
        ];
    }
}
