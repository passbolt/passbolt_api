<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Controller\Favorites;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Validation\Validation;

class FavoritesDeleteController extends AppController
{
    /**
     * Unmark a resource as favorite.
     *
     * @param string îd The identifier of favorite to delete.
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function delete($id = null)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The favorite id is not valid.'));
        }
        $this->loadModel('Favorites');

        // Retrieve the favorite.
        $options['Favorites.id'] = $id;
        $options['Favorites.user_id'] = $this->User->id();
        $favorite = $this->Favorites->findDelete($options)->first();
        if (empty($favorite)) {
            throw new NotFoundException(__('The favorite does not exist.'));
        }

        // Delete the favorite.
        $this->Favorites->delete($favorite);

        $this->success(__('The favorite was deleted.'));
    }
}
