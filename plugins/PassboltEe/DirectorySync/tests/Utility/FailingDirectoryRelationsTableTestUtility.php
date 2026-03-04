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
 * @since         5.10.0
 */
namespace Passbolt\DirectorySync\Test\Utility;

use App\Model\Entity\GroupsUser;
use Exception;
use Passbolt\DirectorySync\Model\Table\DirectoryRelationsTable;

/**
 * A test utility that extends DirectoryRelationsTable and overrides createFromGroupUser
 * to simulate a failure during directory relation creation.
 *
 * Used to verify that GroupUser and DirectoryRelation creation are atomic —
 * if the relation creation fails, the GroupUser should be rolled back.
 */
class FailingDirectoryRelationsTableTestUtility extends DirectoryRelationsTable
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setAlias('DirectoryRelations');
    }

    /**
     * @inheritDoc
     */
    public function createFromGroupUser(GroupsUser $groupUser): mixed
    {
        throw new Exception('Simulated createFromGroupUser failure');
    }
}
