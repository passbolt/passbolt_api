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
namespace PassboltTestData\Command\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataCommand;

class FavoritesDataCommand extends DataCommand
{
    public $entityName = 'Favorites';

    /**
     * Get the favorites data
     *
     * @return array
     */
    public function getData()
    {
        $favorites = [];

        $this->loadModel('Users');
        $this->loadModel('Resources');

        $users = $this->Users->findIndex(Role::USER);
        foreach ($users as $user) {
            $start = time();
            $options['order']['Resources.modified'] = true;
            $resources = $this->Resources->findIndex($user->id, $options);
            foreach ($resources as $resource) {
                $favorites[] = [
                    'id' => UuidFactory::uuid('favorite.id.' . $resource->id . '-' . $user->id),
                    'user_id' => $user->id,
                    'foreign_key' => $resource->id,
                    'foreign_model' => 'Resource',
                    'created' => date('Y-m-d H:i:s')
                ];
            }
            $end = time();
            unset($resources);
        }

        return $favorites;
    }
}
