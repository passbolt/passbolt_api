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

namespace App\Controller\Favorites;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

class FavoritesAddController extends AppController
{
    /**
     * Mark a resource as favorite.
     *
     * @param string $foreignKey The identifier of the instance to mark as favorite.
     * @throws BadRequestException If the resource id is not valid
     * @throws NotFoundException If the resource does not exist
     * @throws NotFoundException If the resource is soft deleted
     * @throws NotFoundException If the user does not have access to the resource
     * @return void
     */
    public function add($foreignKey = null)
    {
        // Check request sanity
        if (!Validation::uuid($foreignKey)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }
        $this->loadModel('Favorites');

        // Build and validate the favorite entity.
        $favorite = $this->_buildAndValidateFavorite($foreignKey);

        // Save the favorite
        $result = $this->Favorites->save($favorite);
        $this->_handleValidationError($favorite);

        $this->success(__('The resource was marked as favorite.'), $result);
    }

    /**
     * Build and validate favorite entity from user input.
     *
     * @param string $foreignKey The identifier of the instance to mark as favorite.
     * @return \Cake\Datasource\EntityInterface $favorite favorite entity
     */
    protected function _buildAndValidateFavorite($foreignKey = null)
    {
        // Build entity and perform basic check.
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => $this->User->id(),
                'foreign_key' => $foreignKey,
                'foreign_model' => 'Resource',
            ],
            [
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_key' => true,
                    'foreign_model' => true
                ]
            ]
        );

        // Handle validation errors if any at this stage.
        $this->_handleValidationError($favorite);

        return $favorite;
    }

    /**
     * Manage validation errors.
     *
     * @param \Cake\Datasource\EntityInterface $favorite favorite
     * @throws BadRequestException if the record is already marked as favorite
     * @throws NotFoundException if the resource does not exist
     * @throws ValidationException if validation failed
     * @return void
     */
    protected function _handleValidationError($favorite)
    {
        $errors = $favorite->getErrors();
        if (!empty($errors)) {
            if (isset($errors['foreign_key']['resource_exists'])
                || isset($errors['foreign_key']['resource_is_not_soft_deleted'])
                || isset($errors['foreign_key']['has_resource_access'])) {
                throw new NotFoundException(__('The resource does not exist.'));
            }
            if (isset($errors['user_id']['favorite_unique'])) {
                throw new BadRequestException(__('This record is already marked as favorite.'));
            }

            throw new ValidationException(__('Could not validate favorite data.'));
        }
    }
}
