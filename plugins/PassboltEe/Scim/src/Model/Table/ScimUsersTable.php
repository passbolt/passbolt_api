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
 * @since         5.11.0
 */

namespace Passbolt\Scim\Model\Table;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Cake\ORM\Query\SelectQuery;

/**
 * ScimUsersTable wraps the core UsersTable with SCIM-specific finders.
 *
 * @psalm-suppress MethodSignatureMismatch CakePHP magic findBy* conflicts with UsersFindersTrait::findByUsername
 */
class ScimUsersTable extends UsersTable
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Ensure the same table name and alias as UsersTable so that
        // aliased conditions (e.g. Users.id) and associations work correctly.
        $this->setTable('users');
        $this->setAlias('Users');
        $this->setEntityClass(User::class);
    }

    /**
     * Find a user by email with an optional FOR UPDATE lock.
     *
     * @param string $email The email/username to search for.
     * @param bool $forUpdate Whether to acquire a row-level lock.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByEmailForScim(string $email, bool $forUpdate = false): SelectQuery
    {
        $query = $this->find()
            ->where([
                $this->aliasField('username') => $email,
                $this->aliasField('deleted') => false,
            ]);

        if ($forUpdate) {
            $query->epilog('FOR UPDATE');
        }

        return $query;
    }

    /**
     * Find a user by explicit conditions, optionally including soft-deleted users.
     *
     * @param array<string, mixed> $conditions Query conditions.
     * @param bool $findDeleted Include soft-deleted users.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findForScim(array $conditions, bool $findDeleted = false): SelectQuery
    {
        if (!$findDeleted) {
            $conditions[$this->aliasField('deleted')] = false;
        }

        return $this->find()->where($conditions);
    }
}
