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
use App\Service\Favorites\FavoritesDeleteService;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;

/**
 * @property \App\Model\Table\FavoritesTable $Favorites
 */
class FavoritesDeleteController extends AppController
{
    /**
     * Unmark a resource as favorite.
     *
     * @param string $id The identifier of favorite to delete.
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\NotFoundException
     * @return void
     */
    public function delete(string $id)
    {
        $this->assertJson();

        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The favorite id is not valid.'));
        }

        (new FavoritesDeleteService())->delete($id, $this->User->id());

        $this->success(__('The favorite was deleted.'));
    }
}
