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
namespace PassboltTestData\Shell\Task\Base;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class FavoritesDataTask extends DataTask
{
    public $entityName = 'Favorites';

    /**
     * Get the favorites data
     *
     * @return array
     */
    public function getData()
    {
        $favorites[] = [
            'id' => UuidFactory::uuid('favorite.id.ada-apache'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'foreign_key' => UuidFactory::uuid('resource.id.apache'),
            'foreign_model' => 'Resource'
        ];
        $favorites[] = [
            'id' => UuidFactory::uuid('favorite.id.dame-apache'),
            'user_id' => UuidFactory::uuid('user.id.dame'),
            'foreign_key' => UuidFactory::uuid('resource.id.apache'),
            'foreign_model' => 'Resource'
        ];
        $favorites[] = [
            'id' => UuidFactory::uuid('favorite.id.dame-april'),
            'user_id' => UuidFactory::uuid('user.id.dame'),
            'foreign_key' => UuidFactory::uuid('resource.id.april'),
            'foreign_model' => 'Resource'
        ];
        $favorites[] = [
            'id' => UuidFactory::uuid('favorite.id.lynne-cakephp'),
            'user_id' => UuidFactory::uuid('user.id.lynne'),
            'foreign_key' => UuidFactory::uuid('resource.id.cakephp'),
            'foreign_model' => 'Resource'
        ];

        return $favorites;
    }
}
