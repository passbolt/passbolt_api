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

namespace App\Controller\Favorites;

use App\Controller\AppController;
use App\Service\Favorites\FavoritesAddService;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;

class FavoritesAddController extends AppController
{
    /**
     * Mark a resource as favorite.
     *
     * @param string $foreignKey The identifier of the instance to mark as favorite.
     * @throws \Cake\Http\Exception\BadRequestException If the resource id is not valid
     * @throws \Cake\Http\Exception\NotFoundException If the resource does not exist
     * @throws \Cake\Http\Exception\NotFoundException If the resource is soft deleted
     * @throws \Cake\Http\Exception\NotFoundException If the user does not have access to the resource
     * @return void
     */
    public function add(string $foreignKey)
    {
        $this->assertJson();

        // Check request sanity
        if (!Validation::uuid($foreignKey)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        $result = (new FavoritesAddService())->add($this->User->getAccessControl(), $foreignKey);

        $this->success(__('The resource was marked as favorite.'), $result);
    }
}
