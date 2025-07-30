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
namespace Passbolt\TestData\Command\Base;

use App\Utility\UuidFactory;
use Passbolt\TestData\Lib\DataCommand;

class RolesDataCommand extends DataCommand
{
    public string $entityName = 'Roles';

    /**
     * Get the roles data
     *
     * @return array
     */
    public function getData(): array
    {
        $roles[] = [
            'id' => UuidFactory::uuid('role.id.guest'),
            'name' => 'guest',
            'description' => 'Non logged in user',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25',
        ];
        $roles[] = [
            'id' => UuidFactory::uuid('role.id.user'),
            'name' => 'user',
            'description' => 'Logged in user',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25',
        ];
        $roles[] = [
            'id' => UuidFactory::uuid('role.id.admin'),
            'name' => 'admin',
            'description' => 'Organization administrator',
            'created' => '2012-07-04 13:39:25',
            'modified' => '2012-07-04 13:39:25',
        ];

        return $roles;
    }
}
