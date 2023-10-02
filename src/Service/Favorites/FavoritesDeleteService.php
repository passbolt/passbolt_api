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
 * @since         3.9.0
 */
namespace App\Service\Favorites;

use App\Model\Entity\Favorite;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class FavoritesDeleteService
{
    /**
     * @var \App\Model\Table\FavoritesTable
     */
    private $Favorites;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites');
    }

    /**
     * Unmarks a resource as favorite.
     *
     * @param string $id The identifier of favorite to delete.
     * @param string|null $userId Currently authenticated user's ID. Used to determine
     *                            if user can delete this favorite or not.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When given ID doesn't exist.
     * @throws \Cake\Http\Exception\BadRequestException When unable to delete the entity.
     */
    public function delete(string $id, ?string $userId): void
    {
        // Retrieve the favorite.
        try {
            $favorite = $this->Favorites->get($id);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The favorite does not exist.'), 404, $e);
        }

        // Delete the favorite.
        $this->Favorites->delete($favorite, ['Favorites.user_id' => $userId]);
        $this->_handleDeleteErrors($favorite);
    }

    /**
     * Manage delete errors.
     *
     * @param \App\Model\Entity\Favorite $favorite Favorite entity.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When user cannot delete this favorite entity.
     * @throws \Cake\Http\Exception\BadRequestException When unable to delete the entity.
     */
    private function _handleDeleteErrors(Favorite $favorite): void
    {
        $errors = $favorite->getErrors();

        if (!empty($errors)) {
            if (isset($errors['user_id']['is_owner'])) {
                throw new NotFoundException(__('The favorite does not exist.'));
            }

            throw new BadRequestException(__('Could not delete favorite.'));
        }
    }
}
