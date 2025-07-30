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
namespace Passbolt\TestData\Command\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Passbolt\TestData\Lib\DataCommand;

class FavoritesDataCommand extends DataCommand
{
    public string $entityName = 'Favorites';

    /**
     * Get the favorites data
     *
     * @return array
     */
    public function getData(): array
    {
        $favorites = [];

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = $this->fetchTable('Resources');

        $users = $usersTable->findIndex(Role::USER);
        foreach ($users as $user) {
            $options['order']['Resources.modified'] = true;
            $resources = $resourcesTable->findIndex($user->get('id'), $options);
            foreach ($resources as $resource) {
                $favorites[] = [
                    'id' => UuidFactory::uuid('favorite.id.' . $resource->get('id') . '-' . $user->get('id')),
                    'user_id' => $user->get('id'),
                    'foreign_key' => $resource->get('id'),
                    'foreign_model' => 'Resource',
                    'created' => date('Y-m-d H:i:s'),
                ];
            }
            unset($resources);
        }

        return $favorites;
    }
}
